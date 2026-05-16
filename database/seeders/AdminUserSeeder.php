<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@automarket.com'
            ],
            [
                'name' => 'Administrador',
                'phone' => '4441234567',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'verified' => true,
                'totalCars' => 0,
                'location' => 'San Luis Potosí',
                'bio' => 'Administrador del sistema',
                'api_token' => Str::random(60),
            ]
        );
    }
}