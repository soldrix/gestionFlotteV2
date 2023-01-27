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
                    <div class="card-header">{{__('Ajouter une consommation')}}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('createConsommation') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="litre" class="col-md-4 col-form-label text-md-end">{{ __('Nombre de litre :') }}</label>
                                <div class="col-md-6">
                                    <input id="litre" type="text" class="form-control @error('litre') is-invalid @enderror" name="litre" value="{{ old('litre') }}" required autocomplete="litre" autofocus>
                                    @error ('litre')
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
                                <label for="voitureID" class="col-md-4 col-form-label text-md-end">{{ __('Voiture :') }}</label>
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
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <a href="/admin/consommations" class="btn btn-outline-danger">
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
