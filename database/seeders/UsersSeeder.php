<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends BasicSeeder
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
                User::create([
                    'first_name' => $userData['first_name'],
                    'last_name' => $userData['last_name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password'])
                ]);
            }

            $this->saveSeed();
        }
    }
}
