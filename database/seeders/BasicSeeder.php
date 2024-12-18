<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class BasicSeeder extends Seeder
{
    private string $seedName;

    public function __construct()
    {
        $this->seedName = get_class($this);
    }

    protected function saveSeed()
    {
        DB::table('seeds')->insert(
            [
                'id' => Str::uuid(),
                'seed' => $this->seedName,
                'batch' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );
    }

    protected function isNotSeeded(): bool
    {
        return DB::table('seeds')->where('seed', $this->seedName)->doesntExist();
    }
}
