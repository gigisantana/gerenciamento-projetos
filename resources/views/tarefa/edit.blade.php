@extends('templates/main', ['titulo'=>"ALTERAR TAREFA"])

@section('conteudo')

    <form action="{{ route('tarefa.update', $data->id) }}" method="POST">
        @csrf
        @method('PUT')
        <x-textbox name="nome" label="Nome" type="text" :value="$data->nome" disabled="false"/>
        <x-selectbox name="projeto_id" label="Projeto" color="success" :data="$projetos" field="nome" disabled="false" :select="$data->projeto_id"/>
        <div class="row">
            <div class="col text-start">
                <x-button label="Voltar" type="link" route="tarefa.index" color="secondary"/>
            </div>
            <div class="col text-end">
                <x-button label="Alterar" type="submit" route="" color="success"/>
            </div>
        </div>
    </form>
    
@endsection