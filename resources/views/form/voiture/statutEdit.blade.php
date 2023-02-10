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
                    <div class="card-header bg-s">{{__('Modifier la voiture')}}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('updateStatut',['id'=> $voiture->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="row mb-3">
                                <label for="statut" class="col-md-4 col-form-label text-md-end">{{ __('Statut du véhicule :') }}</label>
                                <div class="col-md-6">
                                    <select id="statut" class="form-select @error('statut') is-invalid @enderror" aria-label="Default select example" name="statut">
                                        <option value="1" @if($voiture->statut === 1) selected @endif>Disponible</option>
                                        <option value="0" @if($voiture->statut === 0) selected @endif>Indisponible</option>
                                    </select>
                                    @error ('statut')
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
                                            session()->put('urlP', '/voitures');
                                        @endphp
                                    @endif

                                    <a href="{{str_replace(url('/'), '', session()->get('urlP'))}}" class="btn btn-danger">
                                        Retour
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
