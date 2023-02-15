@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="container">
            <h2 class="mb-5">Page agences</h2>
                @foreach($agences as $datas)
                @if(\Illuminate\Support\Facades\Auth::user()->hasRole('chef agence'))
                    @if(\Illuminate\Support\Facades\Auth::id() === $datas->id_user)
                        <div class="col-auto bg-s px-3 py-3 shadow-block d-flex my-3">
                            <ul class="list-unstyled d-flex justify-content-around w-100 m-0">
                                <li>
                                    <p class="m-0">Ville : {{$datas->ville}}</p>
                                </li>
                                <li>
                                    <p class="m-0">Rue : {{$datas->rue}}</p>
                                </li>
                                <li>
                                    <p class="m-0">Code postal : {{$datas->codePostal}}</p>
                                </li>
                                <li>
                                    <a href="/chef-agence/voitures/{{$datas->id}}">Voir les véhicules</a>
                                </li>
                            </ul>
                        </div>
                    @endif
                @else
                    <div class="col-auto bg-s px-3 py-3 shadow-block d-flex my-3">
                        <ul class="list-unstyled d-flex justify-content-around w-100 m-0">
                            <li>
                                <p class="m-0">Ville : {{$datas->ville}}</p>
                            </li>
                            <li>
                                <p class="m-0">Rue : {{$datas->rue}}</p>
                            </li>
                            <li>
                                <p class="m-0">Code postal : {{$datas->codePostal}}</p>
                            </li>
                            <li>
                                <p class="m-0">Chef d'agence : {{$datas->first_name.' '.$datas->last_name.' '.$datas->email}}</p>
                            </li>
                            <li>
                                <a href="/chef-agence/voitures/{{$datas->id}}">Voir les véhicules</a>
                            </li>
                        </ul>
                    </div>
                @endif
                @endforeach
        </div>
    </div>
    <script src="{{ asset('js/agence.js') }}" defer></script>
@endsection
