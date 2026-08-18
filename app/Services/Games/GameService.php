<?php

namespace App\Services\Games;

use App\Data\Admin\Games\GameCourtConflictData;
use App\Data\Admin\Games\GameResultData;
use App\Data\Admin\Games\GameStoreData;
use App\Data\Admin\Games\GameUpdateData;
use App\Data\Core\Pricing\PriceDetailsData;
use App\Enums\GameStatus;
use App\Jobs\NotifyUsersAboutNewGames;
use App\Models\Court;
use App\Models\Game;
use App\Models\Reservation;
use App\Services\Reservations\ReservationService;
use App\Services\Slots\SlotService;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\LaravelData\Optional;

class GameService
{
    public function __construct(
        protected GameAvailabilityService $availabilityService,
        protected GameRefundService       $refundService,
        protected SlotService             $slotService,
        protected ReservationService      $reservationService,
    )
    {
    }

    public function create(GameStoreData $data): GameResultData
    {
        return DB::transaction(function () use ($data) {
            $courts = Court::whereIn('id', $data->courts_ids)->get();

            $conflicts = $this->conflicts($courts, $data->date, $data->start_time, $data->end_time);

            if ($conflicts->isNotEmpty()) {
                return GameResultData::blocked($conflicts);
            }

            $price = applyDiscountAndCalculatePriceDetails($data->price_with_vat);

            $games = $courts->map(fn(Court $court) => $this->createForCourt($court, $data, $price));

            NotifyUsersAboutNewGames::dispatch($games->pluck('id')->all());

            return GameResultData::success($games);
        });
    }

    public function update(Game $game, GameUpdateData $data): GameResultData
    {
        return DB::transaction(function () use ($game, $data) {
            if ($game->start_time->isPast()) {
                throw ValidationException::withMessages([
                    'game' => __('games.cannot_edit_past'),
                ]);
            }

            $takenSpots = $game->activeParticipants()->count();

            if ($data->capacity < $takenSpots) {
                throw ValidationException::withMessages([
                    'capacity' => __('validation.games.capacity_below_taken_spots', ['taken' => $takenSpots]),
                ]);
            }

            $court = Court::findOrFail($data->court_id);
            $reservation = $game->reservation;

            $conflicts = $this->conflicts(
                collect([$court]),
                $data->date,
                $data->start_time,
                $data->end_time,
                $reservation?->id,
            );

            if ($conflicts->isNotEmpty()) {
                return GameResultData::blocked($conflicts);
            }

            $price = applyDiscountAndCalculatePriceDetails($data->price_with_vat);

            $game->update([
                'court_type_id' => $data->court_type_id,
                'court_id' => $court->id,
                'start_time' => $this->dateTime($data->date, $data->start_time),
                'end_time' => $this->dateTime($data->date, $data->end_time),
                'capacity' => $data->capacity,
                'price' => $price->price,
                'vat' => $price->vat,
                'price_with_vat' => $price->price_with_vat,
                ...$this->translations($data),
            ]);

            $this->syncPhoto($game, $data->photoFile, $data->deletePhoto);

            $reservation?->slots()->delete();
            $reservation?->delete();

            $this->createReservation($game->refresh(), $court);

            return GameResultData::success(collect([$game->fresh()]));
        });
    }

    public function cancel(Game $game, ?string $reason = null): Game
    {
        return DB::transaction(function () use ($game, $reason) {
            if ($game->status === GameStatus::CANCELED) {
                return $game;
            }

            $this->refundService->refundAll($game, $reason);

            $game->update([
                'status' => GameStatus::CANCELED,
                'canceled_at' => now(),
                'cancellation_reason' => $reason,
            ]);

            $reservation = $game->reservation;
            $reservation?->slots()->delete();
            $reservation?->delete();

            return $game->fresh();
        });
    }

    public function delete(Game $game): void
    {
        DB::transaction(function () use ($game) {
            $this->cancel($game, __('games.canceled_by_admin'));

            $game->delete();
        });
    }

    private function createForCourt(Court $court, GameStoreData $data, PriceDetailsData $price): Game
    {
        $game = Game::create([
            'court_type_id' => $data->court_type_id,
            'court_id' => $court->id,
            'admin_id' => auth('admin')->id(),
            'start_time' => $this->dateTime($data->date, $data->start_time),
            'end_time' => $this->dateTime($data->date, $data->end_time),
            'capacity' => $data->capacity,
            'price' => $price->price,
            'vat' => $price->vat,
            'price_with_vat' => $price->price_with_vat,
            ...$this->translations($data),
        ]);

        $this->syncPhoto($game, $data->photoFile);

        $this->createReservation($game, $court);

        return $game;
    }

    private function createReservation(Game $game, Court $court): Reservation
    {
        $reservation = $game->reservation()->create([
            'court_id' => $court->id,
            'start_time' => $game->start_time,
            'end_time' => $game->end_time,
            'is_paid' => true,
            'paid_at' => now(),
            'delete_after_failed_payment' => false,
        ]);

        foreach ($this->slotService->generate($game->start_time->format('H:i'), $game->end_time->format('H:i')) as $slot) {
            $reservation->slots()->create([
                'court_id' => $court->id,
                'slot_start' => $this->dateTime($game->start_time, $slot['start_time']),
                'slot_end' => $this->dateTime($game->start_time, $slot['end_time']),
            ]);
        }

        $this->reservationService->refundSlots($reservation);

        return $reservation;
    }

    /**
     * @param Collection<int, Court> $courts
     *
     * @return Collection<int, GameCourtConflictData>
     */
    private function conflicts(
        Collection $courts,
        Carbon     $date,
        string     $startTime,
        string     $endTime,
        ?int       $skipReservationId = null,
    ): Collection
    {
        return $courts
            ->map(function (Court $court) use ($date, $startTime, $endTime, $skipReservationId) {
                $slots = $this->availabilityService->conflicts($court, $date, $startTime, $endTime, $skipReservationId);

                return $slots->isEmpty()
                    ? null
                    : new GameCourtConflictData($court->id, $court->name, $date->toDateString(), $slots);
            })
            ->filter()
            ->values();
    }

    private function translations(GameStoreData|GameUpdateData $data): array
    {
        return collect(['title' => $data->title, 'description' => $data->description])
            ->reject(fn($value) => $value instanceof Optional)
            ->all();
    }

    private function syncPhoto(Game $game, UploadedFile|Optional|null $photoFile, mixed $deletePhoto = null): void
    {
        if ($photoFile instanceof UploadedFile) {
            $game->addMedia($photoFile)->preservingOriginal()->toMediaCollection('photo');

            return;
        }

        if ($deletePhoto) {
            $game->clearMediaCollection('photo');
        }
    }

    private function dateTime(Carbon $date, string $time): Carbon
    {
        return $time === '24:00'
            ? $date->copy()->startOfDay()->addDay()
            : $date->copy()->setTimeFromTimeString($time);
    }
}
