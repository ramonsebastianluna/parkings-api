<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parking;
use Illuminate\Http\Response;

class ParkingController extends Controller
{
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
}