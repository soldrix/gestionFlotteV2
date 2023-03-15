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
                    <div class="card-header bg-s">{{__('Ajouter une location')}}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('createLocation') }}">
                            @csrf
                            <p class="text-end">
                                <span class="text-danger">*</span>
                                champs obligatoires
                            </p>
                            <div class="row mb-3">
                                <label for="DateDebut" class="col-md-4 col-form-label text-md-end">{{ __('Date de debut ') }}<span class="text-danger">*</span>{{__(' :')}}</label>
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
                                <label for="DateFin" class="col-md-4 col-form-label text-md-end">{{ __('Date de fin ') }}<span class="text-danger">*</span>{{__(' :')}}</label>
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
                                <label for="montant" class="col-md-4 col-form-label text-md-end">{{ __('Montant total ') }}<span class="text-danger">*</span>{{__(' :')}}</label>
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
                                <label for="voitureID" class="col-md-4 col-form-label text-md-end">{{ __('Voitures ')}}<span class="text-danger">*</span>{{__(' :')}}</label>
                                @if(count(json_decode($voitures)) > 0)
                                    <div class="col-md-6">
                                        <select id="voitureId" class="form-select @error('id_voiture') is-invalid @enderror" aria-label="Default select example" name="id_voiture">
                                            <option value="">Selectioner une voiture</option>
                                            <option value="vide">Aucune voiture</option>
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
                                @else
                                    <div class="col-md-6">
                                        <p class="text-danger">Aucune voiture, créer une voiture pour pouvoir créer une location.</p>
                                    </div>
                                @endif
                            </div>

                            <div class="row mb-3">
                                <label for="userID" class="col-md-4 col-form-label text-md-end">{{ __('utilisateurs ')}}<span class="text-danger">*</span>{{__(' :')}}</label>
                                <div class="col-md-6">
                                    <select id="userId" class="form-select @error('id_user') is-invalid @enderror" aria-label="Default select example" name="id_user">
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
                                            session()->put('urlP', '/locations');
                                        @endphp
                                    @endif
                                    <a href="{{str_replace(url('/'), '', session()->get('urlP'))}}" class="btn btn-danger">
                                        {{__('Retour')}}
                                    </a>
                                    @if(count(json_decode($voitures)) > 0)
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Continuer') }}
                                        </button>
                                    @endif
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
