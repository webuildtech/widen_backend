<?php

namespace Database\Seeders;

use App\Models\CourtType;

class CourtTypesSeeder extends BasicSeeder
{
    public function run(): void
    {
        if ($this->isNotSeeded()) {
            $courtTypes = [
                ['name' => 'Tenisas | Vidus', 'sort_order' => 1],
                ['name' => 'Tenisas | Laukas', 'sort_order' => 2],
                ['name' => 'Badmintonas', 'sort_order' => 3],
                ['name' => 'Stalo tenisas', 'sort_order' => 4],
            ];

            foreach ($courtTypes as $courtType) {
                CourtType::firstOrCreate(
                    ['name' => $courtType['name']],
                    ['sort_order' => $courtType['sort_order']]
                );
            }

            $this->saveSeed();
        }
    }
}
