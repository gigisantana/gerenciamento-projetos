<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Pega o id do usuário na URL
        $user_id = $this->route('user');
        return [
            'name' => 'require',
            'email' => 'require|email|unique:users,email,' . ($user_id ? $user_id->id : null),
            'password' => 'require|min:6'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'erros' => $validator->errors(),
        ], 422)); // Recebeu a requisição, mas houve erro de validação pelo servidor
    }

    // Função para retornar mensagens de erro para o usuário
    public function messages() : array 
    {
        return[
            'name.require' => 'Nome é um campo obrigatório.',
            'email.require' => 'E-mail é um campo obrigatório.',
            'email.email' => 'O e-mail inválido.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'password.require' => 'Senha é um campo obrigatório.',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres.',
        ];
    }
}
