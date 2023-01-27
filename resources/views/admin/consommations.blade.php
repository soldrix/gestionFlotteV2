@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Button trigger modal -->
        <a href="/admin/consommation/create" class="btn btn-primary float-end">
            Ajouter une consommation
        </a>
        <div class="container">
            <h2>Page Consommations</h2>
            <table id="DataTable_carburants" class="table table-striped dataTable table-responsive" style="width: 100%">
                <thead>
                <tr>
                    <th>Nombre de litre</th>
                    <th>Montant</th>
                    <th>Immatriculation</th>
                    <th>Litre/Prix</th>
                </tr>
                </thead>
                <tbody>
                @foreach($consommations as $datas)
                    <tr>
                        <td>{{$datas->litre}}</td>
                        <td>{{$datas->montant.'€'}}</td>
                        <td>{{$datas->immatriculation ?? 'Aucune voiture'}}</td>
                        <td class="tdBtn">
                            {{round($datas->montant/$datas->litre,3).'€'}}
                            <div class="divBtnTab">
                                <a class="btn btn-info editButton text-white" href="/admin/consommation/edit/{{$datas->id}}"><i class="fa-solid fa-pencil "></i></a>
                                <button class="btn btn-danger delButton" data-voiture="{{$datas->id}}"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{ asset('js/consommation.js') }}" defer></script>
@endsection
