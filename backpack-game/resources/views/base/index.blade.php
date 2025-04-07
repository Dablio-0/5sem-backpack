@extends('layout')

@section('content')
    <h1>Metódos Básicos</h1>
    <p>Configuração Fixa!</p>

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Problema da Mochila</span>
                    <form method="POST">
                        @csrf
                        <div class="row">
                            <div class="input-field col s12 l3 xl2">
                                <input type="text" name="max_capacity" id="max_capacity" value="{{ $max_capacity ?? '' }}" />
                                <label for="max_capacity">Capacidade da Mochila</label>
                            </div>
                            <div class="input-field col s12 l3 xl2">
                                <input type="text" name="item_count" id="item_count" value="{{ $item_count ?? '' }}" />
                                <label for="item_count">Quantidade de Itens</label>
                            </div>
                        </div>
                        <div>
                            <input type="submit" value="Gerar Problema" class="btn waves-effect waves-light" />
                        </div>  
                    </form>
                </div>
            </div>
        </div>
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Visualização dos Dados</span>
                    array de dados
                </div>
            </div>
        </div>
    </div>
@endsection