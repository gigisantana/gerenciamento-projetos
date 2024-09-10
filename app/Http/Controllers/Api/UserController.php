<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() : JsonResponse {
        // Apresenta os usuários inseridos no BD, ordenados pelo id em sentido crescente e paginados em 4 usuários cada
        $users = User::orderBy('id', 'asc')->paginate(4);

        // Retorna os usuários em formato JSON
        return response()->json([
            'status' => true,
            'users' => $users,
        ],200);
    }
}
