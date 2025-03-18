<?php

namespace Database\Seeders;

use App\Enums\FeatureType;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;
use LucasDotVin\Soulbscription\Models\Feature;

class FeaturesSeeder extends BasicSeeder
{
    public function run(): void
    {
        if ($this->isNotSeeded()) {
            $features = [
                [
                    'name' => FeatureType::RESERVATION_PER_WEEK->value,
                    'consumable' => true,
                    'periodicity_type' => PeriodicityType::Week,
                    'periodicity' => 1,
                ],
            ];

            foreach ($features as $feature) {
                Feature::create($feature);
            }

            $this->saveSeed();
        }
    }
}
