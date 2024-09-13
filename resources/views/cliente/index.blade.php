@extends('templates/main', ['titulo'=>"CLIENTES"])

@section('conteudo')

    <x-tablist
        :tabs="$data"
        fieldtab="projeto"
        id="id"
        data="clientes"
        fielddata="nome"
        :header="['ID', 'Nome', 'Ações']" 
        :fields="['id', 'nome']"
        :hide="[true, false, false]" 
        crud="cliente"
        create="cliente.create"
        contentype="datatable"
        primaryroute=""
        secondaryroute=""
    />
@endsection