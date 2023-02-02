@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Button trigger modal -->
        <a href="/assurance/create" class="btn btn-primary float-end">
            Ajouter une assurance
        </a>
        <div class="container">
            <h2>Page assurances</h2>
            <table id="DataTable_assurances" class="table table-dark mt-2 table-hover table-striped dataTable table-responsive" style="width: 100%">
                <thead class="border-1 border-bottom border-white">
                <tr>
                    <th>Nom assurance</th>
                    <th>Debut assurance</th>
                    <th>Fin assurance</th>
                    <th>Immatriculation</th>
                    <th>Frais</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($assurances as $datas)
                    <tr>
                        <td>{{$datas->nom}}</td>
                        {{--Modifie le format de la date de debut--}}
                        <td>{{date('d/m/Y', strtotime($datas->DateDebut))}}</td>
                        <td>{{date('d/m/Y', strtotime($datas->DateFin))}}</td>
                        <td>{{$datas->immatriculation ?? 'Aucune voiture'}}</td>
                        <td>{{$datas->frais.'€'}}</td>
                        <td class="tdBtn">
                            <div class="divBtnTab d-flex flex-column flex-md-row">
                                <a class="btn btn-info editButton text-white" href="/assurance/edit/{{$datas->id}}"><i class="fa-solid fa-pencil "></i></a>
                                <button class="btn btn-danger delButton" data-voiture="{{$datas->id}}"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{ asset('js/assurance.js') }}" defer></script>
@endsection
