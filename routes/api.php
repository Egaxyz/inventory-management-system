<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SupplierControllerr;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::get('/user', [UserController::class, 'index']);

Route::get('/type', [TypeController::class, 'index']);
Route::post('/type', [TypeController::class, 'store']);
Route::get('/type/{id}', [TypeController::class, 'show']);
Route::put('/type/{id}', [TypeController::class, 'update']);
Route::delete('/type/{id}', [TypeController::class, 'destroy']);

Route::get('/supplier', [SupplierControllerr::class, 'index']);
Route::post('/supplier', [SupplierControllerr::class, 'store']);
Route::get('/supplier/{id}', [SupplierControllerr::class, 'show']);
Route::put('/supplier/{id}', [SupplierControllerr::class, 'update']);
Route::delete('/supplier/{id}', [SupplierControllerr::class, 'destroy']);