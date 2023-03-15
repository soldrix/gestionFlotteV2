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
                    <div class="card-header bg-s">{{__('Modifier un fournisseur')}}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('updateFournisseur',['id' => $fournisseur->id]) }}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Nom du fournissseur :') }}</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" placeholder="{{$fournisseur->name}}" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
                                    @error ('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email du fournissseur :')}}</label>
                                <div class="col-md-6">
                                    <input id="email" type="text" placeholder="{{$fournisseur->email}}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>
                                    @error ('email')
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
                                            session()->put('urlP', '/fournisseurs');
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
