@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        {{--Bouton pour créer une agence--}}
        <a  class="btn btn-primary float-end" href="/commande/create">
            Ajouter une commandes
        </a>
        <div class="container">
            <h2>Page commandes</h2>
            <table id="DataTable_commandes" class="table table-dark mt-2 table-hover table-striped dataTable table-responsive" style="width: 100%">
                <thead class="border-1 border-bottom border-white">
                <tr>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Marque</th>
                    <th>Model</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                {{--créer une colonne pour chaque agence--}}
                @foreach($commandes as $datas)
                    <tr>
                        <td>{{date('d/m/Y', strtotime($datas->DateDebut))}}</td>
                        <td>{{date('d/m/Y', strtotime($datas->DateFin))}}</td>
                        <td>{{$datas->marque}}</td>
                        <td>{{$datas->model}}</td>
                        <td class="tdBtn">
                            <div class="divBtnTab d-flex flex-column flex-md-row">
                                {{--Bouton pour modifié une agence--}}
                                <a class="btn btn-info editButton text-white" href="/commande/edit/{{$datas->id}}"><i class="fa-solid fa-pencil "></i></a>
                                {{--Bouton pour supprimé une agence--}}
                                <button class="btn btn-danger delButton" data-voiture="{{$datas->id}}"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{ asset('js/commandes.js') }}" defer></script>
@endsection
