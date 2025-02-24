<?php

namespace Database\Seeders;

use App\Enums\AdminRole;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends BasicSeeder
{
    public function run(): void
    {
        if ($this->isNotSeeded()) {
            foreach (AdminRole::cases() as $roleEnum) {
                Role::create(['name' => $roleEnum->value, 'guard_name' => 'admin']);
            }

            $this->saveSeed();
        }
    }
}
