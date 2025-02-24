<?php

namespace Database\Seeders;

use App\Enums\AdminRole;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminsSeeder extends BasicSeeder
{
    public function run(): void
    {
        if ($this->isNotSeeded()) {
            $users = [
                [
                    'first_name' => 'Admin',
                    'last_name' => 'Admin',
                    "email" => "admin@admin.admin",
                    "password" => "password"
                ],
            ];

            foreach ($users as $userData) {
                $createdUser = Admin::create([
                    'first_name' => $userData['first_name'],
                    'last_name' => $userData['last_name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password'])
                ]);

                $createdUser->assignRole(AdminRole::SUPER_ADMIN);
            }

            $this->saveSeed();
        }
    }
}
