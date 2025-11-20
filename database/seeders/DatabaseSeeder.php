<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(AdminsSeeder::class);
        $this->call(CourtTypesSeeder::class);
        $this->call(DefaultPlanSeeder::class);
        $this->call(PlansFeaturesSeeder::class);
    }
}
