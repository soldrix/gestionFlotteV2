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
                    <div class="card-header">{{__('Modifier l\'entretien')}}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('updateEntretien',['id' => $entretien->id]) }}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="row mb-3">
                                <label for="type" class="col-md-4 col-form-label text-md-end">{{ __('Type d\'entretien :') }}</label>
                                <div class="col-md-6">
                                    <input id="type" type="text" placeholder="{{$entretien->type}}" class="form-control @error('type') is-invalid @enderror" name="type" value="{{ old('type') }}" autocomplete="type" autofocus>
                                    @error ('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nom" class="col-md-4 col-form-label text-md-end">{{ __('Nom du garage :') }}</label>
                                <div class="col-md-6">
                                    <input id="rue" type="text" placeholder="{{$entretien->nom}}" class="form-control @error('nom') is-invalid @enderror" name="nom" value="{{ old('nom') }}" autocomplete="nom" autofocus>
                                    @error ('nom')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="date" class="col-md-4 col-form-label text-md-end">{{ __('Date de l\'entretien :') }}</label>
                                <div class="col-md-6">
                                    <input id="date" type="date" placeholder="{{ date('d/m/Y', strtotime($entretien->date))}}" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date') }}" autocomplete="date" autofocus>
                                    @error ('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="montant" class="col-md-4 col-form-label text-md-end">{{ __('Montant total :') }}</label>
                                <div class="col-md-6">
                                    <input id="montant" type="text" placeholder="{{$entretien->montant}}" class="form-control @error('montant') is-invalid @enderror" name="montant" value="{{ old('montant') }}" autocomplete="montant" autofocus>
                                    @error ('montant')
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
                                        <option value="">Selectioner une voiture</option>
                                        <option value="vide">Aucune voiture</option>
                                        @foreach($voitures as $datas)
                                            @if($entretien->id_voiture === $datas->id)
                                                <option value="{{$datas->id}}" selected>{{$datas->marque.' '.$datas->model.' '.$datas->immatriculation}}</option>
                                            @else
                                                <option value="{{$datas->id}}">{{$datas->marque.' '.$datas->model.' '.$datas->immatriculation}}</option>
                                            @endif
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
                                <label for="note" class="col-md-4 col-form-label text-md-end">{{ __('Note supplémentaire :') }}</label>
                                <div class="col-md-6">
                                    <textarea id="note" type="text" placeholder="{{$entretien->note}}" class="form-control @error('note') is-invalid @enderror" name="note" value="{{ old('montant') }}"  autocomplete="montant" autofocus ></textarea>
                                    @error ('note')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    @if(str_contains(url()->previous(), 'edit') === false)
                                        @php
                                            session()->put('urlP', url()->previous());
                                        @endphp
                                    @elseif(url()->previous() !== url()->current())
                                        @php
                                            session()->put('urlP', '/admin/entretiens/');
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
