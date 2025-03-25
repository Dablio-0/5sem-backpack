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
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection