
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{asset('js/fontawesome.js')}}" defer></script>
    @if(\Illuminate\Support\Facades\Auth::user())
        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['admin', 'fournisseur']))
{{--            <script src="{{ asset('js/app.js') }}" defer></script>--}}
            <link href="{{ asset('css/dataTables.bootstrap5.css') }}" rel="stylesheet">
        @endif
@endif


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">

    <link href="{{ asset('css/CustomScrollbar.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar sticky-top navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                   Home
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @if(\Illuminate\Support\Facades\Auth::user())
                            <ul class="navbar-nav me-auto">
                                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['admin', 'fournisseur']))
                                <a href="{{url('/admin/voitures')}}" class="mx-2 text-dark text-decoration-none">Voitures</a>
                                @endif
                                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['admin']))
                                    <a href="{{url('/admin/entretiens')}}" class="mx-2 text-dark text-decoration-none">Entretiens</a>
                                    <a href="{{url('/admin/assurances')}}" class="mx-2 text-dark text-decoration-none">Assurances</a>
                                    <a href="{{url('/admin/reparations')}}" class="mx-2 text-dark text-decoration-none">Reparations</a>
                                    <a href="{{url('/admin/consommations')}}" class="mx-2 text-dark text-decoration-none">Consommations</a>
                                    <a href="{{url('/admin/agences')}}" class="mx-2 text-dark text-decoration-none">Agences</a>
                                    <a href="{{url('/admin/locations')}}" class="mx-2 text-dark text-decoration-none">Locations</a>
                                    <a href="{{url('/admin/users')}}" class="mx-2 text-dark text-decoration-none">Utilisateurs</a>
                                    <a href="{{url('/admin/fournisseurs')}}" class="mx-2 text-dark text-decoration-none">Fournisseurs</a>
                                @endif
                            </ul>
                    @endif
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                @if(\Illuminate\Support\Facades\Auth::user())
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ \Illuminate\Support\Facades\Auth::user()->name }}
                                </a>
                                @endif
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{url('/profil')}}" role="button">Profil</a>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Déconnexion') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>
        <div class="toast-container position-absolute start-0 p-3 top-0 mt-5" >

            <!-- Then put toasts within -->
            <div id="saveToast" class="toast" role="alert">
                <div class="toast-header">
                    <strong class="me-auto">Enregistrement des données</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Les données ont été enregistrées
                </div>
            </div>
            <div id="toastSupp" class="toast" role="alert">
                <div class="toast-header">
                    <strong class="me-auto">Suppression données</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Les données ont été supprimé
                </div>
            </div>
            <div id="toastLocation" class="toast" role="alert">
                <div class="toast-header">
                    <strong class="me-auto">Location véhicule</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    La location a été réalié avec succès.
                </div>
            </div>
            <div id="toastAnnul" class="toast" role="alert">
                <div class="toast-header">
                    <strong class="me-auto">Annulation de location</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    La location a été annuler.
                </div>
            </div>

            <div id="toastUpdateProfil" class="toast" role="alert">
                <div class="toast-header">
                    <strong class="me-auto">Modification de données </strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Les données ont été modifié.
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" id="delModal" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Êtes-vous sûr de vouloir supprimer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary" id="btnDelModal">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>
