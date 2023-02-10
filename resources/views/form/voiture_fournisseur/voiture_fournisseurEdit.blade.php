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
                    <div class="card-header bg-s">{{__('Modifier une voiture')}}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('updateVoiture_fournisseur',[$voiture->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="row mb-3">
                                <label for="type" class="col-md-4 col-form-label text-md-end">{{ __('Type de voiture :') }}</label>
                                <div class="col-md-6">
                                    <input id="type" type="text" placeholder="{{$voiture->type}}" class="form-control @error('type') is-invalid @enderror" name="type" value="{{ old('type') }}"  autocomplete="type" autofocus>
                                    @error ('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nbPlace" class="col-md-4 col-form-label text-md-end">{{ __('Nombre de siège :') }}</label>
                                <div class="col-md-6">
                                    <input id="nbPlace" type="text" placeholder="{{$voiture->nbPlace}}" class="form-control @error('nbPlace') is-invalid @enderror" name="nbPlace" value="{{ old('nbPlace') }}"  autocomplete="nbPlace" autofocus>
                                    @error ('nbPlace')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nbPorte" class="col-md-4 col-form-label text-md-end">{{ __('Nombre de porte :') }}</label>
                                <div class="col-md-6">
                                    <input id="nbPorte" type="text" placeholder="{{$voiture->nbPorte}}" class="form-control @error('nbPorte') is-invalid @enderror" name="nbPorte" value="{{ old('nbPorte') }}"  autocomplete="nbPorte" autofocus>
                                    @error ('nbPorte')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="model" class="col-md-4 col-form-label text-md-end">{{ __('Model :') }}</label>
                                <div class="col-md-6">
                                    <input id="model" type="text" placeholder="{{$voiture->model}}" class="form-control @error('model') is-invalid @enderror" name="model" value="{{ old('model') }}"  autocomplete="model" autofocus>
                                    @error ('model')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="marque" class="col-md-4 col-form-label text-md-end">{{ __('Marque :') }}</label>
                                <div class="col-md-6">
                                    <input id="marque" type="text" placeholder="{{$voiture->marque}}" class="form-control @error('marque') is-invalid @enderror" name="marque" value="{{ old('marque') }}"  autocomplete="marque" autofocus>
                                    @error ('marque')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="carburant" class="col-md-4 col-form-label text-md-end">{{ __('Type de carburant :') }}</label>
                                <div class="col-md-6">
                                    <input id="carburant" type="text" placeholder="{{$voiture->carburant}}" class="form-control @error('carburant') is-invalid @enderror" name="carburant" value="{{ old('carburant') }}"  autocomplete="carburant" autofocus>
                                    @error ('carburant')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="puissance" class="col-md-4 col-form-label text-md-end">{{ __('Puissance :') }}</label>
                                <div class="col-md-6">
                                    <input id="puissance" type="text" placeholder="{{$voiture->puissance}}" class="form-control @error('puissance') is-invalid @enderror" name="puissance" value="{{ old('puissance') }}"  autocomplete="puissance" autofocus>
                                    @error ('puissance')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="statut" class="col-md-4 col-form-label text-md-end">{{ __('Statut du véhicule :') }}</label>
                                <div class="col-md-6">
                                    <select id="statut" class="form-select @error('statut') is-invalid @enderror" aria-label="Default select example" name="statut">
                                        <option value="">Selectionner un statut</option>
                                        <option value="1">Disponible</option>
                                        <option value="0">Indisponible</option>
                                    </select>
                                    @error ('statut')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="voitureID" class="col-md-4 col-form-label text-md-end">{{ __('Fournisseur :') }}</label>
                                @if(count(json_decode($fournisseurs)) > 0)
                                    <div class="col-md-6">
                                        <select id="id_fournisseur" class="form-select @error('id_fournisseur') is-invalid @enderror" aria-label="Default select example" name="id_fournisseur">
                                            <option value="">Selectionner un fournisseur</option>
                                            @foreach($fournisseurs as $datas)
                                                <option value="{{$datas->id}}">{{$datas->name.' '.$datas->email  }}</option>
                                            @endforeach
                                        </select>
                                        @error ('id_fournisseur')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <p class="text-danger">Aucun founisseur, créer un fournisseur pour pouvoir créer un véhicule.</p>
                                    </div>
                                @endif
                            </div>

                            <div class="row mb-3">
                                <label for="image" class="col-md-4 col-form-label text-md-end">{{ __('Image :') }}</label>
                                <div class="col-md-6">
                                    <input id="image" type="file" accept="image/png, image/jpeg, image/jpg" class="form-control @error('image') is-invalid @enderror" name="image" value="{{ old('image') }}"  autocomplete="image" autofocus>
                                    @error ('image')
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
                                            session()->put('urlP', '/voitures-fournisseur');
                                        @endphp
                                    @endif

                                    <a href="{{str_replace(url('/'), '', session()->get('urlP'))}}" class="btn btn-danger">
                                        Retour
                                    </a>
                                    @if(count(json_decode($fournisseurs)) > 0)
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
