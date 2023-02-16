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
                    <div class="card-header bg-s">{{__('Modifier une assurance')}}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('updateAssurance', ['id' => $assurance->id]) }}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="row mb-3">
                                <label for="nom" class="col-md-4 col-form-label text-md-end">{{ __('Nom de l\'assurance :') }}</label>
                                <div class="col-md-6">
                                    <input id="nom" type="text" placeholder="{{$assurance->nom}}" class="form-control @error('nom') is-invalid @enderror" name="nom" value="{{ old('nom') }}" autocomplete="nom" autofocus>
                                    @error ('nom')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="DateDebut" class="col-md-4 col-form-label text-md-end">{{ __('Date de debut : ').'( '.date('d/m/Y', strtotime($assurance->DateDebut)).' )' }}</label>
                                <div class="col-md-6">
                                    <input id="DateDebut" type="date" class="form-control @error('DateDebut') is-invalid @enderror" name="DateDebut" value="{{ old('DateDebut') }}" autocomplete="DateDebut" autofocus>
                                    @error ('DateDebut')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="DateFin" class="col-md-4 col-form-label text-md-end">{{ __('Date de fin : ').'( '.date('d/m/Y', strtotime($assurance->DateFin)).' )' }}</label>
                                <div class="col-md-6">
                                    <input id="DateFin" type="date" class="form-control @error('DateFin') is-invalid @enderror" name="DateFin" value="{{ old('DateFin') }}" autocomplete="DateFin" autofocus>
                                    @error ('DateFin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="frais" class="col-md-4 col-form-label text-md-end">{{ __('Frais total :') }}</label>
                                <div class="col-md-6">
                                    <input id="frais" type="text" placeholder="{{$assurance->frais}}" class="form-control @error('frais') is-invalid @enderror" name="frais" value="{{ old('frais') }}" autocomplete="frais" autofocus>
                                    @error ('frais')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="voitureID" class="col-md-4 col-form-label text-md-end">{{ __('Voiture :') }}</label>
                                <div class="col-md-6">
                                    <select id="voitureId" class="form-select @error('id_voiture') is-invalid @enderror" aria-label="Default select example" name="id_voiture">
                                        <option value="">Selectionner une voiture</option>
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
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    @if(str_contains(url()->previous(), 'edit') === false && str_contains(url()->previous(), 'create') === false)
                                        @php
                                            session()->put('urlP', url()->previous());
                                        @endphp
                                    @elseif(url()->previous() !== url()->current())
                                        @php
                                            session()->put('urlP', '/assurances');
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
