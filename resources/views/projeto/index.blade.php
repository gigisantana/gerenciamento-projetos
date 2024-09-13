@extends('templates/main', ['titulo'=>"PROJETO"])

@section('conteudo')

    <x-datatable 
        title="Tabela de Projetos" 
        :header="['ID', 'Nome', 'Ações']" 
        crud="projeto" 
        :data="$data"
        :fields="['id', 'nome']" 
        :hide="[true, false, false]"
        remove="nome"
        create="projeto.create" 
        id=""
        modal=""
    /> 
@endsection