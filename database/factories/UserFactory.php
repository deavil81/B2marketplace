<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'role' => 'user',
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // default password
            'remember_token' => Str::random(10),
            'profile_picture' => null,
            'about_us' => $this->faker->text(200),
            'business_type' => $this->faker->randomElement(['Retail', 'Wholesale', 'Manufacturing']),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
        ];
    }
}
