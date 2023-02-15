@extends('layouts.app')
@section('content')
<div class="container d-flex flex-column pt-5">
    <div id="profilContent" class="w-100">

    </div>
<div class="col-8 d-flex align-self-center flex-column">
    <h2>Mon profil</h2>
    <div class="bg-s shadow-block p-3">
            <h3> <span class="first_name_text">{{$user->first_name}}</span> <span>{{$user->last_name}}</span></h3>

            <div class="bg-p p-3">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <p class="m-0">Prenom <br> <span class="first_name_text">{{$user->first_name}}</span></p>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal_first_name">Modifier</button>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <p class="m-0">Nom <br> {{$user->last_name}}</p>
                    <button class="btn btn-outline-primary" href="/profil/edit/{{$user->id}}">Modifier</button>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="m-0">Email <br> {{$user->email}}</p>
                    <button class="btn btn-outline-primary" href="/profil/edit/{{$user->id}}">Modifier</button>
                </div>
            </div>
    </div>
    <h3 class="border-1 border-top border-white mt-5 pt-5">Mot de passe</h3>
    <a class="btn btn-outline-primary h-50 w-fit m-2" href="/UpdatePassword">
        modifier mot de passe
    </a>

            <a class="btn btn-outline-primary h-50 align-self-center m-2" >
                modifier mon profil
            </a>

            <div class="d-flex justify-content-center w-100">
                <button class="btn btn-outline-danger delButton" data-voiture="{{\Illuminate\Support\Facades\Auth::user()->id}}">suppression du compte</button>
            </div>
    </div>


</div>

<!-- Modal -->
<div class="modal fade" id="modal_first_name" tabindex="-1" aria-labelledby="first_nameModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-s shadow-block">
            <div class="modal-header">
                <h5 class="modal-title" id="first_nameModalLabel">Modifier le prénom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-p">
                <div class="container-fluid">
                    <input type="hidden" id="id_user" value="{{$user->id}}">
                    <div class="row mb-3">
                        <label for="first_name" class="col-md-4 col-form-label text-md-end">Prenom</label>
                        <div class="col-md-6">
                            <input id="first_name" type="text" class="form-control" name="first_name" required autocomplete="first_name" autofocus>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="old_password" class="col-md-4 col-form-label text-md-end">Mot de passe</label>
                        <div class="col-md-6">
                            <input id="old_password" type="password" class="form-control" name="old_password" required autocomplete="old_password" autofocus>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end p-2">
                    <button type="button" class="btn btn-secondary m-1 btn_close_modal" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary m-1" id="btn_first_name">Continuer</button>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="{{ asset('js/profil.js') }}" defer></script>
@endsection
