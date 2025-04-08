@extends('layout')

@section('content')
    <h1>Genetic</h1>
    <p>Welcome to our Genetic page!</p>

    <div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">{{ __('Filtros') }}</span>
                <form method="POST">
                    @csrf
                    <div class="row">
                        <div class="input-field col s12 l3 xl2">
                            <input type="text" name="age" id="age" value="{{ $age ?? '' }}" />
                            <label for="age">{{ __('Idade') }}</label>
                        </div>
                        <div class="input-field col s12 l3 xl2">
                            <input type="text" name="name" id="name" value="{{ $name ?? '' }}" />
                            <label for="name">{{ __('Nome') }}</label>
                        </div>
                        <div class="input-field col s12 l3 xl2">
                            <input type="text" name="city" id="city" value="{{ $city ?? '' }}"/>
                            <label for="city">{{ __('Cidade') }}</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" name="gender" id="gender" value="Male" />
                            <label for="gender">{{ __('Gênero') }}</label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection