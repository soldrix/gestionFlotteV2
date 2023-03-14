@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center h-100C">
            <div class="col-md-8 d-flex align-self-center flex-column h-50">
                <div class="card bg-p shadow-block">
                    <div class="card-header bg-s">{{ __('Réactivation de compte') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('reactivateAccount',['id' => $user->id])}}">
                            @csrf
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email : ') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('message') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Mot de passe : ') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('message') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    @error ('message')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <a href="{{url('/login')}}" >Retour ?</a>
                                    <a href="{{url('/delete-user?email='.$user->email)}}" class="btn btn-danger mx-1">Supprimer votre compte ?</a>

                                    <button type="submit" class="btn btn-primary mx-2">
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
    <script src="{{asset('js/bootstrap.bundle.js')}}" defer></script>
@endsection
