@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Button trigger modal -->
        <a href="/admin/fournisseur/create" class="btn btn-primary float-end">
            Ajouter un fournisseur
        </a>
        <div class="container">
            <h2>Page fournisseur</h2>
            <table id="DataTable_fournisseur" class="table table-striped dataTable table-responsive" style="width: 100%">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                </tr>
                </thead>
                <tbody>
                @foreach($fournisseurs as $datas)
                    <tr data-voiture="{{$datas->id}}">
                        <td>{{$datas->name}}</td>
                        <td class="tdBtn">
                            {{$datas->email}}
                            <div class="divBtnTab d-flex flex-column flex-md-row">
                                <a href="/admin/fournisseur/edit/{{$datas->id}}" class="btn btn-info editButton text-white"><i class="fa-solid fa-pencil "></i></a>
                                <button class="btn btn-danger delButton" data-voiture="{{$datas->id}}"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{ asset('js/fournisseur.js') }}" defer></script>
@endsection
