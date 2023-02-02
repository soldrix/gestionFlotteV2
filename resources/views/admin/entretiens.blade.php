@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Button trigger modal -->
        <a href="/entretien/create" class="btn btn-primary float-end">
            Ajouter Entretien
        </a>
        <div class="container">
            <h2>Page entretiens</h2>
            <table id="DataTable_entretiens" class="table table-dark mt-2 table-hover table-striped dataTable table-responsive" style="width: 100%">
                <thead class="border-1 border-bottom border-white">
                <tr>
                    <th>Nom garage</th>
                    <th>Type</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Immatriculation</th>
                    <th>Note</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($entretiens as $datas)
                    <tr data-voiture="{{$datas->id}}">
                        <td>{{$datas->nom}}</td>
                        <td>{{$datas->type}}</td>
                        <td>{{$datas->montant."€"}}</td>
                        <td>{{date('d/m/Y', strtotime($datas->date))}}</td>
                        <td>{{(isset($datas->immatriculation))? $datas->immatriculation : 'Aucune voiture'}}</td>
                        <td>
                            <div class="noteSupp">
                                {{$datas->note}}
                            </div>
                        </td>
                        <td class="tdBtn">
                            <div class="divBtnTab">
                                <a class="btn btn-info editButton text-white"  href="/entretien/edit/{{$datas->id}}"><i class="fa-solid fa-pencil "></i></a>
                                <button class="btn btn-danger delButton" data-voiture="{{$datas->id}}"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{ asset('js/entretien.js') }}" defer></script>
@endsection
