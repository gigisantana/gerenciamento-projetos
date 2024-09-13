<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Repositories\ProjetoRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    protected $repository;
    private $rules = [
        'nome' => 'required|min:10|max:200',
        'email' => 'required|min:8|max:200|unique:users',
        'senha' => 'required|min:8|max:20',
    ];
    private $messages = [
        "required" => "O preenchimento do campo [:attribute] é obrigatório!",
        "max" => "O campo [:attribute] possui tamanho máximo de [:max] caracteres!",
        "min" => "O campo [:attribute] possui tamanho mínimo de [:min] caracteres!",
        "unique" => "Já existe um usuário cadastrado com esse [:attribute]!",
    ];

    public function __construct(){
        $this->repository = new UserRepository();
    }

    public function index() {
        $data = $this->repository->selectAllWith(
            ['role'],
            (object) ["use" => true, "rows" => $this->repository->getRows()]
        );
        return $data;
    }

    public function store(Request $request) {
        
        $this->authorize('hasFullPermission', Auth::user());
        $request->validate($this->rules, $this->messages);
        $objRole = (new RoleRepository())->findById($request->role_id);
        
        if(isset($objRole)) {
            $obj = new User();
            $obj->name = mb_strtoupper($request->nome, 'UTF-8');
            $obj->email = mb_strtolower($request->email, 'UTF-8');
            $obj->password = Hash::make($request->senha);
            $obj->role()->associate($objRole);
            $this->repository->save($obj);
            return redirect()->route('users.role', $objRole->nome);
        }
        
        return view('message')
                    ->with('template', "main")
                    ->with('type', "danger")
                    ->with('titulo', "OPERAÇÃO INVÁLIDA")
                    ->with('message', "Não foi possível efetuar o procedimento!")
                    ->with('link', "home");
    }

    public function show(string $id){

        $this->authorize('hasFullPermission', Auth::user());
        $data = $this->repository->findById($id);

        if(isset($data)) {
            $roles = (new RoleRepository())->selectAll((object) ["use" => false, "rows" => 0]);
            $projeto = (new ProjetoRepository())->selectAll((object) ["use" => false, "rows" => 0]);
            $nome = (new RoleRepository())->findById($data->role_id)->nome;
            return view('user.show', compact(['data', 'projeto', 'roles', 'nome']));
        } 
        
        return view('message')
            ->with('template', "main")
            ->with('type', "danger")
            ->with('titulo', "OPERAÇÃO INVÁLIDA")
            ->with('message', "Não foi possível efetuar o procedimento!")
            ->with('link', "home");
    }

    public function edit(string $id) {
        
        $this->authorize('hasFullPermission', Auth::user());
        $data = $this->repository->findByIdWith(['curso'], $id);

        if(isset($data)) {
            $roles = (new RoleRepository())->selectAll((object) ["use" => false, "rows" => 0]);
            $nome = (new RoleRepository())->findById($data->role_id)->nome;
            $role_id = $data->role_id;
            return view('user.edit', compact(['data', 'projetos', 'roles', 'nome', 'role_id']));
        } 

        return view('message')
            ->with('template', "main")
            ->with('type', "danger")
            ->with('titulo', "OPERAÇÃO INVÁLIDA")
            ->with('message', "Não foi possível efetuar o procedimento!")
            ->with('link', "home");   
    }

    public function update(Request $request, string $id) {

        $this->authorize('hasFullPermission', Auth::user());
        $nome = (new RoleRepository())->findById($this->repository->findById($id)->role_id)->nome;
        $obj = $this->repository->findById($id);
        $objRole = (new RoleRepository())->findById($request->role_id);
        
        if(isset($obj) && isset($objRole)) {
            $obj->name = mb_strtoupper($request->nome, 'UTF-8');
            $obj->email = mb_strtolower($request->email, 'UTF-8');
            $obj->password = Hash::make($request->password); 
            $obj->role()->associate($objRole);
            $this->repository->save($obj);
            return redirect()->route('users.role', $nome);
        }
        
        return view('message')
            ->with('template', "main")
            ->with('type', "danger")
            ->with('titulo', "OPERAÇÃO INVÁLIDA")
            ->with('message', "Não foi possível efetuar o procedimento!")
            ->with('link', "home");
    }

    public function destroy(string $id) {

        $this->authorize('hasFullPermission', Auth::user());
        $nome = (new RoleRepository())->findById($this->repository->findById($id)->role_id)->nome;
        if($this->repository->delete($id))  {
            return redirect()->route('users.role', $nome);
        }
        
        return view('message')
            ->with('template', "main")
            ->with('type', "danger")
            ->with('titulo', "OPERAÇÃO INVÁLIDA")
            ->with('message', "Não foi possível efetuar o procedimento!")
            ->with('link', "home");
    }

    public function getUsersByRole($role) {

        $this->authorize('hasFullPermission', User::class);
        $role = mb_strtoupper($role, 'UTF-8');
        $objRole = (new RoleRepository())->findFirstByColumn("nome", $role);
        $data = $this->repository->findByColumn(
            'role_id', 
            $objRole->id,
            (object) ["use" => true, "rows" => $this->repository->getRows()]
        );
        $route = "users.role.create";
        $id = $objRole->id;
        // return $data;
        return view('user.index', compact('data', 'role', 'route', 'id'));
    }

    public function createUsersByRole($role_id) {

        $this->authorize('hasFullPermission', Auth::user());
        $nome = (new RoleRepository())->findById($role_id)->nome;
        $projetos = (new ProjetoRepository())->selectAll((object) ["use" => false, "rows" => 0]);
        // dd($projetos);
        $roles = (new RoleRepository())->selectAll((object) ["use" => false, "rows" => 0]);
        return view('user.create', compact(['projeto', 'roles', 'role_id', 'nome']));
    }
}
