<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Car;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {

        $carsData = [

            [
                'brand' => 'Toyota',
                'model' => 'Corolla',
                'images' => [
                    'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb',
                    'https://images.unsplash.com/photo-1553440569-bcc63803a83d',
                    'https://images.unsplash.com/photo-1494976388531-d1058494cdd8',
                ]
            ],

            [
                'brand' => 'Honda',
                'model' => 'Civic',
                'images' => [
                    'https://images.unsplash.com/photo-1502877338535-766e1452684a',
                    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341',
                    'https://images.unsplash.com/photo-1550355291-bbee04a92027',
                ]
            ],

            [
                'brand' => 'BMW',
                'model' => 'X5',
                'images' => [
                    'https://images.unsplash.com/photo-1555215695-3004980ad54e',
                    'https://images.unsplash.com/photo-1503376780353-7e6692767b70',
                    'https://images.unsplash.com/photo-1525609004556-c46c7d6cf023',
                ]
            ],

            [
                'brand' => 'Mercedes',
                'model' => 'Clase C',
                'images' => [
                    'https://images.unsplash.com/photo-1511919884226-fd3cad34687c',
                    'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6',
                    'https://images.unsplash.com/photo-1507136566006-cfc505b114fc',
                ]
            ],

            [
                'brand' => 'Audi',
                'model' => 'A4',
                'images' => [
                    'https://images.unsplash.com/photo-1542282088-fe8426682b8f',
                    'https://images.unsplash.com/photo-1614200187524-dc4b892acf16',
                    'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a',
                ]
            ],

            [
                'brand' => 'Ford',
                'model' => 'Mustang',
                'images' => [
                    'https://images.unsplash.com/photo-1584345604476-8ec5e12e42dd',
                    'https://images.unsplash.com/photo-1494905998402-395d579af36f',
                    'https://images.unsplash.com/photo-1502161254066-6c74afbf07aa',
                ]
            ],

            [
                'brand' => 'Nissan',
                'model' => 'Sentra',
                'images' => [
                    'https://images.unsplash.com/photo-1619767886558-efdc259cde1a',
                    'https://images.unsplash.com/photo-1590362891991-f776e747a588',
                    'https://images.unsplash.com/photo-1544636331-e26879cd4d9b',
                ]
            ],

            [
                'brand' => 'Chevrolet',
                'model' => 'Camaro',
                'images' => [
                    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d',
                    'https://images.unsplash.com/photo-1503736334956-4c8f8e92946d',
                    'https://images.unsplash.com/photo-1493238792000-8113da705763',
                ]
            ],

            [
                'brand' => 'Kia',
                'model' => 'Rio',
                'images' => [
                    'https://images.unsplash.com/photo-1597007030739-6d2e9c2b1b3c',
                    'https://images.unsplash.com/photo-1580273916550-e323be2ae537',
                    'https://images.unsplash.com/photo-1541899481282-d53bffe3c35d',
                ]
            ],

            [
                'brand' => 'Mazda',
                'model' => 'Mazda 3',
                'images' => [
                    'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b',
                    'https://images.unsplash.com/photo-1508974239320-0a029497e820',
                    'https://images.unsplash.com/photo-1485463611174-f302f6a5c1c9',
                ]
            ],
        ];

        $names = [
            'Carlos Mendoza',
            'Luis Hernández',
            'Ana Torres',
            'Fernanda Ruiz',
            'Miguel Castillo'
        ];

        $locations = [
            'San Luis Potosí',
            'Monterrey',
            'Guadalajara',
            'Ciudad de México',
            'Querétaro'
        ];

        $bios = [
            'Venta de autos seminuevos',
            'Agencia automotriz',
            'Autos deportivos y familiares',
            'Especialista en autos premium',
            'Compra y venta de vehículos'
        ];

        $users = [];

        // CREAR 5 USUARIOS
        for ($i = 0; $i < 5; $i++) {

            $users[] = User::create([

                'name' => $names[$i],

                'email' => 'usuario' . ($i + 1) . '@gmail.com',

                'phone' => '44' . rand(10000000, 99999999),

                'password' => Hash::make('12345678'),

                'role' => 'user',

                'verified' => true,

                'totalCars' => 6,

                'location' => $locations[$i],

                'bio' => $bios[$i],

                'curp' => 'CURPTEST' . rand(1000, 9999),

                'rfc' => 'RFCTEST' . rand(1000, 9999),

                'domicile' => $locations[$i],

                'ine' => 'INE TEST',
            ]);
        }

        // CREAR 30 AUTOS
        for ($i = 1; $i <= 30; $i++) {

            $randomCar = $carsData[array_rand($carsData)];

            $randomUser = $users[array_rand($users)];

            Car::create([

                'ai_price' => rand(150000, 800000),

                'user_id' => $randomUser->id,

                'brand' => $randomCar['brand'],

                'model' => $randomCar['model'],

                'year' => rand(2018, 2025),

                'price' => rand(180000, 950000),

                'mileage' => rand(5000, 120000),

                'transmission' =>
                    rand(0, 1)
                        ? 'Automática'
                        : 'Manual',

                'fuelType' => [
                    'Gasolina',
                    'Híbrido',
                    'Diésel'
                ][array_rand([
                    'Gasolina',
                    'Híbrido',
                    'Diésel'
                ])],

                'color' => [
                    'Blanco',
                    'Negro',
                    'Rojo',
                    'Azul',
                    'Gris'
                ][array_rand([
                    'Blanco',
                    'Negro',
                    'Rojo',
                    'Azul',
                    'Gris'
                ])],

                'location' => $randomUser->location,

                'phone' => $randomUser->phone,

                'description' =>
                    'Auto en excelentes condiciones listo para usarse.',

                'images' => $randomCar['images'],
            ]);
        }
    }
}