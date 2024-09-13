<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Repositories\ProjetoRepository;
use Illuminate\Http\Request;

class ProjetoController extends Controller {
    
    protected $repository;
    private $rules = [
        'nome' => 'required|min:5|max:200|unique:projetos',
    ];
    private $messages = [
        "required" => "O preenchimento do campo [:attribute] é obrigatório!",
        "max" => "O campo [:attribute] possui tamanho máximo de [:max] caracteres!",
        "min" => "O campo [:attribute] possui tamanho mínimo de [:min] caracteres!",
        "unique" => "Já existe um projeto cadastrado com esse [:attribute]!",
    ];
   
    public function __construct(){
       $this->repository = new ProjetoRepository();
    }

    public function index() {

        $this->authorize('hasFullPermission', Projeto::class);
        $data = $this->repository->selectAllWith(
            ['curso'],
            (object) ["use" => true, "rows" => $this->repository->getRows()]
        );
        return view('projeto.index')->with('data', $data);
        return $data;
    }

    public function create() {

        $this->authorize('hasFullPermission', Projeto::class);
        return view('projeto.create');
    }

    public function store(Request $request) {

        $this->authorize('hasFullPermission', Projeto::class);
        $request->validate($this->rules, $this->messages);
        $obj = new Projeto();
        $obj->nome = mb_strtoupper($request->nome, 'UTF-8');
        $this->repository->save($obj);
        return redirect()->route('projeto.index');
    }

    public function show(string $id) {

        $this->authorize('hasFullPermission', Projeto::class);
        $data = $this->repository->findByIdWith(['curso'], $id);
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

        $this->authorize('hasFullPermission', Projeto::class);
        $data = $this->repository->findById($id);
        if(isset($data))
            return view('projeto.edit', compact('data'));

        return view('message')
            ->with('template', "main")
            ->with('type', "danger")
            ->with('titulo', "OPERAÇÃO INVÁLIDA")
            ->with('message', "Não foi possível efetuar o procedimento!")
            ->with('link', "projeto.index");
    }

    public function update(Request $request, string $id) {

        $this->authorize('hasFullPermission', Projeto::class);
        $obj = $this->repository->findById($id);
        if(isset($obj)) {
            $obj->nome = mb_strtoupper($request->nome, 'UTF-8');
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

        $this->authorize('hasFullPermission', Projeto::class);
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