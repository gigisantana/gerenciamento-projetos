<?php 

namespace App\Repositories;

use App\Models\Tarefa;

class TarefaRepository extends Repository { 

    protected $rows = 6;

    public function __construct() {
        parent::__construct(new Tarefa());
    }   

    public function getRows() { return $this->rows; }
}