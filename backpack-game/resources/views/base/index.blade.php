@extends('layout')

@section('content')
    <center><h1>Metódos Básicos</h1></center>
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
                                <textarea readonly style="width: 100%; height: 200px; resize: vertical; overflow: auto;">{{ json_encode($initial_solution ?? $primarySolution, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
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
                <form method="POST" action="{{ route('home.base.improve', [
                                                        'initialSolution' => json_encode($initial_solution ?? ''), 
                                                        'generatedProblem' => json_encode($generated_problem ?? ''), 
                                                        'items' => json_encode($items ?? ''), 
                                                        'evaluation' => $evaluation ?? '',
                                                        'itemCount' => $item_count ?? '', 
                                                        'maxCapacity' => $max_capacity ?? ''
                                                    ]) }}">
                    @csrf
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
                <div class="card-content center-align">
                    <span class="card-title">Visualização - Resultados Melhorados</span>
                    @if(isset($data))
                        <div class="row" >
                            <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 200px;">
                                    <p><strong>Problema Gerado:</strong></p>
                                    <textarea readonly style="width: 100%; height: 200px; resize: vertical; overflow: auto;">{{ json_encode($data['generated_problem'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                                    <p><strong>Quantidades de Valores:</strong> {{ count($data['generated_problem']) }}</p>
                                </div>

                                <div style="flex: 1; min-width: 200px;">
                                    <p><strong>Itens:</strong></p>
                                    <textarea readonly style="width: 100%; height: 200px; resize: vertical; overflow: auto;">{{ json_encode($data['items'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                                    <p><strong>Quantidade de Valores:</strong> {{ count($data['items']) }}</p>
                                </div>

                                <div style="flex: 1; min-width: 200px;">
                                    <p><strong>Solução Inicial:</strong></p>
                                    <textarea readonly style="width: 100%; height: 200px; resize: vertical; overflow: auto;">{{ json_encode($data['primary_solution'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                                    <p><strong>Quantidade de Valores:</strong> {{ count($data['primary_solution']) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @if($data['method'] == 1)   
                                <center<p><strong>Método Utilizado:</strong> Subida de Encosta</p>
                                <div class="divider"></div>
                                @php
                                    $hillClimbing = $data['results']['hillClimbing'] ?? null;
                                    $output = '';

                                    if ($hillClimbing && isset($hillClimbing['successors_generated'])) {
                                        $output .= "Número de sucessores gerados: " . count($hillClimbing['successors_generated']) . "\n\n";

                                        foreach ($hillClimbing['successors_generated'] as $index => $succ) {
                                            $solution = implode(', ', $succ['solution']);
                                            $evaluation = $succ['evaluation'];
                                            $output .= "Sucessor #" . ($index + 1) . ":\n";
                                            $output .= "  Solução: [{$solution}]\n";
                                            $output .= "  Avaliação: {$evaluation}\n\n";
                                        }

                                        if (isset($hillClimbing['final_solution'], $hillClimbing['final_evaluation'])) {
                                            $finalSolution = implode(', ', $hillClimbing['final_solution']);
                                            $finalEvaluation = $hillClimbing['final_evaluation'];
                                            $output .= "Solução Final:\n";
                                            $output .= "  Solução: [{$finalSolution}]\n";
                                            $output .= "  Avaliação: {$finalEvaluation}\n";
                                        }
                                    } else {
                                        $output .= "Nenhum dado disponível para Subida de Encosta.";
                                    }
                                @endphp
                                <p><strong>Resultados da Subida de Encosta:</strong></p>
                                <textarea readonly style="width: 100%; height: 300px; resize: vertical; overflow: auto;">{{ $output }}</textarea>
                            @elseif($data['method'] == 2)
                                <center><p><strong>Método Utilizado:</strong> Subida de Encosta Alterada</p></center>
                                <div class="divider"></div>
                                @php
                                    $hillClimbing = $data['results']['changedHillClimbing'] ?? null;
                                    $output = '';

                                    if ($hillClimbing && isset($hillClimbing['successors_generated'])) {
                                        $output .= "Número de sucessores gerados: " . count($hillClimbing['successors_generated']) . "\n";
                                        $output .= "Número de Tentativas: " . $data['max_attemps'] ?? 0 . "\n\n";

                                        foreach ($hillClimbing['successors_generated'] as $index => $succ) {
                                            $solution = implode(', ', $succ['solution']);
                                            $evaluation = $succ['evaluation'];
                                            $output .= "Sucessor #" . ($index + 1) . ":\n";
                                            $output .= "  Solução: [{$solution}]\n";
                                            $output .= "  Avaliação: {$evaluation}\n\n";
                                        }

                                        if (isset($hillClimbing['final_solution'], $hillClimbing['final_evaluation'])) {
                                            $finalSolution = implode(', ', $hillClimbing['final_solution']);
                                            $finalEvaluation = $hillClimbing['final_evaluation'];
                                            $output .= "Solução Final:\n";
                                            $output .= "  Solução: [{$finalSolution}]\n";
                                            $output .= "  Avaliação: {$finalEvaluation}\n";
                                        }
                                    } else {
                                        $output .= "Nenhum dado disponível para Subida de Encosta Alterada.";
                                    }
                                @endphp
                                <p><strong>Resultados da Subida de Encosta:</strong></p>
                                <textarea readonly style="width: 100%; height: 300px; resize: vertical; overflow: auto;">{{ $output }}</textarea>
                            @elseif($data['method'] == 3)
                                <center><p><strong>Método Utilizado:</strong> Têmpera Simulada</p></center>
                                <div class="divider"></div>
                                @php
                                    $hillClimbing = $data['results']['changedHillClimbing'] ?? null;
                                    $output = '';

                                    if ($hillClimbing && isset($hillClimbing['successors_generated'])) {
                                        $output .= "Número de sucessores gerados: " . count($hillClimbing['successors_generated']) . "\n";
                                        $output .= "Temperatura Inicial: " . $data['initial_temp'] ?? 0 . "\n";
                                        $output .= "Temperatura Final: " . $data['final_temp'] ?? 0 . "\n";
                                        $output .= "Fator Redutor: " . $data['reducing_factor'] ?? 0 . "\n\n";

                                        foreach ($hillClimbing['successors_generated'] as $index => $succ) {
                                            $solution = implode(', ', $succ['solution']);
                                            $evaluation = $succ['evaluation'];
                                            $output .= "Sucessor #" . ($index + 1) . ":\n";
                                            $output .= "  Solução: [{$solution}]\n";
                                            $output .= "  Avaliação: {$evaluation}\n\n";
                                        }

                                        if (isset($hillClimbing['final_solution'], $hillClimbing['final_evaluation'])) {
                                            $finalSolution = implode(', ', $hillClimbing['final_solution']);
                                            $finalEvaluation = $hillClimbing['final_evaluation'];
                                            $output .= "Solução Final:\n";
                                            $output .= "  Solução: [{$finalSolution}]\n";
                                            $output .= "  Avaliação: {$finalEvaluation}\n";
                                        }
                                    } else {
                                        $output .= "Nenhum dado disponível para Têmpera Simulada.";
                                    }
                                @endphp
                                <p><strong>Resultados da Subida de Encosta:</strong></p>
                                <textarea readonly style="width: 100%; height: 300px; resize: vertical; overflow: auto;">{{ $output }}</textarea>
                            @elseif($data['method'] == 4)
                                <center><p><strong>Método Utilizado:</strong>Todos</p></center>
                                <div class="divider"></div>
                                <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                                    @php
                                        // Subida de Encosta
                                        $hillClimbing = $data['results']['hillClimbing'] ?? null;
                                        $outputHill = '';
                                        if ($hillClimbing && isset($hillClimbing['successors_generated'])) {
                                            $outputHill .= "Subida de Encosta:\n";
                                            $outputHill .= "Número de sucessores gerados: " . count($hillClimbing['successors_generated']) . "\n\n";
                                            foreach ($hillClimbing['successors_generated'] as $index => $succ) {
                                                $solution = implode(', ', $succ['solution']);
                                                $evaluation = $succ['evaluation'];
                                                $outputHill .= "Sucessor #" . ($index + 1) . ":\n";
                                                $outputHill .= "  Solução: [{$solution}]\n";
                                                $outputHill .= "  Avaliação: {$evaluation}\n\n";
                                            }
                                            if (isset($hillClimbing['final_solution'], $hillClimbing['final_evaluation'])) {
                                                $finalSolution = implode(', ', $hillClimbing['final_solution']);
                                                $finalEvaluation = $hillClimbing['final_evaluation'];
                                                $outputHill .= "Solução Final:\n";
                                                $outputHill .= "  Solução: [{$finalSolution}]\n";
                                                $outputHill .= "  Avaliação: {$finalEvaluation}\n";
                                            }
                                        } else {
                                            $outputHill .= "Nenhum dado disponível para Subida de Encosta.";
                                        }

                                        // Subida de Encosta Alterada
                                        $changedHillClimbing = $data['results']['changedHillClimbing'] ?? null;
                                        $outputChanged = '';
                                        if ($changedHillClimbing && isset($changedHillClimbing['successors_generated'])) {
                                            $outputChanged .= "Subida de Encosta Alterada:\n";
                                            $outputChanged .= "Número de sucessores gerados: " . count($changedHillClimbing['successors_generated']) . "\n";
                                            $outputChanged .= "Número de Tentativas: " . ($data['max_attemps'] ?? 0) . "\n\n";
                                            foreach ($changedHillClimbing['successors_generated'] as $index => $succ) {
                                                $solution = implode(', ', $succ['solution']);
                                                $evaluation = $succ['evaluation'];
                                                $outputChanged .= "Sucessor #" . ($index + 1) . ":\n";
                                                $outputChanged .= "  Solução: [{$solution}]\n";
                                                $outputChanged .= "  Avaliação: {$evaluation}\n\n";
                                            }
                                            if (isset($changedHillClimbing['final_solution'], $changedHillClimbing['final_evaluation'])) {
                                                $finalSolution = implode(', ', $changedHillClimbing['final_solution']);
                                                $finalEvaluation = $changedHillClimbing['final_evaluation'];
                                                $outputChanged .= "Solução Final:\n";
                                                $outputChanged .= "  Solução: [{$finalSolution}]\n";
                                                $outputChanged .= "  Avaliação: {$finalEvaluation}\n";
                                            }
                                        } else {
                                            $outputChanged .= "Nenhum dado disponível para Subida de Encosta Alterada.";
                                        }

                                        // Têmpera Simulada
                                        $simulatedAnnealing = $data['results']['simulatedAnnealing'] ?? null;
                                        $outputAnnealing = '';
                                        if ($simulatedAnnealing && isset($simulatedAnnealing['successors_generated'])) {
                                            $outputAnnealing .= "Têmpera Simulada:\n";
                                            $outputAnnealing .= "Número de sucessores gerados: " . count($simulatedAnnealing['successors_generated']) . "\n";
                                            $outputAnnealing .= "Temperatura Inicial: " . ($data['initial_temp'] ?? 0) . "\n";
                                            $outputAnnealing .= "Temperatura Final: " . ($data['final_temp'] ?? 0) . "\n";
                                            $outputAnnealing .= "Fator Redutor: " . ($data['reducing_factor'] ?? 0) . "\n\n";
                                            foreach ($simulatedAnnealing['successors_generated'] as $index => $succ) {
                                                $solution = implode(', ', $succ['solution']);
                                                $evaluation = $succ['evaluation'];
                                                $outputAnnealing .= "Sucessor #" . ($index + 1) . ":\n";
                                                $outputAnnealing .= "  Solução: [{$solution}]\n";
                                                $outputAnnealing .= "  Avaliação: {$evaluation}\n\n";
                                            }
                                            if (isset($simulatedAnnealing['final_solution'], $simulatedAnnealing['final_evaluation'])) {
                                                $finalSolution = implode(', ', $simulatedAnnealing['final_solution']);
                                                $finalEvaluation = $simulatedAnnealing['final_evaluation'];
                                                $outputAnnealing .= "Solução Final:\n";
                                                $outputAnnealing .= "  Solução: [{$finalSolution}]\n";
                                                $outputAnnealing .= "  Avaliação: {$finalEvaluation}\n";
                                            }
                                        } else {
                                            $outputAnnealing .= "Nenhum dado disponível para Têmpera Simulada.";
                                        }
                                    @endphp

                                    <div style="flex: 1; min-width: 300px;">
                                        <p><strong>Subida de Encosta</strong></p>
                                        <textarea readonly style="width: 100%; height: 300px; resize: vertical; overflow: auto;">{{ $outputHill }}</textarea>
                                    </div>
                                    <div style="flex: 1; min-width: 300px;">
                                        <p><strong>Subida de Encosta Alterada</strong></p>
                                        <textarea readonly style="width: 100%; height: 300px; resize: vertical; overflow: auto;">{{ $outputChanged }}</textarea>
                                    </div>
                                    <div style="flex: 1; min-width: 300px;">
                                        <p><strong>Têmpera Simulada</strong></p>
                                        <textarea readonly style="width: 100%; height: 300px; resize: vertical; overflow: auto;">{{ $outputAnnealing }}</textarea>
                                    </div>
                                </div>
                            @endif
                            </div>
                        </div>  
                        <div class="card-action">
                            <div class="center-align">
                                <a href="{{ route('home.base.exportImprove') }}?data={{ urlencode(json_encode($data)) }}" class="btn orange darken-2" target="_blank">Exportar PDF</a>
                            </div>  
                        </div>
                    @else
                        <p class="center-align">Nenhum resultado disponível. Execute um método de busca local para visualizar os resultados.</p>
                    @endif
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
                        <center><p><strong>Subida de Encosta</strong></p></center>
                        <div class="input-field col s12 l4 offset-l4 offset-m3">
                            <input type="text" name="successors_num_se" id="successors_num_se" value="{{ $successors_num_se ?? '' }}" @error('successors_num_se') is-invalid @enderror/>
                            <label for="successors_num_se">Máximo de Iterações (Sucessores)</label>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="row">
                        <br>
                        <center><p><strong>Subida de Encosta Alterada</strong></p></center>
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
                        <center><p><strong>Têmpera Simulada</strong></p></center>
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