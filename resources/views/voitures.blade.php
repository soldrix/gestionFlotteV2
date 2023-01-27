@extends('layouts.app')
@section('content')
        <div class="container d-flex flex-column py-4">
            {{--div agence search--}}
            <div class="col-auto d-flex position-relative bg-light">
                <i class="fa-solid fa-magnifying-glass position-absolute" style="bottom: 7.5px"></i>
                <div class="d-flex flex-column w-75 position-relative">
                    <label for="searchBar" class="text-opacity-50 text-dark mb-2">Retrait et retour</label>
                    @foreach($agence as $datas)
                    <input id="searchBar" type="search" autocomplete="off" placeholder="Trouver une agence" value="{{$datas->ville.' '.$datas->rue}}" class="w-100 text-dark inputSearch ps-4" style="outline: none">
                    @endforeach
                    {{--list agence--}}
                    <div id="divSearch" class="col-12 py-2 px-4  overflow-auto">

                    </div>
                </div>
            </div>
            {{--div filter voiture--}}
            <div class='divFilter px-2'>
                <p class="mb-0 align-self-center mx-2" style="font-weight: bold"><span id="nbOffre">{{$nbVoiture ?? 0}}</span>{{($nbVoiture < 2) ? ' Offre' : ' Offres' }} </p>
                <div class="btn-group">
                    <a class='m-2 text-end' id="dropdownfilter" data-bs-toggle="dropdown" aria-expanded="false"> Type de voiture <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <div class='ligne-75'></div>
                        @foreach($type ?? '' as $data)
                        <li class="dropdown-item"><input type="checkbox" class="typeCheck" checked data-type="{{$data->type}}"> {{strtoupper($data->type)}} </li>
                        @endforeach
                    </ul>
                </div>

            </div>

            <div id="offerList" class="col-12 p-0 d-flex flex-wrap">
                @foreach($voiture as $datas)
                <div class="col-4 p-3 voiture" data-type="{{$datas->type}}">
                    <div class="d-flex flex-column bg-white p-3">
                        <h2>{{$datas->marque.' '.$datas->model}}</h2>
                        <p>ou similaire | {{$datas->type}}</p>
                        <div class="imageDiv w-100 justify-content-center d-flex">
                            <img src="{{asset('/storage/'.$datas->image)}}" alt="" class="w-75 rounded">
                        </div>
                        <p class="mt-2"><i class="fa-solid fa-check"></i> 250 kilomètre incl.</p>
                        <div class="d-flex justify-content-between">
                            <h3>{{$datas->prix}}€ | <span>jour</span></h3>
                            @if($datas->statut !== 'Indisponible')
                            <a class="btn btn-outline-primary text-decoration-none" href="{{route('voitureData', $datas->id) }}">Sélectionner</a>
                            @else
                            <h2 class="text-capitalize">{{$datas->statut}}</h2>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
@endsection
