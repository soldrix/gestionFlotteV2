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
                    <div class="card-header">{{__('Modifier l\'agence')}}</div>
                    <div class="card-body">
                        <form method="post" action="{{ route('updateAgence',['id' => $agence->id]) }}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="row mb-3">
                                <label for="ville" class="col-md-4 col-form-label text-md-end">{{ __('Ville :') }}</label>
                                <div class="col-md-6">
                                    <input id="ville" type="text" placeholder="{{$agence->ville}}" class="form-control @error('ville') is-invalid @enderror" name="ville" value="{{ old('ville') }}" autocomplete="ville" autofocus>
                                    @error ('ville')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="rue" class="col-md-4 col-form-label text-md-end">{{ __('Rue :') }}</label>
                                <div class="col-md-6">
                                    <input id="rue" type="text" placeholder="{{$agence->rue}}" class="form-control @error('rue') is-invalid @enderror" name="rue" value="{{ old('rue') }}" autocomplete="rue" autofocus>
                                    @error ('rue')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="codePostal" class="col-md-4 col-form-label text-md-end">{{ __('Code postal :') }}</label>
                                <div class="col-md-6">
                                    <input id="codePostal" type="text" placeholder="{{$agence->codePostal}}" class="form-control @error('codePostal') is-invalid @enderror" name="codePostal" value="{{ old('codePostal') }}" autocomplete="codePostal" autofocus>
                                    @error ('codePostal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    @php
                                        if(url()->previous() !== url()->current()){
                                              session()->put('urlP', url()->previous());
                                          }
                                    @endphp
                                    <a href="{{(session()->get('urlP')) ? str_replace(url('/'), '', session()->get('urlP')) : '/admin/agence/'.$agence->id}}" class="btn btn-outline-danger">
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
