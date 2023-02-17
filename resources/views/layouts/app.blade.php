
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Gestion Flotte') }}</title>

    <!-- Scripts -->
    <script src="{{asset('js/fontawesome.js')}}" defer></script>
    @if(\Illuminate\Support\Facades\Auth::user())
        @if(! \Illuminate\Support\Facades\Auth::user()->hasRole('user'))
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
        <nav class="navbar sticky-top navbar-expand-md navbar-light bg-nav shadow-sm">
            <div class="container">
                @if(\Illuminate\Support\Facades\Auth::user())
                <a class="navbar-brand text-white" href="{{ url('/home') }}">
                    Home
                </a>
                @endif
                <button class="navbar-toggler border-white text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <i class="fa-solid fa-bars fa-1x"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @if(\Illuminate\Support\Facades\Auth::user())
                            <ul class="navbar-nav me-auto align-items-center">

                                <li class="nav-item dropdown d-none d-md-flex">
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['admin', 'responsable auto']))
                                            <a href="#" class="mx-2 nav-link dropdown-toggle text-white" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                                Pages
                                            </a>
                                    @endif
                                    <div class="dropdown-menu dropdown-menu-end bg-s" aria-labelledby="navbarDropdown">
                                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['admin', 'responsable auto']))
                                            <a href="{{url('/voitures')}}" class="dropdown-item color-white">Voitures</a>
                                            <a href="{{url('/entretiens')}}" class="dropdown-item color-white">Entretiens</a>
                                            <a href="{{url('/assurances')}}" class="dropdown-item color-white">Assurances</a>
                                            <a href="{{url('/reparations')}}" class="dropdown-item color-white">Reparations</a>
                                            <a href="{{url('/consommations')}}" class="dropdown-item color-white">Consommations</a>
                                        @endif
                                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['admin']))
                                            <a href="{{url('/voitures-fournisseur')}}" class="dropdown-item color-white">Gestion véhicules fournisseur</a>
                                        @endif
                                    </div>
                                </li>


                                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['admin', 'responsable auto']))
                                    <a href="{{url('/voitures')}}" class="mb-1 text-white nav-link d-flex d-md-none">
                                        Voitures
                                    </a>
                                @endif
                                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['admin', 'responsable auto']))
                                    <a href="{{url('/entretiens')}}" class="mb-1 text-white nav-link d-flex d-md-none">Entretiens</a>
                                    <a href="{{url('/assurances')}}" class="mb-1 text-white nav-link d-flex d-md-none">Assurances</a>
                                    <a href="{{url('/reparations')}}" class="mb-1 text-white nav-link d-flex d-md-none">Reparations</a>
                                    <a href="{{url('/consommations')}}" class="mb-1 text-white nav-link d-flex d-md-none">Consommations</a>
                                @endif

                                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['responsable fournisseur']))
                                    <a href="{{url('/voitures-fournisseur')}}" class="dropdown-item color-white">Gestion véhicules fournisseur</a>
                                @endif

                                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['admin', 'responsable agence']))
                                    <li class="nav-item">
                                        <a href="{{url('/agences')}}" class="mx-2 text-white nav-link">Gestion agences</a>
                                    </li>
                                @endif
                                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['admin', 'chef agence']))
                                    <li class="nav-item">
                                        <a href="{{url('/chef-agence')}}" class="mx-2 text-white nav-link">Gestion agence</a>
                                    </li>
                                @endif
                                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['admin', 'secretaire']))
                                    <li class="nav-item">
                                        <a href="{{url('/locations')}}" class="mx-2 text-white nav-link">Locations</a>
                                    </li>
                                @endif
                                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['admin', 'responsable fournisseur']))
                                    <li class="nav-item">
                                        <a href="{{url('/fournisseurs')}}" class="mx-2 text-white nav-link">Fournisseurs</a>
                                    </li>
                                @endif
                                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['admin','RH']))
                                    <li class="nav-item">
                                        <a href="{{url('/users')}}" class="mx-2 text-white nav-link">Utilisateurs</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('/commandes')}}" class="mx-2 text-white nav-link">Commandes</a>
                                    </li>
                                @endif
                            </ul>
                    @endif
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->

                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('login') }}">{{ __('Connexion') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('register') }}">{{ __('Inscription ') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown ">
                                @if(\Illuminate\Support\Facades\Auth::user())
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-center text-white" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ \Illuminate\Support\Facades\Auth::user()->first_name }}
                                </a>
                                @endif
                                <div class="dropdown-menu dropdown-menu-end bg-s" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item color-white" href="{{url('/profil')}}" role="button">Profil</a>

                                    <a class="dropdown-item color-white" href="{{ route('logout') }}"
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
            <div id="toastSupp" class="toast bg-p shadow-block" role="alert">
                <div class="toast-header bg-s">
                    <strong class="me-auto color-white">Suppression données</strong>
                    <button type="button" class="btn"  data-bs-dismiss="toast" aria-label="Close"><i class="fa-solid fa-l fa-xmark color-white"></i></button>
                </div>
                <div class="toast-body">
                    Les données ont été supprimé
                </div>
            </div>
            <div id="toastDes" class="toast bg-p shadow-block" role="alert">
                <div class="toast-header bg-s">
                    <strong class="me-auto color-white">Désactivation utlisateur</strong>
                    <button type="button" class="btn"  data-bs-dismiss="toast" aria-label="Close"><i class="fa-solid fa-l fa-xmark color-white"></i></button>
                </div>
                <div class="toast-body">
                    L'utilisateur à été désactiver.
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" id="delModal" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-s shadow-block">
                    <div class="modal-header">
                        <h5 class="modal-title">Êtes-vous sûr de vouloir supprimer</h5>
                        <button type="button" class="btn text-white" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-xmark fa-lg"></i>
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary" id="btnDelModal">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>
    <div class="modal" tabindex="-1" id="desModal" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-s shadow-block">
                <div class="modal-header">
                    <h5 class="modal-title">Êtes-vous sûr de vouloir désactiver le compte ?</h5>
                    <button type="button" class="btn text-white" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark fa-lg"></i>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="btnDesModal">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
