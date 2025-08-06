<?php

namespace App\Services;

use App\Models\Parking;
use App\Models\NotificationDistance;

class NearestParkingService
{
    /**
     * Busca el parking más cercano a las coordenadas proporcionadas utilizando la fórmula de Haversine.
     * 
     * Si la distancia al parking más cercano es mayor a 0.5 km, registra una notificación en la tabla
     * notification_distances con las coordenadas recibidas.
     * 
     * @param float $latitude  Latitud de la ubicación de búsqueda.
     * @param float $longitude Longitud de la ubicación de búsqueda.
     * @return Parking|null    El parking más cercano o null si no existe ninguno.
     */
    public function findNearest(float $latitude, float $longitude): ?Parking
    {
        $haversine = "(6371 * acos(cos(radians($latitude)) 
                        * cos(radians(latitud)) 
                        * cos(radians(longitud) - radians($longitude)) 
                        + sin(radians($latitude)) 
                        * sin(radians(latitud))))";

        $parking = Parking::select('*')
                        ->selectRaw("$haversine AS distance")
                        ->orderBy('distance', 'asc')
                        ->first();

        if ($parking && $parking->distance > 0.5) {
            NotificationDistance::create([
                'latitud' => $latitude,
                'longitud' => $longitude,
            ]);
        }

        return $parking;
    }
}