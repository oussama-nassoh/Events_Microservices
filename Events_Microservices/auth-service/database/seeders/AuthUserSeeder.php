<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuthUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'external_user_id' => 1,
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'external_user_id' => 2,
                'email' => 'operator@example.com',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'external_user_id' => 3,
                'email' => 'eventcreator@example.com',
                'password' => Hash::make('password'),
                'role' => 'event_creator',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'external_user_id' => 4,
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
