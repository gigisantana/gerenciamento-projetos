<?php 

namespace App\Repositories;

use App\Models\Projeto;

class ProjetoRepository extends Repository { 

    protected $rows = 6;

    public function __construct() {
        parent::__construct(new Projeto());
    }   

    public function getRows() { return $this->rows; }
}