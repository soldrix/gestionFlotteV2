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
                    <div class="card-header bg-s">{{__('Ajouter un utilisateur')}}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('userCreate') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('Prenom') }}</label>

                                <div class="col-md-6">
                                    <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>

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
                                    <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>

                                    @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Adresse mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="type" class="col-md-4 col-form-label text-md-end">{{ __('Type d\'utilisateur') }}</label>

                                <div class="col-md-6">
                                    <select id="type" class="form-select @error('type') is-invalid @enderror" aria-label="Default select example" name="type">
                                        @foreach($roles as $role)
                                            <option value="{{$role->name}}" class="text-capitalize">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email_receiver" class="col-md-4 col-form-label text-md-end">{{ __('Envoyer l\'email à : ') }}</label>

                                <div class="col-md-6">
                                    <input id="email_receiver" type="email" class="form-control @error('email_receiver') is-invalid @enderror" name="email_receiver" value="">

                                    @error('email_receiver')
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
