
@extends('layouts.app')

@section('content')
        @foreach( $voitureData ?? '' as $datas)
            <div class="container-fluid py-4">
                <div class="col-auto d-flex flex-column flex-lg-row">
                    <div class="col-12 col-lg-4 border-dark border-3 border-opacity-25 p-4 d-flex justify-content-center" style="border-right: solid">
                        <img id="imageVoiture" src="{{asset("/api/image/".$datas->image)}}" alt="{{$datas->image}}" class="w-100 rounded">
                    </div>
                    <div class="col-12 col-lg-8 px-2 mt-5" data-voiture="{{$datas->id}}" data-db="voiture">
                        <h2 class="text-primary text-center text-lg-start">Immatriculation : <span class="text-muted" id="immatriculation">{{$datas->immatriculation}}</span></h2>
                        <div class="d-flex mt-5">

                            <p class="mx-2"><i class="fa-solid fa-wrench fa-xl text-info"></i> <span id="nbEnt"> {{$nbEnt}} </span> entretiens</p>
                            <p class="mx-2"><i class="fa-solid fa-gear fa-xl text-info"></i> <span id="nbRep"> {{$nbRep}} </span> reparations</p>
                            <p class="mx-2"><i class="fa-solid fa-calendar-check fa-xl text-info"></i> <span id="nbAssu"> {{$nbAssu}} </span> assurances</p>
                            <p class="mx-2"><i class="fa-solid fa-gas-pump fa-xl text-info"></i> <span id="nbCons"> {{$nbCons}} </span> Consommation</p>

                        </div>
                        <div class="col-12 col-lg-6 mt-5 d-flex flex-wrap justify-content-center justify-content-lg-start">
                            <div class="col-auto mx-2">
                                <h2 class="text-primary">Marque : </h2>
                                <h2 class="text-primary">Model : </h2>
                                <h2 class="text-primary">Mise en circulation : </h2>
                                <h2 class="text-primary">Statut : </h2>
                                <h2 class="text-primary">Puissance : </h2>
                                <h2 class="text-primary">Carburant : </h2>
                                <h2 class="text-primary">Type : </h2>
                                <h2 class="text-primary">nombre de siège : </h2>
                                <h2 class="text-primary">nombre de porte : </h2>
                                <h2 class="text-primary">Agence : </h2>
                            </div>
                            <div class="col-auto mx-2">
                                <h2 class="text-muted" id="marque">{{$datas->marque}}</h2>
                                <h2 class="text-muted" id="model">{{$datas->model}}</h2>
                                <h2 class="text-muted" id="circulation">{{$datas->circulation}}</h2>
                                <h2 class="text-muted" id="statut">{{$datas->statut}}</h2>
                                <h2 class="text-muted" id="puissance">{{$datas->puissance}}</h2>
                                <h2 class="text-muted" id="carburant">{{$datas->carburant}}</h2>
                                <h2 class="text-muted" id="typeVoiture">{{$datas->type}}</h2>
                                <h2 class="text-muted" id="nbPlace">{{$datas->nbPlace}}</h2>
                                <h2 class="text-muted" id="nbPorte">{{$datas->nbPorte}}</h2>
                                <h2 class="text-muted" id="agence">{{($datas->ville !== null) ? $datas->ville.' '.$datas->rue : 'vide'}}</h2>
                            </div>
                            <div class="col-auto mx-2 p-0  align-self-center">
                                <a class="btn btn-info ms-lg-5" href="edit/{{$datas->id}}" style="height: fit-content">modifier</a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="divbottom">
                    <div class="border-bottom border-top mt-2 border-dark border-opacity-25 border-2 pt-2">
                        <ul id="info_voiture" class="nav nav-tabs">
                            <li class="nav-item">
                                <a id="btnTabEnt" class="nav-link active text-dark" href="#" data-bs-toggle="tab" data-bs-target="#table_entretiens"><i class="fa-solid fa-wrench fa-lg text-dark m-2"></i>Entretiens</a>
                            </li>
                            <li class="nav-item">
                                <a id="btnTabRep" class="nav-link text-dark" href="#" data-bs-toggle="tab" data-bs-target="#table_reparations"><i class="fa-solid fa-gear fa-lg text-dark m-2"></i>Reparations</a>
                            </li>
                            <li class="nav-item">
                                <a id="btnTabAssu" class="nav-link text-dark" href="#" data-bs-toggle="tab" data-bs-target="#table_assurances"><i class="fa-solid fa-calendar-check fa-lg text-dark m-2"></i>Assurances</a>
                            </li>
                            <li class="nav-item">
                                <a id="btnTabCons" class="nav-link text-dark" href="#" data-bs-toggle="tab" data-bs-target="#table_carburants"><i class="fa-solid fa-gas-pump fa-lg text-dark m-2"></i>Carburants</a>
                            </li>
                        </ul>
                    </div>
                    <div id="block_info_voiture" class="tab-content">
                        <div id="table_entretiens" class="tab-pane fade active show" role="tabpanel">

                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary float-end" id="btnAddEntretiens">
                                Ajouter Entretien
                            </button>
                            <table id="DataTable_entretiens" class="table table-striped dataTable dt-responsive" style="width: 100%">
                                <thead>
                                <tr>
                                    <th>Nom garage</th>
                                    <th>Type</th>
                                    <th>Montant</th>
                                    <th>Date</th>
                                    <th>Note</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($entretiens as $datasEnt)
                                    <tr data-voiture="{{{$datasEnt->id}}}" data-db="entretiens">
                                        <td>{{$datasEnt->garageEnt}}</td>
                                        <td>{{$datasEnt->typeEnt}}</td>
                                        <td>{{$datasEnt->montantEnt.'€'}}</td>
                                        <td>{{$datasEnt->dateEnt}}</td>
                                        <td class="tdBtn">
                                            <div class="noteSupp">
                                                {{(isset($datasEnt->noteEnt)) ? $datasEnt->noteEnt : "aucune note"}}
                                            </div>
                                            <div class="divBtnTab">
                                                <button class="btn btn-info editButton text-white"><i class="fa-solid fa-pencil "></i></button>
                                                <button class="btn btn-danger delButton"><i class="fa-solid fa-trash-can"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                        <div id="table_reparations" class="tab-pane fade" role="tabpanel">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary float-end" id="btnAddReparations">
                                Ajouter Reparation
                            </button>
                            <table id="DataTable_reparations" class="table table-striped dataTable dt-responsive" style="width: 100%">
                                <thead>
                                <tr>
                                    <th>Nom garage</th>
                                    <th>Type</th>
                                    <th>Montant</th>
                                    <th>Date</th>
                                    <th>Note</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reparations as $datasRep)
                                    <tr data-voiture="{{$datasRep->id}}" data-db="reparations">
                                        <td>{{$datasRep->garageRep}}</td>
                                        <td>{{$datasRep->typeRep}}</td>
                                        <td>{{$datasRep->montantRep.'€'}}</td>
                                        <td>{{$datasRep->dateRep}}</td>
                                        <td class="tdBtn">
                                            <div class="noteSupp">
                                                {{(isset($datasRep->noteRep)) ? $datasRep->noteRep : "aucune note"}}
                                            </div>
                                            <div class="divBtnTab">
                                                <button class="btn btn-info editButton text-white"><i class="fa-solid fa-pencil "></i></button>
                                                <button class="btn btn-danger delButton"><i class="fa-solid fa-trash-can"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div id="table_assurances" class="tab-pane fade " role="tabpanel">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary float-end" id="btnAddAssurance">
                                Ajouter assurance
                            </button>
                            <table id="DataTable_assurances" class="table table-striped dataTable dt-responsive" style="width: 100%">
                                <thead>
                                <tr>
                                    <th>Nom assurance</th>
                                    <th>Debut assurance</th>
                                    <th>Fin assurance</th>
                                    <th>Frais</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($assurances as $datasAssu)
                                    <tr data-voiture="{{$datasAssu->id}}" data-db="assurance">
                                        <td>{{$datasAssu->nomAssu}}</td>
                                        <td>{{$datasAssu->debutAssu}}</td>
                                        <td>{{$datasAssu->finAssu}}</td>
                                        <td class="tdBtn">
                                            {{$datasAssu->frais."€"}}
                                            <div class="divBtnTab">
                                                <button class="btn btn-info editButton text-white"><i class="fa-solid fa-pencil "></i></button>
                                                <button class="btn btn-danger delButton"><i class="fa-solid fa-trash-can"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div id="table_carburants" class="tab-pane fade" role="tabpanel">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary float-end " id="btnAddConsommation">
                                Ajouter Carburant
                            </button>
                            <table id="DataTable_carburants" class="table table-striped dataTable dt-responsive" style="width: 100%">
                                <thead>
                                <tr>
                                    <th>Nombre de litre</th>
                                    <th>Montant</th>
                                    <th>litre/prix</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($consommations as $datasCons)
                                    <tr data-voiture="{{$datasCons->id}}" data-db="consommation">
                                        <td>{{$datasCons->litre}}</td>
                                        <td>{{$datasCons->montantCons.'€'}}</td>
                                        <td class="tdBtn">
                                            {{round($datasCons->montantCons/$datasCons->litre,3).'€'}}
                                            <div class="divBtnTab">
                                                <button class="btn btn-info editButton text-white"><i class="fa-solid fa-pencil "></i></button>
                                                <button class="btn btn-danger delButton"><i class="fa-solid fa-trash-can"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
@endsection
