<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Método: LISTAR USUÁRIOS
Route::get('/users', [UserController::class, 'index']);
// Método: VISUALIZAR USUÁRIO
Route::get('/users/{user}', [UserController::class, 'show']);
// Método: CADASTRAR USUÁRIO
Route::post('/users', [UserController::class, 'store']);