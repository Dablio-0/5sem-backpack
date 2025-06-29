@extends('layout')

@section('content')
<center>
    <h1>Algoritmos Genéticos</h1>
</center>
<div class="row">
    <div class="col s12">
        <div class="card">
            <form method="POST" action="{{ route('home.genetic.solve') }}">
                @csrf
                <div class="card-content center-align">
                    <span class="card-title">Problema da Mochila</span>
                    <div class="row">
                        <div class="input-field col s12 l6 center-align">
                            <input type="text" name="max_capacity" id="max_capacity" value="{{ $max_capacity ?? '' }}" @error('max_capacity') is-invalid @enderror />
                            <label for="max_capacity">Capacidade da Mochila</label>
                        </div>
                        <div class="input-field col s12 l6 center-align">
                            <input type="text" name="item_count" id="item_count" value="{{ $item_count ?? '' }}" @error('item_count') is-invalid @enderror />
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
                        <textarea readonly style="width: 100%; height: 200px; resize: vertical; overflow: auto;">{{ json_encode($initial_solution ?? $primarySolution, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                        <p><strong>Quantidade de Valores:</strong> {{ count($initial_solution) }}</p>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <center>
                        <p><strong>Avaliação:</strong> {{ $evaluation }}</p>
                    </center>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <div class="card">
            <form method="POST" action="{{ route('home.genetic.improve', [
                                                        'initialSolution' => json_encode($initial_solution ?? ''), 
                                                        'generatedProblem' => json_encode($generated_problem ?? ''), 
                                                        'items' => json_encode($items ?? ''), 
                                                        'evaluation' => $evaluation ?? '',
                                                        'itemCount' => $item_count ?? '', 
                                                        'maxCapacity' => $max_capacity ?? ''
                                                    ]) }}">
                @csrf
                <div class="card-content center-align">
                    <span class="card-title">Parâmetros Genéticos</span>
                    <div class="row">
                        <div class="input-field col s12 l6 center-align">
                            <input type="text" name="populationSize" id="populationSize" value="{{ $populationSize ?? '' }}" @error('populationSize') is-invalid @enderror />
                            <label for="populationSize">Tamanho da População</label>
                        </div>
                        <div class="input-field col s12 l6 center-align">
                            <input type="text" name="crossoverTax" id="cruzeTax" value="{{ $cruzeTax ?? '' }}" @error('cruzeTax') is-invalid @enderror />
                            <label for="cruzeTax">Taxa de Cruzamento</label>
                        </div>
                        <div class="input-field col s12 l6 center-align">
                            <input type="text" name="mutationTax" id="mutationTax" value="{{ $mutationTax ?? '' }}" @error('mutationTax') is-invalid @enderror />
                            <label for="mutationTax">Taxa de Mutação</label>
                        </div>
                        <div class="input-field col s12 l6 center-align">
                            <input type="text" name="generationInterval" id="generationInterval" value="{{ $generationInterval ?? '' }}" @error('generationInterval') is-invalid @enderror />
                            <label for="generationInterval">Intervalo de Geração</label>
                        </div>
                        <div class="input-field col s12 l4 center-align offset-l4 offset-m3">
                            <input type="text" name="generationNum" id="generationNum" value="{{ $generationNum ?? '' }}" @error('generationNum') is-invalid @enderror />
                            <label for="generationNum">Número de Gerações</label>
                        </div>
                        <div class="switch col s12 l4 center-align offset-l4 offset-m3">
                            <label for="defaultParameters">
                                <input type="checkbox" name="defaultParameters" id="defaultParameters" value="1" {{ old('defaultParameters', $defaultParameters ?? false) ? 'checked' : '' }}>
                                <span class="lever"></span>
                                    <strong>Usar Parâmetros Pré-definidos</strong>
                            </label>
                            @error('defaultParameters')
                                <small class="red-text">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-action">
                    <div class="center-align">
                        <input type="submit" value="Aplicar Genes" class="btn orange darken-2 " />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content center-align">
                <span class="card-title">Visualização de Resultados - Algoritmos Genéticos</span>
            </div>

            @if(isset($data) && $data->isNotEmpty())
            @php
            // O primeiro item da coleção $data contém os dados iniciais do problema
            $initialData = $data->first();
            // Verifica a flag para saber se foram usados os parâmetros padrão
            $isDefaultParametersRun = $initialData['default_parameters_used_for_execution'] ?? false;
            @endphp

            {{-- Seção para exibir os dados iniciais do problema --}}
            <div class="row">
                <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 200px;">
                        <p><strong>Problema Gerado:</strong></p>
                        <textarea readonly style="width: 100%; height: 200px; resize: vertical; overflow: auto;">{{ json_encode($initialData['generated_problem'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                        <p><strong>Quantidades de Valores:</strong> {{ count($initialData['generated_problem']) }}</p>
                    </div>

                    <div style="flex: 1; min-width: 200px;">
                        <p><strong>Itens:</strong></p>
                        <textarea readonly style="width: 100%; height: 200px; resize: vertical; overflow: auto;">{{ json_encode($initialData['items'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                        <p><strong>Quantidade de Valores:</strong> {{ count($initialData['items']) }}</p>
                    </div>

                    <div style="flex: 1; min-width: 200px;">
                        <p><strong>Solução Inicial:</strong></p>
                        <textarea readonly style="width: 100%; height: 200px; resize: vertical; overflow: auto;">{{ json_encode($initialData['primary_solution'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                        <p><strong>Quantidade de Valores:</strong> {{ count($initialData['primary_solution']) }}</p>
                    </div>
                </div>
                <div style="margin-top: 20px;">
                    <p><strong>Avaliação Inicial:</strong> {{ $initialData['primary_evaluation'] }}</p>
                </div>
            </div>

            {{-- Seção para exibir os resultados do Algoritmo Genético --}}
            @if($isDefaultParametersRun)
            {{-- Múltiplas execuções com parâmetros padrão --}}
            <h5>Resultados das Múltiplas Execuções (Parâmetros Padrão)</h5>
            @foreach($data->slice(1) as $result)
            <div class="card-panel grey lighten-4 z-depth-1" style="margin-bottom: 20px;">
                <h6>Configuração:</h6>
                <ul>
                    <li><strong>TP (Tamanho da População):</strong> {{ $result['TP'] ?? 'N/A' }}</li>
                    <li><strong>NG (Número de Gerações):</strong> {{ $result['NG'] ?? 'N/A' }}</li>
                    <li><strong>TC (Taxa de Cruzamento):</strong> {{ $result['TC'] ?? 'N/A' }}</li>
                    <li><strong>TM (Taxa de Mutação):</strong> {{ $result['TM'] ?? 'N/A' }}</li>
                    <li><strong>IG (Intervalo de Geração):</strong> {{ $result['IG'] ?? 'N/A' }}</li>
                </ul>
                <h6>Resultados:</h6>
                <ul>
                    <li><strong>Melhor Solução:</strong> {{ json_encode($result['best_solution'] ?? 'N/A') }}</li>
                    <li><strong>Avaliação da Melhor Solução:</strong> {{ $result['evaluation_bestSolution'] ?? 'N/A' }}</li>
                    <li><strong>Ganho:</strong> {{ $result['ganho'] ?? 'N/A' }}</li>
                    <li><strong>Ganho Porcentagem:</strong> {{ $result['ganho_porcentagem'] ?? 'N/A' }}</li>
                </ul>
            </div>
            @endforeach
            @else
            {{-- Única execução com parâmetros do usuário --}}
            <h5>Resultado da Execução (Parâmetros do Usuário)</h5>
            @php
            $singleResult = $data->slice(1)->first(); // Pega o primeiro (e único) resultado do GA
            @endphp
            @if($singleResult)
            <div class="card-panel grey lighten-4 z-depth-1">
                <ul>
                    <li><strong>Melhor Solução:</strong> {{ json_encode($singleResult['best_solution'] ?? 'N/A') }}</li>
                    <li><strong>Avaliação da Melhor Solução:</strong> {{ $singleResult['evaluation_bestSolution'] ?? 'N/A' }}</li>
                    <li><strong>Ganho:</strong> {{ $singleResult['gain'] ?? 'N/A' }}</li>
                    <li><strong>Ganho Porcentagem:</strong> {{ $singleResult['gain'] ?? 'N/A' }}</li>
                </ul>
            </div>
            @else
            <p>Nenhum resultado de algoritmo genético encontrado para os parâmetros do usuário.</p>
            @endif
            @endif
            @else
            <p class="center-align">Nenhum dado para exibir. Por favor, execute o algoritmo genético.</p>
            @endif
        </div>
    </div>
</div>
@endsection