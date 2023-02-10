
@extends('layouts.app')

@section('content')
        @foreach( $voitureData ?? '' as $datas)
            <div class="container-fluid py-4">
                <div class="col-auto d-flex flex-column flex-lg-row">
                    <div class="col-12 col-lg-6 mt-2 d-flex">
                        <div class="imageVEdit align-self-center w-100 rounded" style='background-image: url("{{asset("/api/image/".$datas->image)}}");'>

                        </div>
{{--                        <img id="imageVoiture" src="{{asset("/api/image/".$datas->image)}}" alt="{{$datas->image}}" class="rounded" style="max-width: 100%">--}}
                    </div>
                    <div class="col-12 col-lg-6 px-2 ms-0 ms-lg-2 mt-5 mt-lg-2 border-3 border-opacity-25 border-dark" style="border-left: solid">
                        <h2 class="text-primary text-center text-lg-start v-title mx-2">Immatriculation : <span class="text-white" id="immatriculation">{{$datas->immatriculation}}</span></h2>
                        <div class="d-flex mt-3">
                            <p class="mx-2 mb-2 text-center"><i class="fa-solid fa-wrench fa-xl text-info"></i> <span id="nbEnt"> {{$nbEnt}} </span> entretiens</p>
                            <p class="mx-2 mb-2 text-center"><i class="fa-solid fa-gear fa-xl text-info"></i> <span id="nbRep"> {{$nbRep}} </span> reparations</p>
                            <p class="mx-2 mb-2 text-center"><i class="fa-solid fa-calendar-check fa-xl text-info"></i> <span id="nbAssu"> {{$nbAssu}} </span> assurances</p>
                            <p class="mx-2 mb-2 text-center"><i class="fa-solid fa-gas-pump fa-xl text-info"></i> <span id="nbCons"> {{$nbCons}} </span> Consommation</p>
                        </div>
                        <div class="col-auto mt-2 d-flex flex-wrap justify-content-center justify-content-lg-start">
                            <div class="col-auto mx-2">
                                <h2 class="text-primary v-title">Marque : <span class="text-white">{{$datas->marque}}</span></h2>
                                <h2 class="text-primary v-title">Model : <span class="text-white">{{$datas->model}}</span> </h2>
                                <h2 class="text-primary v-title">Mise en circulation : <span class="text-white">{{date('d/m/Y', strtotime($datas->circulation))}}</span></h2>
                                <h2 class="text-primary v-title">Statut : <span class="text-white">{{($datas->statut === 1) ? 'Disponible' :'Indisponible'}}</span></h2>
                                <h2 class="text-primary v-title">Puissance : <span class="text-white">{{$datas->puissance}}</span></h2>
                                <h2 class="text-primary v-title">Carburant : <span class="text-white">{{$datas->carburant}}</span></h2>
                                <h2 class="text-primary v-title">Type : <span class="text-white">{{$datas->type}}</span></h2>
                                <h2 class="text-primary v-title">nombre de siège : <span class="text-white">{{$datas->nbPlace}}</span></h2>
                                <h2 class="text-primary v-title">nombre de porte : <span class="text-white">{{$datas->nbPorte}}</span></h2>
                                <h2 class="text-primary v-title">Agence : <span class="text-white">{{($datas->ville !== null) ? $datas->ville.' '.$datas->rue : 'Aucune agence'}}</span></h2>
                                <h2 class="text-primary v-title">Fournisseur : <span class="text-white">{{($datas->name !== null) ? $datas->name.' '.$datas->email : 'Aucun fournisseur'}}</span></h2>
                            </div>
                            <div class="col-auto mx-2 p-0  align-self-center">
                                <a class="btn btn-info  ms-lg-5 h-fit" href="edit/{{$datas->id}}">modifier</a>
                            </div>
                        </div>
                    </div>

                </div>
                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['admin', 'responsable auto']))
                <div class="divbottom">
                    <div class="border-bottom border-top mt-2 border-dark border-opacity-25 border-2 pt-2">
                        <ul id="info_voiture" class="nav nav-tabs mt-3">
                            <li class="nav-item">
                                <a id="btnTabEnt" class="nav-link active tabsHome" href="#" data-bs-toggle="tab" data-bs-target="#table_entretiens"><i class="fa-solid fa-wrench fa-lg m-2"></i>Entretiens</a>
                            </li>
                            <li class="nav-item">
                                <a id="btnTabRep" class="nav-link tabsHome" href="#" data-bs-toggle="tab" data-bs-target="#table_reparations"><i class="fa-solid fa-gear fa-lg m-2"></i>Reparations</a>
                            </li>
                            <li class="nav-item">
                                <a id="btnTabAssu" class="nav-link tabsHome" href="#" data-bs-toggle="tab" data-bs-target="#table_assurances"><i class="fa-solid fa-calendar-check fa-lg m-2"></i>Assurances</a>
                            </li>
                            <li class="nav-item">
                                <a id="btnTabCons" class="nav-link tabsHome" href="#" data-bs-toggle="tab" data-bs-target="#table_carburants"><i class="fa-solid fa-gas-pump fa-lg m-2"></i>Consommations</a>
                            </li>
                        </ul>
                    </div>
                    <div id="block_info_voiture" class="tab-content">
                        <div id="table_entretiens" class="tab-pane fade active show " role="tabpanel">

                            <!-- Button trigger modal -->
                            <a href="/entretien/create" class="btn btn-primary float-end my-2">
                                Ajouter Entretien
                            </a>
                            <table id="DataTable_entretiens" class="table table-dark mt-2 table-striped table-hover dataTable dt-responsive" style="width: 100%">
                                <thead class="border-1 border-bottom border-white">
                                <tr>
                                    <th>Nom garage</th>
                                    <th>Type</th>
                                    <th>Montant</th>
                                    <th>Date</th>
                                    <th>Note</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($entretiens as $datas)
                                    <tr>
                                        <td>{{$datas->nom}}</td>
                                        <td>{{$datas->type}}</td>
                                        <td>{{$datas->montant.'€'}}</td>
                                        <td>{{date('d/m/Y', strtotime($datas->date))}}</td>
                                        <td>
                                            <div class="noteSupp">
                                                {{(isset($datas->note)) ? $datas->note : "aucune note"}}
                                            </div>
                                        </td>
                                        <td class="tdBtn">
                                            <div class="divBtnTab">
                                                <a class="btn btn-info editButton text-white" href="/entretien/edit/{{$datas->id}}"><i class="fa-solid fa-pencil "></i></a>
                                                <button class="btn btn-danger delButton" data-voiture="{{{$datas->id}}}" data-db="entretien"><i class="fa-solid fa-trash-can"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                        <div id="table_reparations" class="tab-pane fade" role="tabpanel">
                            <!-- Button trigger modal -->
                            <a href="/reparation/create" class="btn btn-primary float-end my-2">
                                Ajouter Reparation
                            </a>
                            <table id="DataTable_reparations" class="table table-dark mt-2 table-hover table-striped dataTable dt-responsive" style="width: 100%">
                                <thead class="border-1 border-bottom border-white">
                                <tr>
                                    <th>Nom garage</th>
                                    <th>Type</th>
                                    <th>Montant</th>
                                    <th>Date</th>
                                    <th>Note</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reparations as $datas)
                                    <tr>
                                        <td>{{$datas->nom}}</td>
                                        <td>{{$datas->type}}</td>
                                        <td>{{$datas->montant.'€'}}</td>
                                        <td>{{date('d/m/Y', strtotime($datas->date))}}</td>
                                        <td>
                                            <div class="noteSupp">
                                                {{(isset($datas->note)) ? $datas->note : "aucune note"}}
                                            </div>
                                        </td>
                                        <td class="tdBtn">
                                            <div class="divBtnTab">
                                                <a class="btn btn-info editButton text-white" href="/reparation/edit/{{$datas->id}}"><i class="fa-solid fa-pencil "></i></a>
                                                <button class="btn btn-danger delButton" data-voiture="{{$datas->id}}" data-db="reparation"><i class="fa-solid fa-trash-can"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div id="table_assurances" class="tab-pane fade " role="tabpanel">
                            <!-- Button trigger modal -->
                            <a href="/assurance/create" class="btn btn-primary float-end my-2">
                                Ajouter assurance
                            </a>
                            <table id="DataTable_assurances" class="table table-dark mt-2 table-hover table-striped dataTable dt-responsive" style="width: 100%">
                                <thead class="border-1 border-bottom border-white">
                                <tr>
                                    <th>Nom assurance</th>
                                    <th>Debut assurance</th>
                                    <th>Fin assurance</th>
                                    <th>Frais</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($assurances as $datas)
                                    <tr>
                                        <td>{{$datas->nom}}</td>
                                        <td>{{date('d/m/Y', strtotime($datas->DateDebut))}}</td>
                                        <td>{{date('d/m/Y', strtotime($datas->DateFin))}}</td>
                                        <td>{{$datas->frais."€"}}</td>
                                        <td class="tdBtn">
                                            <div class="divBtnTab">
                                                <a class="btn btn-info editButton text-white" href="/assurance/edit/{{$datas->id}}"><i class="fa-solid fa-pencil "></i></a>
                                                <button class="btn btn-danger delButton" data-voiture="{{$datas->id}}" data-db="assurance"><i class="fa-solid fa-trash-can"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div id="table_carburants" class="tab-pane fade" role="tabpanel">
                            <!-- Button trigger modal -->
                            <a class="btn btn-primary float-end my-2" href="/consommation/create">
                                Ajouter Consommation
                            </a>
                            <table id="DataTable_carburants" class="table table-dark mt-2 table-hover  table-striped dataTable dt-responsive" style="width: 100%">
                                <thead class="border-1 border-bottom border-white">
                                <tr>
                                    <th>Nombre de litre</th>
                                    <th>Montant</th>
                                    <th>litre/prix</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($consommations as $datas)
                                    <tr>
                                        <td>{{$datas->litre}}</td>
                                        <td>{{$datas->montant.'€'}}</td>
                                        <td>{{round($datas->montant/$datas->litre,3).'€'}}</td>
                                        <td class="tdBtn">
                                            <div class="divBtnTab">
                                                <a class="btn btn-info editButton text-white" href="/consommation/edit/{{$datas->id}}"><i class="fa-solid fa-pencil "></i></a>
                                                <button class="btn btn-danger delButton" data-db="consommation" data-voiture="{{$datas->id}}"><i class="fa-solid fa-trash-can"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        @endforeach
        <script src="{{ asset('js/voitureDatatable.js') }}" defer></script>
@endsection
