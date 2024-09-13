@extends('templates/main', ['titulo'=>"NOVA TAREFA"])

@section('conteudo')

    <form action="{{ route('tarefa.store') }}" method="POST">
        @csrf
        <x-textbox name="nome" label="Nome" type="text" value="null" disabled="false"/>
        <x-selectbox name="tarefa_id" label="Tarefa" color="success" :data="$tarefas" field="nome" disabled="false" select="-1"/>
        <div class="row">
            <div class="col text-start">
                <x-button label="Voltar" type="link" route="tarefa.index" color="secondary"/>
            </div>
            <div class="col text-end">
                <x-button label="Cadastar" type="submit" route="" color="success"/>
            </div>
        </div>
    </form>
    
@endsection