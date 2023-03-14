@extends('layouts.app')

@section('content')
    <div class="container h-100C">
        <div class="row justify-content-center h-100">
            <div class="col-md-8 align-self-center">
                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
                <div class="card bg-p shadow-block">
                    <div class="card-header bg-s">{{__('Modifier un utilisateur')}}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('userUpdate',['id' => $user->id]) }}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="row mb-3">
                                <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('Prenom') }}</label>

                                <div class="col-md-6">
                                    <input id="first_name" type="text" placeholder="{{$user->first_name}}" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" autocomplete="first_name" autofocus>

                                    @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="last_name" class="col-md-4 col-form-label text-md-end">{{ __('Nom') }}</label>

                                <div class="col-md-6">
                                    <input id="last_name" type="text" placeholder="{{$user->last_name}}" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" autocomplete="last_name" autofocus>

                                    @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Addresse mail') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"  placeholder="{{$user->email}}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                            <div class="row mb-3">
                                <label for="id_role" class="col-md-4 col-form-label text-md-end">{{ __('Role de l\'utilisateur ( '.$user->role.' )') }}</label>

                                <div class="col-md-6">
                                    <select id="id_role" class="form-select @error('id_role') is-invalid @enderror" aria-label="Default select example" name="id_role">
                                        <option value="" class="text-capitalize">Sélectionner un role</option>
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}" class="text-capitalize">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('id_role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            @endif
                            <div class="row mb-3">
                                <label for="statut" class="col-md-4 col-form-label text-md-end">{{ __('Statut') }}</label>

                                <div class="col-md-6">
                                    <select id="statut" class="form-select @error('statut') is-invalid @enderror" aria-label="Default select example" name="statut">
                                        <option value="" class="text-capitalize">Sélectionner un statut</option>
                                        <option value="1" class="text-capitalize">Activer</option>
                                        <option value="0" class="text-capitalize">Désactiver</option>
                                    </select>
                                    @error('statut')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    @if(str_contains(url()->previous(), 'edit') === false && str_contains(url()->previous(), 'create') === false)
                                        @php
                                            session()->put('urlP', url()->previous());
                                        @endphp
                                    @elseif(url()->previous() !== url()->current())
                                        @php
                                            session()->put('urlP', '/users');
                                        @endphp
                                    @endif
                                    <a href="{{str_replace(url('/'), '', session()->get('urlP'))}}" class="btn btn-danger">
                                        {{__('Retour')}}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Continuer') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('js/bootstrap.bundle.js')}}"></script>
@endsection
