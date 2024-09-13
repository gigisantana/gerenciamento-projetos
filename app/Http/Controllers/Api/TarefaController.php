<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Repositories\ProjetoRepository;
use App\Repositories\TarefaRepository;
use Illuminate\Http\Request;

class TarefaController extends Controller {

    protected $repository;
    private $rules = [
        'nome' => 'required|min:5|max:200|unique:projetos',
        'sigla' => 'required|min:2|max:8',
        'horas' => 'required',
        'eixo_id' => 'required',
        'nivel_id' => 'required',
    ];
    private $messages = [
        "required" => "O preenchimento do campo [:attribute] é obrigatório!",
        "max" => "O campo [:attribute] possui tamanho máximo de [:max] caracteres!",
        "min" => "O campo [:attribute] possui tamanho mínimo de [:min] caracteres!",
        "unique" => "Já existe um projeto cadastrado com esse [:attribute]!",
    ];
   
    public function __construct(){
       $this->repository = new TarefaRepository();
    }

    public function index() {

        $this->authorize('hasFullPermission', Tarefa::class);
        $data = $this->repository->selectAllWith(
            ['eixo', 'nivel'], 
            (object) ["use" => true, "rows" => $this->repository->getRows()]
        );
        return view('projeto.index', compact('data'));
        return $data;
    }

    public function create() {

        $this->authorize('hasFullPermission', Tarefa::class);
        $eixos = (new ProjetoRepository())->selectAll((object) ["use" => false, "rows" => 0]);
        return view('projeto.create', compact(['projeto']));
    }

    public function store(Request $request) {

        $this->authorize('hasFullPermission', Tarefa::class);
        $request->validate($this->rules, $this->messages);
        $objProjeto = (new ProjetoRepository())->findById($request->projeto_id);

        if(isset($objProjeto) && isset($objNivel)) {
            $obj = new Tarefa();
            $obj->nome = mb_strtoupper($request->nome, 'UTF-8');
            $obj->sigla = mb_strtoupper($request->sigla, 'UTF-8');
            $obj->total_horas = $request->horas;
            $obj->eixo()->associate($objProjeto);
            $obj->nivel()->associate($objNivel);
            $this->repository->save($obj);
            return redirect()->route('projeto.index');
        }
        
        return view('message')
                    ->with('template', "main")
                    ->with('type', "danger")
                    ->with('titulo', "OPERAÇÃO INVÁLIDA")
                    ->with('message', "Não foi possível efetuar o procedimento!")
                    ->with('link', "projeto.index");
    }

    public function show(string $id) {
        
        $this->authorize('hasFullPermission', Tarefa::class);
        $data = $this->repository->findByIdWith(['projeto'], $id);
        if(isset($data))
            return view('projeto.show', compact('data'));

        return view('message')
                    ->with('template', "main")
                    ->with('type', "danger")
                    ->with('titulo', "OPERAÇÃO INVÁLIDA")
                    ->with('message', "Não foi possível efetuar o procedimento!")
                    ->with('link', "projeto.index");
    }   

    public function edit(string $id) {

        $this->authorize('hasFullPermission', Tarefa::class);
        $data = $this->repository->findById($id);
        if(isset($data)) {
            $eixos = (new ProjetoRepository())->selectAll((object) ["use" => false, "rows" => 0]);
            return view('projeto.edit', compact(['data', 'tarefas']));
        }

        return view('message')
                    ->with('template', "main")
                    ->with('type', "danger")
                    ->with('titulo', "OPERAÇÃO INVÁLIDA")
                    ->with('message', "Não foi possível efetuar o procedimento!")
                    ->with('link', "projeto.index");
    }

    public function update(Request $request, string $id) {
        
        $this->authorize('hasFullPermission', Tarefa::class);
        $obj = $this->repository->findById($id);
        $objProjeto = (new ProjetoRepository())->findById($request->eixo_id);
        
        if(isset($obj) && isset($objProjeto) && isset($objNivel)) {
            $obj->nome = mb_strtoupper($request->nome, 'UTF-8');
            $obj->sigla = mb_strtoupper($request->sigla, 'UTF-8');
            $obj->total_horas = $request->horas;
            $obj->eixo()->associate($objProjeto);
            $obj->nivel()->associate($objNivel);
            $this->repository->save($obj);
            return redirect()->route('projeto.index');
        }

        return view('message')
            ->with('template', "main")
            ->with('type', "danger")
            ->with('titulo', "OPERAÇÃO INVÁLIDA")
            ->with('message', "Não foi possível efetuar o procedimento!")
            ->with('link', "projeto.index");
    }

    public function destroy(string $id) {
        
        $this->authorize('hasFullPermission', Tarefa::class);
        if($this->repository->delete($id))  {
            return redirect()->route('projeto.index');
        }
        
        return view('message')
                    ->with('template', "main")
                    ->with('type', "danger")
                    ->with('titulo', "OPERAÇÃO INVÁLIDA")
                    ->with('message', "Não foi possível efetuar o procedimento!")
                    ->with('link', "projeto.index");
    }
}