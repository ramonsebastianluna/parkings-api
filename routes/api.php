<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\AuthController;


//Autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    //Parkings
    Route::prefix('parkings')->group(function () {
        // endpoint para buscar el parking más cercano a un punto dado
        Route::get('/nearest', [ParkingController::class, 'nearest']);
    
        // endpoints crud parking
        Route::get('/', [ParkingController::class, 'index']);
        Route::post('/', [ParkingController::class, 'store']);
        Route::get('/{id}', [ParkingController::class, 'show']);
        Route::put('/{id}', [ParkingController::class, 'update']);
        Route::delete('/{id}', [ParkingController::class, 'destroy']);
    });
});