<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Projeto;
use App\Models\Role;
use App\Models\User;
use App\Repositories\ClienteRepository;
use App\Repositories\ProjetoRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller {
    
    protected $repository;
    private $rules = [
        'nome' => 'required|min:10|max:200',
        'cpf' => 'required|min:11|max:11|unique:clientes',
        'email' => 'required|min:8|max:200|unique:clientes',
        'senha' => 'required|min:8|max:20',
    ];
    private $messages = [
        "required" => "O preenchimento do campo [:attribute] é obrigatório!",
        "max" => "O campo [:attribute] possui tamanho máximo de [:max] caracteres!",
        "min" => "O campo [:attribute] possui tamanho mínimo de [:min] caracteres!",
        "unique" => "Já existe um usuário cadastrado com esse [:attribute]!",
    ];

    public function __construct(){
        $this->repository = new ClienteRepository();
    }

    public function index() {

        $this->authorize('hasFullPermission', Cliente::class);
        $data = $this->repository->selectAllByProjetos(Auth::user()->projeto_id);
        return view('cliente.index', compact('data'));
    }

    public function register() {
        
        $projetos = (new ProjetoRepository())->selectAll((object) ["use" => false, "rows" => 0]);
        return view('cliente.register', compact(['projetos']));
    }

    public function storeRegister(Request $request) {
        
        $request->validate($this->rules, $this->messages);

        $objProjeto = (new ProjetoRepository())->findById($request->projeto_id);
        
        if(isset($objProjeto)) {
            $obj = new Cliente();
            $obj->nome = mb_strtoupper($request->nome, 'UTF-8');
            $obj->cpf = $request->cpf;
            $obj->email = mb_strtolower($request->email, 'UTF-8');
            $obj->password = Hash::make($request->senha); 
            $obj->projeto()->associate($objProjeto);
            $this->repository->save($obj);

            return view('message')
                    ->with('template', "site")
                    ->with('type', "success")
                    ->with('titulo', "")
                    ->with('message', "[OK] Registro efetuado com sucesso!")
                    ->with('link', "site");
        }
        
        return view('message')
                    ->with('template', "site")
                    ->with('type', "danger")
                    ->with('titulo', "")
                    ->with('message', "Não foi possível efetuar o registro!")
                    ->with('link', "site");
    }

    public function create() {

        $this->authorize('hasFullPermission', Cliente::class);
        $projetos = (new ProjetoRepository())->selectAll((object) ["use" => false, "rows" => 0]);
        return view('cliente.create', compact(['projetos']));
    }

    public function store(Request $request) {

        $this->authorize('hasFullPermission', Cliente::class);
        $request->validate($this->rules, $this->messages);
        $objProjeto = (new ProjetoRepository())->findById($request->projeto_id);
        $objRole = (new RoleRepository())->findFirstByColumn('nome', 'CLIENTE');
        
        if(isset($objProjeto) && isset($objTurma) && isset($objRole)) {
            // Create User
            $objUser = new User();
            $objUser->name = mb_strtoupper($request->nome, 'UTF-8');
            $objUser->email = $request->email;
            $objUser->password = Hash::make($request->password); 
            $objUser->projeto()->associate($objProjeto);
            $objUser->role()->associate($objRole);
            (new UserRepository())->save($objUser);
            // Create Cliente
            $obj = new Cliente();
            $obj->nome = mb_strtoupper($request->nome, 'UTF-8');
            $obj->cpf = $request->cpf;
            $obj->email = $request->email;
            $obj->password = $objUser->password; 
            $obj->projeto()->associate($objProjeto);
            $obj->user()->associate($objUser);
            $this->repository->save($obj);
            return redirect()->route('cliente.index');
        }
        
        return view('message')
                ->with('template', "main")
                ->with('type', "danger")
                ->with('titulo', "OPERAÇÃO INVÁLIDA")
                ->with('message', "Não foi possível efetuar o procedimento!")
                ->with('link', "cliente.index");
    }

    public function show(string $id) {

        $this->authorize('hasFullPermission', Cliente::class);
        $data = $this->repository->findByIdWith(['projeto',], $id);

        if(isset($data)) {
            return view('cliente.show', compact('data'));
        }

        return view('message')
            ->with('template', "main")
            ->with('type', "danger")
            ->with('titulo', "OPERAÇÃO INVÁLIDA")
            ->with('message', "Não foi possível efetuar o procedimento!")
            ->with('link', "cliente.index");
        
    }

    public function edit(string $id) {

        $this->authorize('hasFullPermission', Cliente::class);
        $projetos = (new ProjetoRepository())->selectAll((object) ["use" => false, "rows" => 0]);
        $data = $this->repository->findById($id);

        if(isset($projetos) && isset($data)) {
            return view('cliente.edit', compact(['data', 'projetos']));
        }
        
        return view('message')
            ->with('template', "main")
            ->with('type', "danger")
            ->with('titulo', "OPERAÇÃO INVÁLIDA")
            ->with('message', "Não foi possível efetuar o procedimento!")
            ->with('link', "cliente.index");
    }

    public function update(Request $request, string $id) {
        
        $this->authorize('hasFullPermission', Cliente::class);
        $obj = $this->repository->findById($id);
        $objProjeto = (new ProjetoRepository())->findById($request->projeto_id);
        
        if(isset($obj) && isset($objProjeto)) {
            $obj->nome = mb_strtoupper($request->nome, 'UTF-8');
            $obj->cpf = $request->cpf;
            $obj->projeto()->associate($objProjeto);
            $this->repository->save($obj);
            return redirect()->route('cliente.index');
        }
        
        return view('message')
            ->with('template', "main")
            ->with('type', "danger")
            ->with('titulo', "OPERAÇÃO INVÁLIDA")
            ->with('message', "Não foi possível efetuar o procedimento!")
            ->with('link', "cliente.index");
    }

    public function destroy(string $id){
        
        $this->authorize('hasFullPermission', Cliente::class);
        if($this->repository->delete($id))  {
            return redirect()->route('cliente.index');;
        }
        
        return view('message')
            ->with('template', "main")
            ->with('type', "danger")
            ->with('titulo', "OPERAÇÃO INVÁLIDA")
            ->with('message', "Não foi possível efetuar o procedimento!")
            ->with('link', "cliente.index");
    }


    public function getClientsByProject($value) {
        $data = $this->repository->findByColumn(
            'projeto_id', 
            $value, 
            (object) ["use" => true, "rows" => $this->repository->getRows()]
        );
        return json_encode($data);
    }

    public function listNewRegisters() {
        
        $this->authorize('hasValidateRegisterPermission', Cliente::class);
        $data = $this->repository->selectAllAdapted(
            false, 
            Auth::user()->projeto_id, 
            ['projeto'],
            true
        );
        return view('cliente.validate', compact('data'));
    }

    public function validateNewRegisters(Request $request, $id) {
        
        $this->authorize('hasValidateRegisterPermission', Cliente::class);
        $cliente = $this->repository->findById($id);

        if(isset($cliente)) {

            $response = $request->input('status_'.$id);
            $role_id = Role::getRoleId("CLIENTE");
            
            // Accept - create and bind user
            if($response == 1) {
                // Create
                $user = new User();
                $user->name = $cliente->nome;
                $user->email = $cliente->email;
                $user->password = $cliente->password; 
                $user->projeto()->associate((new ProjetoRepository())->findById($cliente->projeto_id));
                $user->role()->associate((new RoleRepository())->findById($role_id));
                (new UserRepository())->save($user);
                // Bind
                $cliente->user()->associate($user);
                $this->repository->save($cliente);
            }
            // Refuse - remove register
            else {
                $this->destroy($id);
            }

            return redirect()->route('validate.list');
        }

        return view('message')
            ->with('template', "main")
            ->with('type', "danger")
            ->with('titulo', "OPERAÇÃO INVÁLIDA")
            ->with('message', "Não foi possível efetuar o procedimento!")
            ->with('link', "validate.list");        
    }
}