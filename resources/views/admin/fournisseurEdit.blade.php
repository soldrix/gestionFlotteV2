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
                    <div class="card-header">{{__('Modifier un fournisseur')}}</div>
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
                                <label for="userId" class="col-md-4 col-form-label text-md-end">{{ __('Utilisateur :') }}</label>
                                <div class="col-md-6">
                                    <select id="userId" class="form-select @error('id_users') is-invalid @enderror" aria-label="Default select example" name="id_users">
                                        <option value="">Selectionner un fournisseur</option>
                                        @foreach($users as $datas)
                                            @if($fournisseur->id_users === $datas->id)
                                            <option value="{{$datas->id}}" selected>{{$datas->name.' '.$datas->email}}</option>
                                            @else
                                            <option value="{{$datas->id}}">{{$datas->name.' '.$datas->email}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error ('id_users')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <a href="/admin/fournisseurs" class="btn btn-outline-danger">
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
