@extends('layout')

@section('content')
    <h1>Metódos Básicos</h1>
    <div class="row">
        <div class="col s12">
            <div class="card">
                <form method="POST" action="{{ route('home.base.solve') }}">
                    @csrf
                    <div class="card-content center-align">
                        <span class="card-title">Problema da Mochila</span>
                        <div class="row">
                            <div class="input-field col s12 l6 center-align">
                                <input type="text" name="max_capacity" id="max_capacity" value="{{ $max_capacity ?? '' }}"  @error('max_capacity') is-invalid @enderror/>
                                <label for="max_capacity">Capacidade da Mochila</label>
                            </div>
                            <div class="input-field col s12 l6 center-align">
                                <input type="text" name="item_count" id="item_count" value="{{ $item_count ?? '' }}" @error('item_count') is-invalid @enderror/>
                                <label for="item_count">Quantidade de Itens</label>
                            </div>
                        </div>
                        <div class="card-action">
                            <div class="center-align">
                                <input type="submit" value="Executar" class="btn orange darken-2 " />
                                <input type="reset" value="Limpar" class="btn orange darken-2 " />
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
                                <p><strong>Quantidades de Valores:</strong> {{ count($generated_problem) }}</p>
                            </div>

                            <div style="flex: 1; min-width: 200px;">
                                <p><strong>Itens:</strong></p>
                                <textarea readonly style="width: 100%; height: 200px; resize: vertical; overflow: auto;">{{ json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                                <p><strong>Quantidade de Valores:</strong> {{ count($items) }}</p>
                            </div>

                            <div style="flex: 1; min-width: 200px;">
                                <p><strong>Solução Inicial:</strong></p>
                                <textarea readonly style="width: 100%; height: 200px; resize: vertical; overflow: auto;">{{ json_encode($initial_solution, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                                <p><strong>Quantidade de Valores:</strong> {{ count($initial_solution) }}</p>
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
                <form method="POST" action="{{ route('home.base.improve') }}">
                    @csrf
                    <input type="hidden" name="initial_solution" value="{{ json_encode($initial_solution ?? '') ?? '' }}" />
                    <input type="hidden" name="generated_problem" value="{{ json_encode($generated_problem ?? '') ?? '' }}" />
                    <input type="hidden" name="items" value="{{ json_encode($items ?? '') ?? '' }}" /
                    <input type="hidden" name="evaluation" value="{{ $evaluation ?? '' }}" />
                    <input type="hidden" name="item_count" value="{{ $item_count ?? '' }}" />
                    <input type="hidden" name="max_capacity" value="{{ $max_capacity ?? '' }}" />
                    <div class="card-content center-align">
                        <span class="card-title">Algoritmos de Busca Local</span>
                        <div class="row">
                            <div class="input-field col s12 l4 offset-l4 offset-m3 center-align">
                                <select id="improvement_method" name="improvement_method" class="browser-default" @error('improvement_method') invalid @enderror">
                                    <option value="" selected>Escolha um método</option>
                                    <option value="1" {{ old('improvement_method') == 1 ? 'selected' : '' }}>Subida de Encosta</option>
                                    <option value="2" {{ old('improvement_method') == 2 ? 'selected' : '' }}>Subida de Encosta Alterada</option>
                                    <option value="3" {{ old('improvement_method') == 3 ? 'selected' : '' }}>Têmpera Simulada</option>
                                    <option value="4" {{ old('improvement_method') == 4 ? 'selected' : '' }}>Todos</option>
                                </select>
                            @error('improvement_method')
                                <span class="helper-text red-text">{{ $message }}</span>
                            @enderror
                            </div>
                        </div>
                        <div id="dynamic-fields">
                            <!-- Html de configuração de parâmetros do método de busca local selecionado -->
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
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('improvement_method').addEventListener('change', function() {
            const selectedValue = this.value;
            console.log('Método selecionado:', selectedValue);
            const configDiv = document.getElementById('dynamic-fields');
            if (selectedValue == 1) {
                configDiv.innerHTML = `
                    <br>
                    <div class="divider"></div>
                    <br>
                    <div class="row">
                        <div class="input-field col s12 l4 offset-l4 offset-m3">
                            <input type="text" name="successors_num_se" id="successors_num_se" value="{{ $successors_num_se ?? '' }}" @error('successors_num_se') is-invalid @enderror/>
                            <label for="successors_num_se">Máximo de Iterações (Sucessores)</label>
                        </div>
                    </div>
                `;
                configDiv.style.display = 'block';
            } else if (selectedValue == 2) {
                configDiv.innerHTML = `
                    <br>
                    <div class="divider"></div>
                    <div class="row">
                        <div class="input-field col s12 l6">
                            <input type="text" name="successors_num_sea" id="successors_num_sea" value="{{ $successors_num_sea ?? '' }}" @error('successors_num_sea') is-invalid @enderror/>
                            <label for="successors_num_sea">Máximo de Iterações (Sucessores)</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <input type="text" name="max_attemps" id="max_attemps" value="{{ $max_attemps ?? '' }}" @error('max_attemps') is-invalid @enderror />
                            <label for="max_attemps">Máximo de Tentativas</label>
                        </div>
                    </div>
                `;
                configDiv.style.display = 'block';
            } else if (selectedValue == 3) {
                configDiv.innerHTML = `
                    <br>
                    <div class="divider"></div>
                    <div class="row">
                        <div class="input-field col s12 l6 offset-l3 offset-m4">
                            <input type="text" name="successors_num_ts" id="successors_num_ts" value="{{ $successors_num_ts ?? '' }}" @error('successors_num_ts') is-invalid @enderror />
                            <label for="successors_num_ts">Número de Sucessores</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 l4">
                            <input type="text" name="initial_temp" id="initial_temp" value="{{ $initial_temp ?? '' }}" @error('initial_temp') is-invalid @enderror />
                            <label for="initial_temp">Temperatura Inicial</label>
                        </div>
                        <div class="input-field col s12 l4">
                            <input type="text" name="final_temp" id="final_temp" value="{{ $final_temp ?? '' }}" @error('final_temp') is-invalid @enderror/>
                            <label for="final_temp">Temratura Final</label>
                        </div>
                        <div class="input-field col s12 l4">
                            <input type="text" name="reducing_factor" id="reducing_factor" value="{{ $reducing_factor ?? '' }}" @error('reducing_factor') is-invalid @enderror/>
                            <label for="reducing_factor">Fator Redutor</label>
                        </div>
                    </div>
                `;
                configDiv.style.display = 'block';
            } else if (selectedValue == 4) {
                configDiv.innerHTML = `
                    <br>
                    <div class="divider"></div>
                    <div class="row">
                        <br>
                        <center ><p><strong>Subida de Encosta</strong></p></center>
                        <div class="input-field col s12 l4 offset-l4 offset-m3">
                            <input type="text" name="successors_num_se" id="successors_num_se" value="{{ $successors_num_se ?? '' }}" @error('successors_num_se') is-invalid @enderror/>
                            <label for="successors_num_se">Máximo de Iterações (Sucessores)</label>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="row">
                        <br>
                        <center ><p><strong>Subida de Encosta Alterada</strong></p></center>
                        <div class="input-field col s12 l6">
                            <input type="text" name="successors_num_sea" id="successors_num_sea" value="{{ $successors_num_sea ?? '' }}" @error('successors_num_sea') is-invalid @enderror/>
                            <label for="successors_num_sea">Máximo de Iterações (Sucessores)</label>
                        </div>
                        <div class="input-field col s12 l6">
                            <input type="text" name="max_attemps" id="max_attemps" value="{{ $max_attemps ?? '' }}" @error('max_attemps') is-invalid @enderror />
                            <label for="max_attemps">Máximo de Tentativas</label>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="row">
                        <br>
                        <center ><p><strong>Têmpera Simulada</strong></p></center>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 l6 offset-l3 offset-m4">
                            <input type="text" name="successors_num_ts" id="successors_num_ts" value="{{ $successors_num_ts ?? '' }}" @error('successors_num_ts') is-invalid @enderror />
                            <label for="successors_num_ts">Número de Sucessores</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 l4">
                            <input type="text" name="initial_temp" id="initial_temp" value="{{ $initial_temp ?? '' }}" @error('initial_temp') is-invalid @enderror />
                            <label for="initial_temp">Temperatura Inicial</label>
                        </div>
                        <div class="input-field col s12 l4">
                            <input type="text" name="final_temp" id="final_temp" value="{{ $final_temp ?? '' }}" @error('final_temp') is-invalid @enderror/>
                            <label for="final_temp">Temratura Final</label>
                        </div>
                        <div class="input-field col s12 l4">
                            <input type="text" name="reducing_factor" id="reducing_factor" value="{{ $reducing_factor ?? '' }}" @error('reducing_factor') is-invalid @enderror/>
                            <label for="reducing_factor">Fator Redutor</label>
                        </div>
                    </div>
                `;
                configDiv.style.display = 'block';
            }
        });
        
        const selectedMethod = '{{ old('improvement_method') }}';

        if (selectedMethod) {
            document.getElementById('improvement_method').value = selectedMethod;
            document.getElementById('improvement_method').dispatchEvent(new Event('change'));
        }

    });
</script>
@endsection