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
                <div class="card-content center-align">
                    <p class="flow-text">
                        <ul>
                            <li>
                                <strong>Problema da Mochila</strong>: 
                                Problema clássico de otimização, onde o objetivo é maximizar o valor dos itens em uma mochila com capacidade limitada.
                                <ul>
                                    <li>Capacidade da Mochila: Limite máximo de peso que a mochila pode carregar.</li>
                                    <li>Quantidade de Itens: Número total de itens disponíveis para serem colocados na mochila.</li>
                                    <li>Valor dos Itens: Valor associado a cada item, que contribui para o valor total da mochila.</li>
                                    <li>Peso dos Itens: Peso associado a cada item, que deve respeitar a capacidade da mochila.</li>
                                    <li>Objetivo: Selecionar os itens que maximizam o valor total sem exceder a capacidade da mochila.</li>
                                    <li>Restrições: Itens podem ter restrições de peso, valor ou dependência, que devem ser consideradas na seleção.</li>
                                    <li>Aplicações: O problema da mochila é amplamente utilizado em áreas como logística, finanças e planejamento de recursos.</li>
                                    <li>Exemplo: Se a mochila tem capacidade de 50 kg e os itens têm pesos e valores variados, o objetivo é escolher os itens que maximizam o valor total sem ultrapassar os 50 kg.</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Desenvolvido por:</strong>
                                <ul>
                                    <li>Nome: Wellington de Elias Rodrigues</li>
                                </ul>
                                <ul>
                                    <li>Nome: Willians Henrique Santos Silva</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Instituição e Professor Orientador:</strong>
                                <ul>
                                    <li>Instituição: FATEC - Faculdade de Tecnologia de São Paulo</li>
                                    <li>Curso: Análise e Desenvolvimento de Sistemas</li>
                                    <li>Professor Orientador: Prof. Dr. Luis Fernando de Almeirda</li>
                                    <li>Data: 1/2025</li>
                                </ul>
                            </li>
                        </ul>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection