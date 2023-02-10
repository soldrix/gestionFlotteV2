@extends('layouts.app')
@section('content')
    <div class="container-fluid py-4">
        <a href="/chef-agence" class="btn bg-s shadow-block color-white">Retour</a>
        <div class="col-9 d-flex flex-wrap pt-5 justify-content-center mx-auto containerVoiture">
            @foreach($voitures as $data)
                <div class="col-12 col-lg-3 col-xxl-3 d-flex flex-column  p-2 rounded m-2 blockVoiture bg-s">
                    <img src="{{asset("/api/image/".$data->image)}}" alt="Image voiture" class="rounded">
                    <p class="text-center my-1">Marque : {{$data->marque}}</p>
                    <p class="text-center m-0">Model : {{$data->model}}</p>
                    <p class="text-center m-0">Type : {{$data->type}}</p>
                    <p class="text-center m-0">Carburant : {{$data->carburant}}</p>
                    <p class="text-center m-0">prix : {{$data->prix.' € / jour'}}</p>
                    <p class="text-center m-0">Statut : {{($data->statut === 1 ) ? 'Disponible': 'Indisponible'}}</p>
                    <a  class="btn btn-primary w-75 align-self-center mt-3 btn-info-car" href="{{route('statutVoiture', $data->id) }}">
                       Modifier statut
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <script src="{{ asset('js/voiture.js') }}" defer></script>
@endsection
