@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Button trigger modal -->
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
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($agences as $datas)
                    <tr>
                        <td>{{$datas->ville}}</td>
                        <td>{{$datas->rue}}</td>
                        <td>{{$datas->codePostal}}</td>
                        <td class="tdBtn">
                            <div class="divBtnTab d-flex flex-column flex-md-row">
                                <a class="btn btn-info editButton text-white" href="/agence/edit/{{$datas->id}}"><i class="fa-solid fa-pencil "></i></a>
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