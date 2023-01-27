@extends('layouts.app')

@section('content')
         <div class="container Containerlocation py-4">
            <h2>mes locations</h2>
             <div class="col-auto pt-5 location">
                 <div class="w-100 d-flex bg-light text-dark">
                     <div class="col-3 p-2 cellLocation d-flex">
                         <p class="m-auto">Date de début</p>
                     </div>
                     <div class="col-3 p-2 cellLocation d-flex">
                         <p class="m-auto">Date de fin</p>
                     </div>
                     <div class="col-3 p-2 cellLocation d-flex">
                         <p class="m-auto">Montant</p>
                     </div>
                     <div class="col-3 p-2 cellLocation d-flex">
                         <p class="m-auto">Voiture</p>
                     </div>
                 </div>
                 @if(isset($locations))
                     @foreach($locations as $datas)
                         <div class="w-100 d-flex bg-dark bg-opacity-50 text-white">
                             <div class="col-3 p-2 cellLocation d-flex">
                                 <p class="m-auto">{{date('d/m/Y', strtotime($datas->DateDebut))}}</p>
                             </div>
                             <div class="col-3 p-2 cellLocation d-flex">
                                 <p class="m-auto">{{date('d/m/Y', strtotime($datas->DateFin))}}</p>
                             </div>
                             <div class="col-3 p-2 cellLocation d-flex">
                                 <p class="m-auto">{{$datas->montant.'€'}}</p>
                             </div>
                             <div class="col-3 p-2 cellLocation d-flex">
                                 <a class="m-auto text-decoration-none text-white" href="/voiture/{{$datas->id_voiture}}">{{$datas->marque.' '.$datas->model}}</a>
                                 @if(date_format(date_create($datas->DateDebut), 'Y-m-d') > date('Y-m-d'))
                                     <button class="btn btn-outline-danger annulLocation" data-location="{{$datas->id}}">
                                         Annuler
                                     </button>
                                 @endif
                             </div>
                         </div>
                     @endforeach
                     @else
                     <div class="w-100 d-flex bg-dark bg-opacity-50 text-white">
                         <div class="w-100 p-2 cellLocation d-flex">
                             <p class="m-auto">Aucune location</p>
                         </div>
                     </div>
                 @endif
             </div>
         </div>
@endsection
