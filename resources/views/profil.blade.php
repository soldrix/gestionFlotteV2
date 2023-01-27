@extends('layouts.app')
@section('content')
<div class="container d-flex flex-column pt-5">
    <div id="profilContent" class="w-100">

    </div>
    <div class="col-8 d-flex align-self-center flex-column">
            @if(\Illuminate\Support\Facades\Auth::user())
                <h2>Mon profil</h2>
                <div class="d-flex w-100 p-2">
                    <p class="m-0" style="font-weight: bold">
                        Prenom <br> <span id="textName">{{auth()->user()->name}}</span>
                    </p>
                    <button class="btn btn-outline-primary h-50 align-self-center mx-2" data-bs-target="#modifNamemodal" data-bs-toggle="modal" data-bs-dismiss="modal">
                        modifier
                    </button>
                </div>
                <div class="d-flex w-100 p-2">
                    <p class="m-0" style="font-weight: bold">
                        EMAIL <br> <span id="textEmail">{{auth()->user()->email}}</span>
                    </p>
                    <button class="btn btn-outline-primary h-50 align-self-center mx-2" data-bs-target="#modifEmailmodal" data-bs-toggle="modal" data-bs-dismiss="modal">
                        modifier
                    </button>
                </div>
                <button class="btn btn-outline-primary h-50 align-self-center m-2" data-bs-target="#modifPasswordmodal" data-bs-toggle="modal" data-bs-dismiss="modal">
                    modifier mot de passe
                </button>

                <div class="d-flex justify-content-center w-100">
                    <button class="btn btn-outline-danger" data-bs-target="#deluser" data-bs-toggle="modal" data-bs-dismiss="modal">suppression du compte</button>
                </div>
            @endif
    </div>
</div>
@endsection
