<?php

namespace Database\Seeders;

use App\Models\CourtType;

class CourtTypesSeeder extends BasicSeeder
{
    public function run(): void
    {
        if ($this->isNotSeeded()) {
            $courtTypes = [
                ['name' => 'Tenisas'],
                ['name' => 'Stalo tenisas'],

            ];

            foreach ($courtTypes as $courtType) {
                CourtType::create($courtType);
            }

            $this->saveSeed();
        }
    }
}
