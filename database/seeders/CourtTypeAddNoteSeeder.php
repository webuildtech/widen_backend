<?php

namespace Database\Seeders;

use App\Models\CourtType;

class CourtTypeAddNoteSeeder extends BasicSeeder
{
    public function run(): void
    {
        if ($this->isNotSeeded()) {
            $courtType = CourtType::whereName('Tenisas | Laukas')->first();

            $courtType->update([
                'note' => 'Jeigu dėl blogų oro sąlygų lauko aikštelės rezervacija negalės įvykti, administracija grąžins sumokėtą rezervacijos mokestį.'
            ]);

            $this->saveSeed();
        }
    }
}
