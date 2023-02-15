@extends('layouts.app')

@section('content')
        <div class="container-fluid py-4">
            <!-- Button trigger modal -->
            <a href="/location/create" class="btn btn-primary float-end">
                Ajouter une location
            </a>
            <div class="container">
                <h2>Page location</h2>
                <table id="DataTable_location" class="table table-dark mt-2 table-hover table-striped dataTable table-responsive" style="width: 100%">
                    <thead class="border-1 border-bottom border-white">
                    <tr>
                        <th>Date de début</th>
                        <th>Date de Fin</th>
                        <th>Immatriculation</th>
                        <th>utilisateur</th>
                        <th>Montant</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($locations as $datas)
                        <tr data-voiture="{{$datas->id}}" data-db="location">
                            <td>{{date('d/m/Y', strtotime($datas->DateDebut))}}</td>
                            <td>{{date('d/m/Y', strtotime($datas->DateFin))}}</td>
                            <td>{{$datas->immatriculation ?? 'Aucune voiture'}}</td>
                            <td>{{($datas->email !== null) ? $datas->first_name.' '.$datas->last_name.' '.$datas->email :'Aucun utilisateur'}}</td>
                            <td>{{$datas->montant}}</td>
                            <td class="tdBtn">
                                <div class="divBtnTab d-flex flex-column flex-md-row">
                                    <a href="/location/edit/{{$datas->id}}" class="btn btn-info editButton text-white"><i class="fa-solid fa-pencil "></i></a>
                                    <button class="btn btn-danger delButton" data-voiture="{{$datas->id}}"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <script src="{{ asset('js/location.js') }}" defer></script>
@endsection
