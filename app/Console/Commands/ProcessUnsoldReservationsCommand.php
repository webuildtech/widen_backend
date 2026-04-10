<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use App\Models\ReservationSlot;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Log;

class ProcessUnsoldReservationsCommand extends Command
{
    protected $signature = 'app:process-unsold-reservations-command';

    public function handle(): int
    {
        Reservation::query()
            ->with('slots')
            ->whereIsPaid(true)
            ->whereNotNull('canceled_at')
            ->whereColumn('price_with_vat', '!=', 'refunded_amount')
            ->whereBetween('start_time', [now()->subMinutes(21), now()->addMinutes(10)])
            ->lazyById()
            ->each(function (Reservation $reservation): void {
                if ($this->shouldSkipReservation($reservation)) {
                    return;
                }

                DB::transaction(function () use ($reservation): void {
                    $this->processSlots(
                        reservation: $reservation,
                        slots: $reservation->slots
                            ->where('try_sell', true)
                            ->sortBy('slot_start')
                            ->values(),
                        restore: true,
                    );

                    $this->processSlots(
                        reservation: $reservation,
                        slots: $reservation->slots
                            ->where('is_refunded', true)
                            ->sortBy('slot_start')
                            ->values(),
                        restore: false,
                    );

                    $reservation->delete();
                });

                Log::info("Reservation {$reservation->id} unsold, restoring slots...");
            });

        return self::SUCCESS;
    }

    private function shouldSkipReservation(Reservation $reservation): bool
    {
        return $reservation->slots
            ->where('try_sell', true)
            ->contains(function (ReservationSlot $slot): bool {
                return ReservationSlot::query()
                    ->whereCourtId($slot->court_id)
                    ->whereSlotStart($slot->slot_start)
                    ->whereSlotEnd($slot->slot_end)
                    ->where('id', '!=', $slot->id)
                    ->exists();
            });
    }

    /**
     * @param Collection<int, ReservationSlot> $slots
     */
    private function processSlots(Reservation $reservation, Collection $slots, bool $restore): void
    {
        foreach ($this->groupConsecutiveSlots($slots) as $slotGroup) {
            $this->createReservation(
                reservation: $reservation,
                slots: $slotGroup,
                restore: $restore,
            );
        }
    }

    /**
     * @param Collection<int, ReservationSlot> $slots
     * @return Collection<int, Collection<int, ReservationSlot>>
     */
    private function groupConsecutiveSlots(Collection $slots): Collection
    {
        $groups = collect();
        $currentGroup = collect();

        /** @var ReservationSlot $slot */
        foreach ($slots as $slot) {
            /** @var ReservationSlot|null $lastSlot */
            $lastSlot = $currentGroup->last();

            if ($lastSlot === null || $slot->slot_start->equalTo($lastSlot->slot_end)) {
                $currentGroup->push($slot);
                continue;
            }

            $groups->push($currentGroup);
            $currentGroup = collect([$slot]);
        }

        if ($currentGroup->isNotEmpty()) {
            $groups->push($currentGroup);
        }

        return $groups;
    }

    /**
     * @param Collection<int, ReservationSlot> $slots
     */
    private function createReservation(Reservation $reservation, Collection $slots, bool $restore): void
    {
        /** @var ReservationSlot $firstSlot */
        $firstSlot = $slots->first();

        /** @var ReservationSlot $lastSlot */
        $lastSlot = $slots->last();

        $priceWithVat = $slots->sum('price_with_vat');

        $newReservation = $reservation->replicate();

        $newReservation->fill([
            'start_time' => $firstSlot->slot_start,
            'end_time' => $lastSlot->slot_end,
            'price' => $slots->sum('price'),
            'vat' => $slots->sum('vat'),
            'discount' => $slots->sum('discount'),
            'price_with_vat' => $priceWithVat,
            'refunded_amount' => $restore ? 0 : $priceWithVat,
            'canceled_at' => $restore ? null : $reservation->canceled_at,
            'cancellation_reason' => $restore ? null : $reservation->cancellation_reason,
        ]);

        $newReservation->save();

        if ($restore) {
            $slots->each(fn(ReservationSlot $slot) => $slot->update([
                'try_sell' => false,
                'reservation_id' => $newReservation->id,
            ]));

            return;
        }

        $slots->each(fn(ReservationSlot $slot) => $slot->delete());
    }
}
