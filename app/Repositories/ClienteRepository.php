<?php 

namespace App\Repositories;

use App\Models\Projeto;
use App\Models\Cliente;

class ClienteRepository extends Repository { 

    protected $rows = 2;

    public function __construct() {
        parent::__construct(new Cliente());
    }   

    public function getRows() { return $this->rows; }

    public function selectAllAdapted($flag, $projeto_id, $orm, $paginate) {

        // user_id -> NULL
        if($flag) $data = Cliente::whereNotNull('user_id');
        else $data = Cliente::whereNull('user_id');
        // ORM
        if(count($orm) > 0) $data->with($orm);        

        if($paginate)
            return $data->where('projeto_id', $projeto_id)->paginate($this->rows);

        return $data->where('projeto_id', $projeto_id)->get();
    }

    public function selectAllByProjetos($projeto_id) {

        $projetos = (new ProjetoRepository())->findByColumnWith(
            'projeto_id', 
            $projeto_id, ['projeto'],
            (object) ["use" => false, "rows" => 0]
        );
        $projetos = Projeto::with(['projeto'])->where('projeto_id', $projeto_id)->get();

        $data = collect();
        $cont = 0;

        foreach($projetos as $projeto) {
            $data[$cont] = [
                "id" => $projeto->id,
                "projeto" => $projeto->projeto->sigla.$projeto,
                "clientes" => $this->findByColumn(
                        'projeto_id', 
                        $projeto->id, 
                        (object) ["use" => true, "rows" => $this->rows]
                ) 
            ];
            $cont++;
        }
        return $data; 
    }
}