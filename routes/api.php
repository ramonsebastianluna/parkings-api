<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParkingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('parkings')->group(function () {
    Route::get('/', [ParkingController::class, 'index']);
    Route::post('/', [ParkingController::class, 'store']);
    Route::get('/{id}', [ParkingController::class, 'show']);
    Route::put('/{id}', [ParkingController::class, 'update']);
    Route::delete('/{id}', [ParkingController::class, 'destroy']);
});