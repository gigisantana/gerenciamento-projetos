@extends('templates/main', ['titulo'=>"DETALHES DO PROJETO"])

@section('conteudo')

    <x-textbox name="nome" label="Nome" type="text" value="{{$data->nome}}" disabled="true"/>
    <x-listbox title="Projetos" :data="$data->projeto" field="nome"/>
    <div class="row">
        <div class="col text-start mt-3">
            <x-button label="Voltar" type="link" route="evento.index" color="secondary"/>
        </div>
    </div>
    
    
@endsection