<?php

namespace App\Services\Games;

use App\Data\User\Games\GameGuestData;
use App\Data\User\Games\GameJoinData;
use App\Enums\DiscountCodeType;
use App\Enums\GameStatus;
use App\Models\DiscountCode;
use App\Models\Game;
use App\Models\GameParticipant;
use App\Models\User;
use App\Responders\Games\GameJoinPaymentResponder;
use App\Services\DiscountCodeService;
use App\Support\ServiceResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\LaravelData\Optional;

class GameJoinService
{
    public function __construct(
        protected DiscountCodeService      $discountCodeService,
        protected GameJoinPaymentResponder $responder,
    )
    {
    }

    /**
     * Reserves the spots for the user and their guests, then hands them to payment. Spots are held as
     * pending until the payment provider confirms, exactly like an unpaid court reservation holds its
     * court slots.
     *
     * @throws ValidationException|\Throwable
     */
    public function join(Game $game, User $user, GameJoinData $data): ServiceResponse
    {
        $guests = $data->guests instanceof Optional ? collect() : $data->guests;
        $discountCode = $this->discountCode($game, $data);

        $participants = $this->reserveSpots($game, $user, $data, $guests, $discountCode);

        return $this->responder->handle($game, $user, $participants, $discountCode);
    }

    /**
     * @return Collection<int, GameParticipant>
     * @throws \Throwable
     */
    private function reserveSpots(Game $game, User $user, GameJoinData $data, Collection $guests, ?DiscountCode $discountCode): Collection
    {
        return DB::transaction(function () use ($game, $user, $data, $guests, $discountCode) {
            $game = Game::whereKey($game->id)->lockForUpdate()->firstOrFail();

            $this->guardAgainstInvalidJoin($game, $user, $guests);

            $prices = $this->prices($game, $user, $guests->count() + 1, $discountCode);

            $participants = collect();

            $participants->push($this->createParticipant($game, $prices->shift(), [
                'user_id' => $user->id,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'game_level_id' => $this->levelId($game, $data->game_level_id),
            ]));

            foreach ($guests as $guest) {
                $existingUser = User::whereEmail($guest->email)->first();

                $participants->push($this->createParticipant($game, $prices->shift(), [
                    'user_id' => $existingUser?->id,
                    'added_by_user_id' => $user->id,
                    'email' => $guest->email,
                    'first_name' => $guest->first_name instanceof Optional
                        ? $existingUser?->first_name
                        : $guest->first_name,
                    'last_name' => $guest->last_name instanceof Optional
                        ? $existingUser?->last_name
                        : $guest->last_name,
                    'game_level_id' => $this->levelId($game, $guest->game_level_id),
                ]));
            }

            return $participants;
        });
    }

    /**
     * @param Collection<int, GameGuestData> $guests
     *
     * @throws ValidationException
     */
    private function guardAgainstInvalidJoin(Game $game, User $user, Collection $guests): void
    {
        $spots = $guests->count() + 1;

        $participants = $game->activeParticipants()->get();

        $error = match (true) {
            $game->status !== GameStatus::PUBLISHED => __('games.not_available'),
            !$game->start_time->isFuture() => __('games.already_started'),
            $participants->contains('email', $user->email) => __('games.already_joined'),
            $participants->count() + $spots > $game->capacity => __('games.not_enough_spots', [
                'spots' => max(0, $game->capacity - $participants->count()),
            ]),
            default => null,
        };

        if ($error) {
            throw ValidationException::withMessages(['game' => $error]);
        }

        $guestEmails = $guests->pluck('email')->map(fn(string $email) => mb_strtolower($email));

        if ($guestEmails->unique()->count() !== $guestEmails->count() || $guestEmails->contains(mb_strtolower($user->email))) {
            throw ValidationException::withMessages(['guests' => __('games.duplicate_guest')]);
        }

        $taken = $participants->pluck('email')->map(fn(string $email) => mb_strtolower($email));

        if ($guestEmails->intersect($taken)->isNotEmpty()) {
            throw ValidationException::withMessages(['guests' => __('games.guest_already_joined')]);
        }
    }

    /**
     * One price per spot. The payer's personal discount applies to the whole purchase, while a fixed
     * amount discount code is applied once - to the first spot - so the parts still add up exactly.
     *
     * @return Collection<int, \App\Data\Core\Pricing\PriceDetailsData>
     */
    private function prices(Game $game, User $user, int $spots, ?DiscountCode $discountCode): Collection
    {
        $fixedDiscountLeft = $discountCode?->type === DiscountCodeType::FIXED
            ? (float)$discountCode->value
            : 0.0;

        return collect(range(1, $spots))->map(function () use ($game, $user, $discountCode, &$fixedDiscountLeft) {
            $price = (float)$game->price_with_vat;
            $discount = 0.0;

            if ($user->discount_on_everything > 0) {
                $amount = round($price * ($user->discount_on_everything / 100), 2);
                $price -= $amount;
                $discount += $amount;
            }

            if ($discountCode?->type === DiscountCodeType::PERCENT) {
                $amount = round($price * ($discountCode->value / 100), 2);
                $price -= $amount;
                $discount += $amount;
            } elseif ($fixedDiscountLeft > 0) {
                $amount = min($fixedDiscountLeft, $price);
                $price -= $amount;
                $discount += $amount;
                $fixedDiscountLeft -= $amount;
            }

            $details = applyDiscountAndCalculatePriceDetails($price);
            $details->discount = $discount;

            return $details;
        });
    }

    private function createParticipant(Game $game, $price, array $attributes): GameParticipant
    {
        return $game->participants()->create([
            ...$attributes,
            'price' => $price->price,
            'vat' => $price->vat,
            'discount' => $price->discount,
            'price_with_vat' => $price->price_with_vat,
        ]);
    }

    /**
     * @throws ValidationException
     */
    private function discountCode(Game $game, GameJoinData $data): ?DiscountCode
    {
        if ($data->discount_code instanceof Optional || !is_string($data->discount_code)) {
            return null;
        }

        $result = $this->discountCodeService->validateCode($data->discount_code, [$game->court_type_id]);

        if (!$result['valid']) {
            throw ValidationException::withMessages(['discount_code' => $result['message']]);
        }

        return $result['discountCode'];
    }

    /**
     * @throws ValidationException
     */
    private function levelId(Game $game, int|Optional|null $levelId): ?int
    {
        if ($levelId instanceof Optional || $levelId === null) {
            return null;
        }

        if (!$game->courtType->gameLevels()->whereKey($levelId)->exists()) {
            throw ValidationException::withMessages(['game_level_id' => __('games.level_not_for_sport')]);
        }

        return $levelId;
    }
}
