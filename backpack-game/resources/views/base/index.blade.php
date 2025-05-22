@extends('layout')

@section('content')
    <h1>Metódos Básicos</h1>
    <p>Configuração Fixa!</p>
    <div class="row">
        <div class="col s12">
            <div class="card">
                <form method="POST">
                    @csrf
                    <div class="card-content center-align">
                        <span class="card-title">Problema da Mochila</span>
                        <div class="row">
                            <div class="input-field col s12 l6 center-align">
                                <input type="text" name="max_capacity" id="max_capacity" value="{{ $max_capacity ?? '' }}" />
                                <label for="max_capacity">Capacidade da Mochila</label>
                            </div>
                            <div class="input-field col s12 l6 center-align">
                                <input type="text" name="item_count" id="item_count" value="{{ $item_count ?? '' }}" />
                                <label for="item_count">Quantidade de Itens</label>
                            </div>
                        </div>
                        <div class="card-action">
                            <div class="center-align">
                                <input type="submit" value="Executar" class="btn orange darken-2 " />
                            </div> 
                        </div> 
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title center-align">Visualização - Dados Iniciais</span>
                    @if(isset($generated_problem))
                        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                            <div style="flex: 1; min-width: 200px;">
                                <p><strong>Problema Gerado:</strong></p>
                                <textarea readonly style="width: 100%; height: 200px; resize: vertical; overflow: auto;">{{ json_encode($generated_problem, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                            </div>

                            @if(isset($items))
                                <div style="flex: 1; min-width: 200px;">
                                    <p><strong>Itens:</strong></p>
                                    <textarea readonly style="width: 100%; height: 200px; resize: vertical; overflow: auto;">{{ json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                                </div>
                            @endif

                            <div style="flex: 1; min-width: 200px;">
                                <p><strong>Solução Inicial:</strong></p>
                                <textarea readonly style="width: 100%; height: 200px; resize: vertical; overflow: auto;">{{ json_encode($initial_solution, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                            </div>
                        </div>

                        <div style="margin-top: 20px;">
                            <center><p><strong>Avaliação:</strong> {{ $evaluation }}</p></center>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            <div class="card">
                <form method="POST">
                    @csrf
                    <div class="card-content center-align">
                        <span class="card-title">Configuração do Problema</span>
                        <div class="row">
                            <div class="input-field col s12 l6">
                                <select name="metodo_melhoria" id="metodo_melhoria" class="browser-default">
                                    <option value="" selected>Selecione um Método</option>
                                    <option value="1">Subida de Encosta</option>
                                    <option value="2">Têmpera</option>
                                    <option value="3">Têmpera Simulada</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-action">
                            <div class="center-align">
                                <input type="submit" value="Aplicar Método(s)" class="btn orange darken-2 " />
                            </div>  
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title center-align">Visualização - Resultados Melhorados</span>
                    @if(isset($generated_problem))

                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection