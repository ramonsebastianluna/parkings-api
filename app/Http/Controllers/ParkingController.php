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
    
    /**
     * @OA\Get(
     *     path="/parkings",
     *     summary="Obtener el listado de todos los parkings",
     *     tags={"Parkings"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de parkings obtenido correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Listado de parkings obtenido correctamente"),
     *             @OA\Property(
     *                 property="parkings",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Parking Central"),
     *                     @OA\Property(property="direccion", type="string", example="Calle Falsa 123"),
     *                     @OA\Property(property="latitud", type="number", format="float", example=40.416775),
     *                     @OA\Property(property="longitud", type="number", format="float", example=-3.703790),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-01T12:00:00.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-01T12:00:00.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function index()
    {
        $parkings = Parking::all();

        return response()->json([
            'message' => 'Listado de parkings obtenido correctamente',
            'parkings' => $parkings,
        ], Response::HTTP_OK);
    }

    
    /**
     * @OA\Post(
     *     path="/parkings",
     *     summary="Crear un nuevo parking",
     *     tags={"Parkings"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","direccion","latitud","longitud"},
     *             @OA\Property(property="nombre", type="string", example="Parking Central"),
     *             @OA\Property(property="direccion", type="string", example="Calle Falsa 123"),
     *             @OA\Property(property="latitud", type="number", format="float", example=40.416775),
     *             @OA\Property(property="longitud", type="number", format="float", example=-3.703790)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Parking creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Parking creado exitosamente"),
     *             @OA\Property(
     *                 property="parking",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Parking Central"),
     *                 @OA\Property(property="direccion", type="string", example="Calle Falsa 123"),
     *                 @OA\Property(property="latitud", type="number", format="float", example=40.416775),
     *                 @OA\Property(property="longitud", type="number", format="float", example=-3.703790),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-01T12:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-01T12:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos de entrada inválidos"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/parkings/{id}",
     *     summary="Obtener información de un parking específico",
     *     tags={"Parkings"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del parking a obtener",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Parking obtenido correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Parking obtenido correctamente"),
     *             @OA\Property(
     *                 property="parking",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Parking Central"),
     *                 @OA\Property(property="direccion", type="string", example="Calle Falsa 123"),
     *                 @OA\Property(property="latitud", type="number", format="float", example=40.416775),
     *                 @OA\Property(property="longitud", type="number", format="float", example=-3.703790),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-01T12:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-01T12:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Parking no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Parking no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/parkings/{id}",
     *     summary="Actualizar un parking existente",
     *     tags={"Parkings"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del parking a actualizar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", maxLength=255, example="Parking Central Actualizado"),
     *             @OA\Property(property="direccion", type="string", maxLength=255, example="Calle Nueva 456"),
     *             @OA\Property(property="latitud", type="number", format="float", example=40.416775),
     *             @OA\Property(property="longitud", type="number", format="float", example=-3.703790)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Parking actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Parking actualizado exitosamente"),
     *             @OA\Property(
     *                 property="parking",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Parking Central Actualizado"),
     *                 @OA\Property(property="direccion", type="string", example="Calle Nueva 456"),
     *                 @OA\Property(property="latitud", type="number", format="float", example=40.416775),
     *                 @OA\Property(property="longitud", type="number", format="float", example=-3.703790),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-01T12:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-01T12:10:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Parking no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Parking no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos de entrada inválidos"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/parkings/{id}",
     *     summary="Eliminar un parking",
     *     description="Elimina un parking existente por su ID.",
     *     tags={"Parkings"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del parking a eliminar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Parking eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Parking eliminado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Parking no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Parking no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
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
     * @OA\Get(
     *     path="/parkings/nearest",
     *     summary="Obtener el parking más cercano a una ubicación",
     *     description="Devuelve el parking más cercano a las coordenadas proporcionadas (latitud y longitud). Si la distancia es mayor a 500 metros, se incluye una advertencia.",
     *     tags={"Parkings"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="latitud",
     *         in="query",
     *         required=true,
     *         description="Latitud del punto de búsqueda",
     *         @OA\Schema(type="number", format="float", example=40.416775)
     *     ),
     *     @OA\Parameter(
     *         name="longitud",
     *         in="query",
     *         required=true,
     *         description="Longitud del punto de búsqueda",
     *         @OA\Schema(type="number", format="float", example=-3.703790)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Parking más cercano obtenido correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Parking más cercano obtenido correctamente"),
     *             @OA\Property(
     *                 property="parking",
     *                 type="object",
     *                 description="Datos del parking más cercano",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Parking Central"),
     *                 @OA\Property(property="latitud", type="number", format="float", example=40.416775),
     *                 @OA\Property(property="longitud", type="number", format="float", example=-3.703790),
     *                 @OA\Property(property="distance", type="number", format="float", example=0.3, description="Distancia en kilómetros")
     *             ),
     *             @OA\Property(
     *                 property="warning",
     *                 type="string",
     *                 example="Distancia superior a 500 metros, ver registro en la base de datos"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron parkings cercanos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No se encontraron parkings cercanos")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
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