<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('site');

Route::get('/home', function () {
    return view('home');
})->name('home')->middleware(['auth']);

// Registro de Clientes - Site (Visitante)
Route::get('/site/register', 'App\Http\Controllers\ClienteController@register')->name('site.register');
Route::post('/site/success', 'App\Http\Controllers\ClienteController@storeRegister')->name('site.submit');

Route::middleware('auth')->group(function () {
    // CRUDs
    Route::resource('/cliente', 'App\Http\Controllers\Api\ClienteController');
    Route::resource('/tarefa', 'App\Http\Controllers\Api\TarefaController');
    Route::resource('/projeto', 'App\Http\Controllers\Api\ProjetoController');
    Route::resource('/permission', 'App\Http\Controllers\Api\PermissionController');
    Route::resource('/usuario', 'App\Http\Controllers\Api\UserController');
    // Inserção de Admin
    Route::get('/users/{role}', 'App\Http\Controllers\UserController@getUsersByRole')->name('users.role');
    Route::get('/users/create/{role_id}', 'App\Http\Controllers\UserController@createUsersByRole')->name('users.role.create');
    // Validação dos Cadastros de Novos Alunos
    Route::get('/validate', 'App\Http\Controllers\AlunoController@listNewRegisters')->name('validate.list');
    Route::post('/validate/{aluno_id}', 'App\Http\Controllers\AlunoController@validateNewRegisters')->name('validate.finish');
});