<?php

namespace App\Repositories;

use App\Models\Court;
use Illuminate\Support\Collection;

class CourtRepository
{
    public function get(?int $courtTypeId = null, mixed $id = null): Collection
    {
        $courts = Court::query();

        if ($courtTypeId) {
            $courts->whereCourtTypeId($courtTypeId);
        }

        if (is_int($id)) {
            $courts->whereId($id);
        }

        return $courts->get();
    }
}
