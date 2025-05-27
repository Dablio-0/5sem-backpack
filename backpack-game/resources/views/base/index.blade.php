@extends('layout')

@section('content')
    <h1>Metódos Básicos</h1>
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
                            <div class="input-field col s12 l4 offset-l4 offset-m3 center-align">
                                <select name="metodo_melhoria" id="metodo_melhoria" class="browser-default">
                                    <option value="" selected>Selecione um Método</option>
                                    <option value="1">Subida de Encosta</option>
                                    <option value="2">Subida de Encosta Alterada</option>
                                    <option value="3">Têmpera Simulada</option>
                                </select>
                            </div>
                        </div>
                        <div id="dynamic-fields">
                            <!-- Html de configuração de parâmetros do método de busca local selecionado -->
                        </div>
                        </div>
                        <div class="card-action">
                            <div class="center-align">
                                <input type="submit" value="Aplicar Método" class="btn orange darken-2 " />
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('metodo_melhoria').addEventListener('change', function() {
            const selectedValue = this.value;
            console.log('Método selecionado:', selectedValue);
            const configDiv = document.getElementById('dynamic-fields');
            if (selectedValue == 1) {
            configDiv.innerHTML = `
                <div class="divider"></div>
                <div class="row">
                    <div class="input-field col s12 l4 offset-l4 offset-m3">
                        <input type="text" name="max_iterations" id="max_iterations" value="{{ $max_iterations ?? '' }}" />
                        <label for="max_iterations">Máximo de Iterações</label>
                    </div>
                </div>
            `;
            configDiv.style.display = 'block';
            } else if (selectedValue == 2) {
            configDiv.innerHTML = `
                <div class="divider"></div>
                <div class="row">
                    <div class="input-field col s12 l6">
                        <input type="text" name="successors_num" id="successors_num" value="{{ $successors_num ?? '' }}" />
                        <label for="successors_num">Número de Sucessores</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <input type="text" name="attemps" id="attemps" value="{{ $attemps ?? '' }}" />
                        <label for="attemps">Tentativas</label>
                    </div>
                </div>
            `;
            } else if (selectedValue == 3) {
            configDiv.innerHTML = `
                <div class="divider"></div>
                <div class="row">
                    <div class="input-field col s12 l6 offset-l3 offset-m4">
                        <input type="text" name="successors_num" id="successors_num" value="{{ $successors_num ?? '' }}" />
                        <label for="successors_num">Número de Sucessores</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 l3">
                        <input type="text" name="initial_temp" id="initial_temp" value="{{ $initial_temp ?? '' }}" />
                        <label for="initial_temp">Temperatura Inicial</label>
                    </div>
                    <div class="input-field col s12 l3">
                        <input type="text" name="final_temp" id="final_temp" value="{{ $final_temp ?? '' }}" />
                        <label for="final_temp">Temratura Final</label>
                    </div>
                    <div class="input-field col s12 l3">
                        <input type="text" name="reducing_factor" id="reducing_factor" value="{{ $reducing_factor ?? '' }}" />
                        <label for="reducing_factor">Fator Redutor</label>
                    </div>
                    <div class="input-field col s12 l3">
                        <input type="text" name="t_max" id="t_max" value="{{ $t_max ?? '' }}" />
                        <label for="t_max">T_MAX</label>
                    </div>
                </div>
            `;
            } else {
                configDiv.innerHTML = '';
            }
        });
    });
</script>
@endsection