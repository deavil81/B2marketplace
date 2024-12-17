<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test_' . Str::random(5) . '@example.com',
            'role' => 'user',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'profile_picture' => null,
            'about_us' => 'This is a test user.',
            'business_type' => 'Retail',
            'phone' => '1234567890',
            'address' => '123 Test St, Test City, TS 12345',
        ]);
    }
}
