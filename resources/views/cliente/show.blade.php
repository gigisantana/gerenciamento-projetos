@extends('templates/main', ['titulo'=>"DETALHES DO CLIENTE"])

@section('conteudo')

    <x-textbox name="nome" label="Nome" type="text" :value="$data->nome" disabled="true"/>
    <x-textbox name="cpf" label="CPF" type="text" :value="$data->cpf" disabled="true"/>
    <x-textbox name="email" label="E-mail" type="email" :value="$data->email" disabled="true"/>
    <x-textbox name="projeto" label="Projeto" type="text" :value="$data->projeto->nome" disabled="true"/>
    <div class="row">
        <div class="col text-start">
            <x-button label="Voltar" type="link" route="cliente.index" color="secondary"/>
        </div>
    </div>

@endsection
