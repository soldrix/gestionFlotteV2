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
                    <div class="card-header bg-s">{{__('Ajouter une agence')}}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('createAgence') }}">
                            @csrf
                            <p class="text-end">
                                <span class="text-danger">*</span>
                                champs obligatoires
                            </p>
                            <div class="row mb-3">
                                <label for="ville" class="col-md-4 col-form-label text-md-end">{{ __('Ville ')}}<span class="text-danger">*</span>{{__(' :')}}</label>
                                <div class="col-md-6">
                                    <input id="ville" type="text" class="form-control @error('ville') is-invalid @enderror" name="ville" value="{{ old('ville') }}" required autocomplete="ville" autofocus>
                                    @error ('ville')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="rue" class="col-md-4 col-form-label text-md-end">{{ __('Rue ')}}<span class="text-danger">*</span>{{__(' :')}}</label>
                                <div class="col-md-6">
                                    <input id="rue" type="text" class="form-control @error('rue') is-invalid @enderror" name="rue" value="{{ old('rue') }}" required autocomplete="rue" autofocus>
                                    @error ('rue')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="codePostal" class="col-md-4 col-form-label text-md-end">{{ __('Code postal ')}}<span class="text-danger">*</span>{{__(' :')}}</label>
                                <div class="col-md-6">
                                    <input id="codePostal" type="text" class="form-control @error('codePostal') is-invalid @enderror" name="codePostal" value="{{ old('codePostal') }}" required autocomplete="codePostal" autofocus>
                                    @error ('codePostal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="userID" class="col-md-4 col-form-label text-md-end">{{ __('utilisateurs :') }}</label>
                                <div class="col-md-6">
                                    <select id="userID" class="form-select @error('id_user') is-invalid @enderror" aria-label="Default select example" name="id_user">
                                        <option value="">Sélectionner un utilisateur</option>
                                        <option value="vide">Aucun utilisateur</option>
                                        @foreach($users as $datas)
                                            <option value="{{$datas->id}}">{{$datas->first_name.' '.$datas->last_name.' '.$datas->email}}</option>
                                        @endforeach
                                    </select>
                                    @error ('id_user')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    @if(str_contains(url()->previous(), 'edit') === false && str_contains(url()->previous(), 'create') === false)
                                        @php
                                            session()->put('urlP', url()->previous());
                                        @endphp
                                    @elseif(url()->previous() !== url()->current())
                                        @php
                                            session()->put('urlP', '/agences');
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
