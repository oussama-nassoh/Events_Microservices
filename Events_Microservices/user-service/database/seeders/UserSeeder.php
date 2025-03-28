<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate the table first
        DB::table('users')->truncate();

        $users = [
            [
                'id' => 1,
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'phone_number' => '+1234567890',
                'address' => '123 Admin Street',
                'city' => 'Admin City',
                'country' => 'US',
                'language' => 'en',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Operator User',
                'email' => 'operator@example.com',
                'phone_number' => '+1234567891',
                'address' => '456 Operator Street',
                'city' => 'Operator City',
                'country' => 'US',
                'language' => 'en',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Event Creator',
                'email' => 'eventcreator@example.com',
                'phone_number' => '+1234567892',
                'address' => '789 Creator Street',
                'city' => 'Creator City',
                'country' => 'US',
                'language' => 'en',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'phone_number' => '+1234567893',
                'address' => '321 User Street',
                'city' => 'User City',
                'country' => 'US',
                'language' => 'en',
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
