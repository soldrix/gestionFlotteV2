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
                <div class="card">
                    <div class="card-header">{{__('Ajouter une location')}}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('createLocation') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="DateDebut" class="col-md-4 col-form-label text-md-end">{{ __('Date de debut :') }}</label>
                                <div class="col-md-6">
                                    <input id="DateDebut" type="date" class="form-control @error('DateDebut') is-invalid @enderror" name="DateDebut" value="{{ old('DateDebut') }}" required autocomplete="DateDebut" autofocus>
                                    @error ('DateDebut')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="DateFin" class="col-md-4 col-form-label text-md-end">{{ __('Date de fin :') }}</label>
                                <div class="col-md-6">
                                    <input id="DateFin" type="date" class="form-control @error('DateFin') is-invalid @enderror" name="DateFin" value="{{ old('DateFin') }}" required autocomplete="DateFin" autofocus>
                                    @error ('DateFin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="montant" class="col-md-4 col-form-label text-md-end">{{ __('Montant total :') }}</label>
                                <div class="col-md-6">
                                    <input id="montant" type="text" class="form-control @error('montant') is-invalid @enderror" name="montant" value="{{ old('montant') }}" required autocomplete="montant" autofocus>
                                    @error ('montant')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="voitureID" class="col-md-4 col-form-label text-md-end">{{ __('Voitures :') }}</label>
                                <div class="col-md-6">
                                    <select id="voitureId" class="form-select @error('id_voiture') is-invalid @enderror" aria-label="Default select example" name="id_voiture">
                                        <option value="null">Aucune voiture</option>
                                        @foreach($voitures as $datas)
                                            <option value="{{$datas->id}}">{{$datas->marque.' '.$datas->model.' '.$datas->immatriculation}}</option>
                                        @endforeach
                                    </select>
                                    @error ('id_voiture')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="userID" class="col-md-4 col-form-label text-md-end">{{ __('utilisateurs :') }}</label>
                                <div class="col-md-6">
                                    <select id="userId" class="form-select @error('id_user') is-invalid @enderror" aria-label="Default select example" name="id_user">
                                        <option value="null">Aucun utilisateur</option>
                                        @foreach($users as $datas)
                                            <option value="{{$datas->id}}">{{$datas->name.' '.$datas->email}}</option>
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
                                            session()->put('urlP', '/admin/locations/');
                                        @endphp
                                    @endif
                                    <a href="{{str_replace(url('/'), '', session()->get('urlP'))}}" class="btn btn-outline-danger">
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
@endsection
