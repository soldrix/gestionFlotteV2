@extends('layouts.app')
@section('content')
<div class="container d-flex flex-column pt-5">
    <div id="profilContent" class="w-100">

    </div>
    <div class="col-8 d-flex align-self-center flex-column">
            @if(\Illuminate\Support\Facades\Auth::user())
                <h2>Mon profil</h2>
                <p>Nom : {{\Illuminate\Support\Facades\Auth::user()->name}}</p>
                <p>Email : {{\Illuminate\Support\Facades\Auth::user()->email}}</p>
                <a class="btn btn-outline-primary h-50 align-self-center m-2" href="/profil/edit/{{\Illuminate\Support\Facades\Auth::id()}}">
                    modifier mon profil
                </a>
                <a class="btn btn-outline-primary h-50 align-self-center m-2" href="/UpdatePassword">
                    modifier mot de passe
                </a>
                <div class="d-flex justify-content-center w-100">
                    <button class="btn btn-outline-danger delButton" data-voiture="{{\Illuminate\Support\Facades\Auth::user()->id}}">suppression du compte</button>
                </div>
            @endif
    </div>
</div>
<script src="{{ asset('js/profil.js') }}" defer></script>
@endsection
