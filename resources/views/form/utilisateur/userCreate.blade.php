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
                            <p class="text-end">
                                <span class="text-danger">*</span>
                                champs obligatoires
                            </p>
                            <div class="row mb-3">
                                <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('Prenom ')}}<span class="text-danger">*</span>{{__(' :')}}</label>

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
                                <label for="last_name" class="col-md-4 col-form-label text-md-end">{{ __('Nom ')}}<span class="text-danger">*</span>{{__(' :')}}</label>

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
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Adresse mail Address ')}}<span class="text-danger">*</span>{{__(' :')}}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                            <div class="row mb-3">
                                <label for="id_role" class="col-md-4 col-form-label text-md-end">{{ __('Role de l\'utilisateur ')}}<span class="text-danger">*</span>{{__(' :')}}</label>
                                <div class="col-md-6">
                                    <select id="id_role" class="form-select @error('id_role') is-invalid @enderror" aria-label="Default select example" name="id_role">
                                        <option value="">Sélectionner un role</option>
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
                                <label for="email_receiver" class="col-md-4 col-form-label text-md-end">{{ __('Envoyer l\'email à ') }}<span class="text-danger">*</span>{{__(' :')}}</label>

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
