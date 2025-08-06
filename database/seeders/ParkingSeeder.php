<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Parking;

class ParkingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Parking::updateOrCreate(
            ['nombre' => 'Parking Centro'],
            [
                'direccion' => 'Calle Principal 123',
                'latitud' => -34.6037,
                'longitud' => -58.3816,
            ]
        );

        Parking::updateOrCreate(
            ['nombre' => 'Parking Norte'],
            [
                'direccion' => 'Avenida Norte 456',
                'latitud' => -34.5900,
                'longitud' => -58.4100,
            ]
        );
    }
}
