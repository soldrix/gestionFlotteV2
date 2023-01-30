@extends('layouts.app')
@section('content')
<div class="container d-flex flex-column pt-5">
    <div id="profilContent" class="w-100">

    </div>
    <div class="col-8 d-flex align-self-center flex-column">
            @if(\Illuminate\Support\Facades\Auth::user())
                <h2>Mon profil</h2>
                <a class="btn btn-outline-primary h-50 align-self-center m-2" href="/UpdatePassword">
                    modifier mot de passe
                </a>

                <div class="d-flex justify-content-center w-100">
                    <button class="btn btn-outline-danger" data-bs-target="#deluser" data-bs-toggle="modal" data-bs-dismiss="modal">suppression du compte</button>
                </div>
            @endif
    </div>
</div>
@endsection
