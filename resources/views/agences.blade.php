@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        {{--Bouton pour créer une agence--}}
        <a  class="btn btn-primary float-end" href="/agence/create">
            Ajouter agence
        </a>
        <div class="container">
            <h2>Page agences</h2>
            <table id="DataTable_agence" class="table table-dark mt-2 table-hover table-striped dataTable table-responsive" style="width: 100%">
                <thead class="border-1 border-bottom border-white">
                <tr>
                    <th>Ville</th>
                    <th>Rue</th>
                    <th>Code Postal</th>
                    <th>Chef agence</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                {{--créer une colonne pour chaque agence--}}
                @foreach($agences as $datas)
                    <tr>
                        <td>{{$datas->ville}}</td>
                        <td>{{$datas->rue}}</td>
                        <td>{{$datas->codePostal}}</td>
                        <td>{{($datas->first_name !== null) ? $datas->first_name.' '.$datas->last_name.' '.$datas->email : 'Aucun utilisateur'}}</td>
                        <td class="tdBtn">
                            <div class="divBtnTab d-flex flex-column flex-md-row">
                                {{--Bouton pour modifié une agence--}}
                                <a class="btn btn-info editButton text-white" href="/agence/edit/{{$datas->id}}"><i class="fa-solid fa-pencil "></i></a>
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
    <script src="{{ asset('js/agence.js') }}" defer></script>
@endsection
