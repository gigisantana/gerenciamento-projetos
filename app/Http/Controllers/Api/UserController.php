<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    // Apresenta os usuários inseridos no BD, ordenados pelo id em sentido crescente e paginados em 4 usuários cada
    public function index() : JsonResponse {
        $users = User::orderBy('id', 'asc')->paginate(4);

        // Retorna os usuários em formato JSON
        return response()->json([
            'status' => true,
            'users' => $users,
        ],200);
    }

    // Apresenta o usuário específico, com todos os dados
    public function show(User $user) : JsonResponse {
        return response()->json([
            'status' => true,
            'users' => $user,
        ],200);
    }

    // Cria um novo usuário com os dados fornecidos
    public function store(UserRequest $request) {
        // Inicia os registros na tabela
        DB::beginTransaction();  
        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);

            // Registra os dados na tabela
            DB::commit();

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => "Usuário cadastrado!",
            ], 201);

        } catch (Exception $e){
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => "Usuário não cadastrado.",
            ]);
        }
        
        $user = new User();
    }
}
