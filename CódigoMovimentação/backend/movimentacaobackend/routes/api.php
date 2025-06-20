<?php

use App\Http\Controllers\MovementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;

Route::post('usuarios', [UsuarioController::class, 'store']);
Route::get('usuarios', [UsuarioController::class, 'index']);
Route::post('usuarios/login', [UsuarioController::class, 'login']);
Route::get('usuarios/login', [UsuarioController::class, 'index']);
Route::middleware('auth:sanctum')->get('usuario/autenticado', function (Request $request) {
    return response()->json($request->user());
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('movements', [MovementController::class, 'saveMovement']);
    Route::get('movements', [MovementController::class, 'getUserMovements']);
    Route::put('movements/{id}', [MovementController::class, 'updateMovement']);
    Route::delete('movements/{id}', [MovementController::class, 'deleteMovement']);
    Route::post('verifypassword', [UsuarioController::class, 'verifyPassword']);
    Route::post('updatepassword', [UsuarioController::class, 'updatePassword']);
});
