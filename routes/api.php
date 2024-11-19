<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PolicyController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/policies', [PolicyController::class, 'index']);
// Route::middleware('auth:sanctum')->get('/policies', [PolicyController::class, 'index']);
