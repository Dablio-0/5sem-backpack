<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name="description" content="">

        <title>
            @if(View::hasSection('title'))
                @yield('title')
            @else
                Título Padrão
            @endif
        </title>

        
        <!-- Materialize CSS - CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
        <!-- Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        <script src="{{ asset('assets/js/home.script.js') }}"></script>
        <!-- Material Icons-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    </head>
    <body>        
        <header>
          <nav class="orange grey lighten-4">
                <div class="nav-wrapper container">
                    <a href="{{ route('home.index') }}" class="btn orange darken-2 white-text waves-effect waves-light">
                        Home
                    </a>
                </div>
            </nav>
        </header>
        <main>
            <div class="container">
                @yield('content')
            </div>
        </main>
        <footer>

        </footer>
    </body>
</html>