<?php

use App\Http\Controllers\DonutApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/donuts')->group(function () {
    Route::get('/', [DonutApiController::class, 'index']);
    Route::post('/', [DonutApiController::class, 'store']);
    Route::delete('/{id}', [DonutApiController::class, 'destroy']);
});