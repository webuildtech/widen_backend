<?php

namespace App\Repositories;

use App\Enums\CourtType;
use App\Models\Court;
use Illuminate\Support\Collection;

class CourtRepository
{
    public function get(?CourtType $type = null, mixed $id = null): Collection
    {
        $courts = Court::query();

        if ($type) {
            $courts->whereType($type->value);
        }

        if (is_int($id)) {
            $courts->whereId($id);
        }

        return $courts->get();
    }
}
