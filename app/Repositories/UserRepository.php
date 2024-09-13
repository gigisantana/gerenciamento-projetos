<?php 

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Repository { 

    protected $rows = 6;

    public function __construct() {
        parent::__construct(new User());
    }  
    
    public function getRows() { return $this->rows; }

    public function findUserByRoleAndCourse($role_id, $atividade_id) {
        return User::with(['atividade'])->where('role_id', $role_id)
                    ->where('atividade_id', $atividade_id)->first();
    }
}