@extends('layout')

@section('content')
    <center>
        <h1>Tela Incial</h1>
    </center>


    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title center-align">{{ __('Menu Principal') }}</span>
                </div>
                <div class="card-action">
                    <div class="row center-align">
                        <div class="col s12">
                            <a href="{{ route('home.base.index') }}" class="waves-effect waves-light btn-large orange darken-2">
                                <i class="material-icons right">gamepad</i><i class="material-icons left">gamepad</i>{{ __('Métodos Básicos') }}
                            </a>
                        </div>
                    </div>
                    <div class="row center-align">
                        <div class="col s12">
                            <a href="{{ route('home.genetic.index') }}" class="waves-effect waves-light btn-large red darken-2">
                                <i class="material-icons right">fingerprint</i><i class="material-icons left">fingerprint</i>{{ __('Algoritmos Genéticos') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <ul class="collapsible popout">
                        <li>
                            <div class="collapsible-header"><i class="material-icons">work</i><strong>Problema da Mochila</strong></div>
                            <div class="collapsible-body">
                                <p>Problema clássico de otimização, onde o objetivo é maximizar o valor dos itens em uma mochila com capacidade limitada.</p>
                                <ul class="browser-default">
                                    <li><strong>Capacidade da Mochila:</strong> Limite máximo de peso que a mochila pode carregar.</li>
                                    <li><strong>Quantidade de Itens:</strong> Número total de itens disponíveis para serem colocados na mochila.</li>
                                    <li><strong>Valor dos Itens:</strong> Valor associado a cada item, que contribui para o valor total da mochila.</li>
                                    <li><strong>Peso dos Itens:</strong> Peso associado a cada item, que deve respeitar a capacidade da mochila.</li>
                                    <li><strong>Objetivo:</strong> Selecionar os itens que maximizam o valor total sem exceder a capacidade da mochila.</li>
                                    <li><strong>Restrições:</strong> Itens podem ter restrições de peso, valor ou dependência, que devem ser consideradas na seleção.</li>
                                    <li><strong>Aplicações:</strong> Logística, finanças, planejamento de recursos, etc.</li>
                                    <li><strong>Exemplo:</strong> Se a mochila tem capacidade de 50 kg e os itens têm pesos e valores variados, o objetivo é escolher os itens que maximizam o valor total sem ultrapassar os 50 kg.</li>
                                </ul>
                            </div>
                        </li>

                        <li>
                            <div class="collapsible-header"><i class="material-icons">people</i><strong>Desenvolvedores</strong></div>
                            <div class="collapsible-body">
                                <ul class="browser-default">
                                    <li>Wellington de Elias Rodrigues</li>
                                    <li>Willians Henrique Santos Silva</li>
                                </ul>
                            </div>
                        </li>

                        <li>
                            <div class="collapsible-header"><i class="material-icons">school</i><strong>Instituição e Professor Orientador</strong></div>
                            <div class="collapsible-body">
                                <ul class="browser-default">
                                    <li><strong>Instituição:</strong> FATEC - Faculdade de Tecnologia de São Paulo</li>
                                    <li><strong>Curso:</strong> Análise e Desenvolvimento de Sistemas</li>
                                    <li><strong>Professor Orientador:</strong> Prof. Dr. Luis Fernando de Almeirda</li>
                                    <li><strong>Data:</strong> 1/2025</li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
@endsection