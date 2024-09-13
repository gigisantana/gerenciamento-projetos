@extends('templates/main', ['titulo'=>"DETALHES DA TAREFA"])

@section('conteudo')

    <x-textbox name="nome" label="Nome" type="text" value="{{$data->nome}}" disabled="true"/>
    <x-textbox name="projeto" label="Projeto" type="text" value="{{$data->projeto->nome}}" disabled="true"/>
    <div class="row">
        <div class="col text-start">
            <x-button label="Voltar" type="link" route="tarefa.index" color="secondary"/>
        </div>
    </div>

@endsection