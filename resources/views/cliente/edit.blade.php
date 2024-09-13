@extends('templates/main', ['titulo'=>"ALTERAR CLIENTE"])

@section('conteudo')

    <form action="{{ route('cliente.update', $data->id) }}" method="POST">
        @csrf
        @method('PUT')
        <x-textbox name="nome" label="Nome" type="text" :value="$data->nome" disabled="false"/>
        <x-textbox name="cpf" label="CPF" type="number" :value="$data->cpf" disabled="false"/>
        <x-selectbox name="projeto_id" label="Projeto" color="success" :data="$projetos" field="nome" disabled="false" :select="$data->projeto_id"/>
        <div class="row">
            <div class="col text-start">
                <x-button label="Voltar" type="link" route="cliente.index" color="secondary"/>
            </div>
            <div class="col text-end">
                <x-button label="Alterar" type="submit" route="" color="success"/>
            </div>
        </div>
    </form>

@endsection


@section('script')

    <script type="text/javascript">

        document.getElementById('projeto_id').addEventListener('change', function() {

            // Obtém id do projeto selecionado
            let projeto_id = this.value

            // Requisição a API
            $.getJSON('/api/turma/'+projeto_id, function(data) {

                // Remove todos os Elementos do Select
                $('#evento_id').children().remove().end()
                // Preenche o Select
                data.map((item) => {
                    $('#evento_id').append(new Option(item.id))
                });
                // Habilita Select
                $('#evento_id').removeAttr('disabled');
            });

            // Desabilita Select
            // $('#id').attr('disabled', 'disabled');  
        });

    </script>

@endsection