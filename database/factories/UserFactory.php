<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'password' => Hash::make('password123'),
            'role' => 'user',
            'verified' => false,
            'totalCars' => 0,
            'location' => fake()->city(),
            'api_token' => bin2hex(random_bytes(32)),
        ];
    }
}
