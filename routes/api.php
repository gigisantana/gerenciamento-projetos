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
// Método: ATUALIZAR USUÁRIO
Route::put('/users/{user}', [UserController::class, 'update']);
// Método: DELETAR USUÁRIO
Route::delete('/users/{user}', [UserController::class, 'destroy']);