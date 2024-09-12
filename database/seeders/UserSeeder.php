<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Teste de usuários no BD
        if(!User::where('email', 'teste@teste.com')->first()){
            $superAdmin = User::create([
                'name' => 'Admin',
                'email' => 'teste@teste.com',
                'password' => Hash::make('1234567a', ['rounds' => 12]),
            ]);
        }
    }
}
