@extends('layouts.app')
@section('content')
<div class="container d-flex flex-column pt-5">
    <div id="profilContent" class="w-100">

    </div>
<div class="col-8 d-flex align-self-center flex-column">
    <h2>Mon profil</h2>
    <div class="bg-s shadow-block p-3">
            <h3> <span class="first_name_text">{{$user->first_name}}</span> <span class="last_name_text">{{$user->last_name}}</span></h3>

            <div class="bg-p p-3">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <p class="m-0">Prenom <br> <span class="first_name_text">{{$user->first_name}}</span></p>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal_first_name">Modifier</button>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <p class="m-0">Nom <br> <span class="last_name_text">{{$user->last_name}}</span></p>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal_last_name">Modifier</button>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="m-0">Email <br> <span class="email_text">{{$user->email}}</span></p>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal_email">Modifier</button>
                </div>
            </div>
    </div>
    <h3 class="border-1 border-top border-white mt-5 pt-5">Mot de passe</h3>
    <a class="btn btn-outline-primary h-50 w-fit m-2" data-bs-toggle="modal" data-bs-target="#modal_password">
        modifier mot de passe
    </a>



            <div class="d-flex justify-content-center w-100">
                <button class="btn btn-outline-danger delButton" data-voiture="{{\Illuminate\Support\Facades\Auth::user()->id}}">suppression du compte</button>
            </div>
    </div>


</div>

<!-- Modal first name -->
<div class="modal fade" id="modal_first_name" tabindex="-1" aria-labelledby="first_nameModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg mt-15p">
            <div class="alert alert-success d-none">
            </div>
            <div class="modal-content bg-s shadow-block">
                <div class="modal-header">
                    <h5 class="modal-title" id="first_nameModalLabel">Modifier le prénom</h5>
                </div>
                <div class="modal-body bg-p">
                    <div class="container-fluid">
                        <input type="hidden" class="id_user" value="{{$user->id}}">
                        <div class="row mb-3">
                            <label for="first_name" class="col-md-4 col-form-label text-md-end">Prenom</label>
                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control fn_input" name="first_name" required autocomplete="first_name" autofocus>
                                <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="old_password" class="col-md-4 col-form-label text-md-end">Mot de passe</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control old_password fn_input" name="old_password" required autocomplete="old_password" autofocus>
                                <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end p-2">
                        <button type="button" class="btn btn-secondary m-1 btn_close_modal" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary m-1" id="btn_first_name">Continuer</button>
                    </div>
                </div>

            </div>
    </div>
</div>
<!-- Modal last name -->
<div class="modal fade" id="modal_last_name" tabindex="-1" aria-labelledby="last_nameModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg mt-15p">
        <div class="alert alert-success d-none">
        </div>
        <div class="modal-content bg-s shadow-block">
            <div class="modal-header">
                <h5 class="modal-title" id="last_nameModalLabel">Modifier le nom</h5>
            </div>
            <div class="modal-body bg-p">
                <div class="container-fluid">
                    <input type="hidden" class="id_user" value="{{$user->id}}">
                    <div class="row mb-3">
                        <label for="last_name" class="col-md-4 col-form-label text-md-end">Nom</label>
                        <div class="col-md-6">
                            <input id="last_name" type="text" class="form-control ln_input" name="last_name" required autocomplete="last_name" autofocus>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="old_password" class="col-md-4 col-form-label text-md-end">Mot de passe</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control old_password ln_input" name="old_password" required autocomplete="old_password" autofocus>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end p-2">
                    <button type="button" class="btn btn-secondary m-1 btn_close_modal" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary m-1" id="btn_last_name">Continuer</button>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Modal email -->
<div class="modal fade" id="modal_email" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg mt-15p">
        <div class="alert alert-success d-none">
        </div>
        <div class="modal-content bg-s shadow-block">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">Modifier l'adresse mail</h5>
            </div>
            <div class="modal-body bg-p">
                <div class="container-fluid">
                    <input type="hidden" class="id_user" value="{{$user->id}}">
                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-end">Adresse mail</label>
                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control e_input" name="email" required autocomplete="email" autofocus>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="old_password" class="col-md-4 col-form-label text-md-end">Mot de passe</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control old_password e_input" name="old_password" required autocomplete="old_password" autofocus>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end p-2">
                    <button type="button" class="btn btn-secondary m-1 btn_close_modal" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary m-1" id="btn_email">Continuer</button>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Modal password -->
<div class="modal fade" id="modal_password" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg mt-15p">
        <div class="alert alert-success d-none">
        </div>
        <div class="modal-content bg-s shadow-block">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel">Modifier le mot de passe</h5>
            </div>
            <div class="modal-body bg-p">
                <div class="container-fluid">
                    <input type="hidden" class="id_user" value="{{$user->id}}">
                    <div class="row mb-3">
                        <label for="old_password" class="col-md-4 col-form-label text-md-end">Ancien mot de passe</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control old_password p_input" name="old_password" required autocomplete="old_password" autofocus>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="new_password" class="col-md-4 col-form-label text-md-end">Nouveau mot de passe</label>
                        <div class="col-md-6">
                            <input id="new_password" type="password" class="form-control p_input" name="new_password" required autocomplete="new_password" autofocus>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="new_password_confirmation" class="col-md-4 col-form-label text-md-end">Nouveau mot de passe</label>
                        <div class="col-md-6">
                            <input id="new_password_confirmation" type="password" class="form-control p_input" name="new_password_confirmation" required autocomplete="new_password_confirmation" autofocus>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end p-2">
                    <button type="button" class="btn btn-secondary m-1 btn_close_modal" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary m-1" id="btn_password">Continuer</button>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="{{ asset('js/profil.js') }}" defer></script>
@endsection
