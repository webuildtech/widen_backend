<?php

namespace App\Data\Admin\Reservations;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class MultiReservationResultData extends Data
{
    public function __construct(
        public Collection $free_slots,
        public Collection $blocked_slots,
    ) {}

    public static function success(Collection $freeSlots): self
    {
        return new self($freeSlots, collect());
    }

    public static function blocked(Collection $blockedSlots): self
    {
        return new self(collect(), $blockedSlots);
    }

    public function hasBlockedSlots(): bool
    {
        return $this->blocked_slots->isNotEmpty();
    }

    public function freeSlots(): Collection
    {
        return $this->free_slots;
    }

    public function blockedSlots(): Collection
    {
        return $this->blocked_slots;
    }
}
