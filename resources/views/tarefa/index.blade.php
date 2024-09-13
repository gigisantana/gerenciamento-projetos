@extends('templates/main', ['titulo'=>"TAREFAS"])

@section('conteudo')

    <x-datatable 
        title="Tabela de Tarefas" 
        :header="['ID', 'Nome', 'Ações']" 
        crud="tarefa" 
        :data="$data"
        :fields="['id', 'nome']" 
        :hide="[true, false, false]"
        remove="nome"
        create="tarefa.create" 
        id=""
        modal=""
    /> 
@endsection