<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parking;
use Illuminate\Http\Response;
use App\Services\NearestParkingService;

class ParkingController extends Controller
{
    protected $nearestParkingService;

    public function __construct(NearestParkingService $nearestParkingService)
    {
        $this->nearestParkingService = $nearestParkingService;
    }

    public function index()
    {
        $parkings = Parking::all();

        return response()->json([
            'message' => 'Listado de parkings obtenido correctamente',
            'parkings' => $parkings,
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
        ]);

        $parking = Parking::create($validated);

        return response()->json([
            'message' => 'Parking creado exitosamente',
            'parking' => $parking,
        ], Response::HTTP_CREATED);
    }

    public function show(string $id)
    {
        $parking = Parking::find($id);

        if (!$parking) {
            return response()->json([
                'message' => 'Parking no encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'message' => 'Parking obtenido correctamente',
            'parking' => $parking,
        ], Response::HTTP_OK);
    }

    public function update(Request $request, string $id)
    {
        $parking = Parking::find($id);

        if (!$parking) {
            return response()->json([
                'message' => 'Parking no encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'nombre' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
        ]);

        $parking->update($validated);

        return response()->json([
            'message' => 'Parking actualizado exitosamente',
            'parking' => $parking,
        ], Response::HTTP_OK);
    }

    public function destroy(string $id)
    {
        $parking = Parking::find($id);

        if (!$parking) {
            return response()->json([
                'message' => 'Parking no encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        $parking->delete();

        return response()->json([
            'message' => 'Parking eliminado exitosamente',
        ], Response::HTTP_NO_CONTENT);
    }

    /**
     * Busca el parking más cercano a las coordenadas proporcionadas.
     *
     * Valida que se reciban los parámetros 'latitud' y 'longitud' en el request.
     * Utiliza el servicio NearestParkingService para encontrar el parking más cercano a dichas coordenadas.
     * Si no se encuentra ningún parking cercano, retorna un mensaje de error.
     * Si se encuentra, retorna el parking más cercano y un mensaje de éxito.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function nearest(Request $request)
    {
        $validated = $request->validate([
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
        ]);

        $nearestParking = $this->nearestParkingService->findNearest($validated['latitud'], $validated['longitud']);

        if (!$nearestParking) {
            return response()->json([
                'message' => 'No se encontraron parkings cercanos',
            ], 404);
        }

        $response = [
            'message' => 'Parking más cercano obtenido correctamente',
            'parking' => $nearestParking,
        ];

        // Si la distancia es mayor a 500m (0.5km), agregamos el warning
        if ($nearestParking->distance > 0.5) {
            $response['warning'] = 'Distancia superior a 500 metros, ver registro en la base de datos';
        }

        return response()->json($response);
    }
}