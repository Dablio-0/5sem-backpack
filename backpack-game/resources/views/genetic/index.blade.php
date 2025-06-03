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
                <form method="POST" action="{{ route('home.genetic.improve') }}">
                    @csrf
                    <div class="card-content center-align">
                        <span class="card-title">Parâmetros Genéticos</span>
                        <div class="row">
                            <div class="input-field col s12 l6 center-align">
                                <input type="text" name="populationSize" id="populationSize" value="{{ $populationSize ?? '' }}" @error('populationSize') is-invalid @enderror/>
                                <label for="populationSize">Tamanho da População</label>
                            </div>
                            <div class="input-field col s12 l6 center-align">
                                <input type="text" name="cruzeTax" id="cruzeTax" value="{{ $cruzeTax ?? '' }}" @error('cruzeTax') is-invalid @enderror/>
                                <label for="cruzeTax">Taxa de Cruzamento</label>
                            </div>
                            <div class="input-field col s12 l6 center-align">
                                <input type="text" name="mutationTax" id="mutationTax" value="{{ $mutationTax ?? '' }}" @error('mutationTax') is-invalid @enderror/>
                                <label for="mutationTax">Taxa de Mutação</label>
                            </div>
                            <div class="input-field col s12 l6 center-align">
                                <input type="text" name="generationInterval" id="generationInterval" value="{{ $generationInterval ?? '' }}" @error('generationInterval') is-invalid @enderror/>
                                <label for="generationInterval">Intervalo de Geração</label>
                            </div>
                            <div class="input-field col s12 l6 center-align">
                                <input type="text" name="generationNum" id="generationNum" value="{{ $generationNum ?? '' }}" @error('generationNum') is-invalid @enderror/>
                                <label for="generationNum">Número de Gerações</label>
                            </div>
                        </div>
                        </div>
                        <div class="card-action">
                            <div class="center-align">
                                <input type="submit" value="Aplicar Genes" class="btn orange darken-2 " />
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
                    <span class="card-title">Visualização de Resultados - Algoritmos Genéticos</span>
                </div>
            </div>
        </div>
    </div>
@endsection