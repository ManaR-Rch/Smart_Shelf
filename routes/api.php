<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RayonController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VenteController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

   
    Route::get('/user', fn(Request $request) => $request->user());

  
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('rayons', RayonController::class);
        Route::get('/getStats', [VenteController::class, 'getStats']);
        Route::get('/getAlert', [ProductController::class, 'getAlert']);
    });

   
    Route::prefix('products')->group(function () {
        Route::get('/search', [ProductController::class, 'search'])->middleware('role:client');
        Route::apiResource('/', ProductController::class);
    });

  
    Route::get('/rayons/{rayonId}/products', [ProductController::class, 'getProductsInRayon'])
        ->middleware('role:client');


    Route::post('/logout', [AuthController::class, 'logout']);
});