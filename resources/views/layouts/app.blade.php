<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>BANCO DEV</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
        <style>
          .uper {
            margin-top: 40px;
          }
        </style> 

        <!-- Script -->
        <script src="{{ asset('js/app.js') }}" defer></script>
      </head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    BANCO DEV
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
    
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto"></ul>


                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                            <li><a class="nav-link" href="{{ route('register-user') }}">{{ __('Register') }}</a></li>
                        @else

                            @can('super-admin')                            
                              <li><a class="nav-link" href="{{ route('users.index') }}">Usuarios</a></li>
                               <li><a class="nav-link" href="{{ route('roles.index') }}">Roles</a></li>            
                            @endcan
                            
                            @canany(['account-manager'], \App\Models\Account::class)
                            <li><a class="nav-link" href="{{ route('accounts') }}">Cuentas</a></li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Transacciones Bancarias<span class="caret"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">                                        
                                    <a class="dropdown-item" href="{{ route('transferir') }}">Transferencia entre cuentas Propias</a>
                                    <a class="dropdown-item" href="{{ route('transferir_tereceros') }}">Transferencia a cuenta de Tereceros</a>
                                    <a class="dropdown-item" href="{{ route('matricular_cuenta') }}">Matricular Cuenta de Tercero</a>
                                    <a class="dropdown-item" href="{{ route('listar_transferencias') }}">Listar Transferencias</a>
                                </div>                                
                            </li>    
                            @endcanany                         

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">                                        
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
            @yield('content')
            </div>
        </main>
    </div>
</body>
</html>