<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{   // Função para validação de login
    public function login(Request $request) : JsonResponse {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $token = $request->user()->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'user' => $user,
                'token' => $token,
            ], 201);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Senha ou e-mail inválidos.'
            ], 404);
        }
    }
}
