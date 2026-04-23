<?php

namespace Database\Seeders;

use App\Models\Advertisement;

class AdvertisementsSeeder extends BasicSeeder
{
    public function run(): void
    {
        if ($this->isNotSeeded()) {
            Advertisement::create();

            $this->saveSeed();
        }
    }
}
