@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4 px-0">
        <!-- Button trigger modal -->
        <a href="/admin/user/create" class="btn btn-primary float-end">
            Ajouter un utilisateur
        </a>
        <div class="container p-0">
            <h2>Page utilisateurs</h2>
            <table id="DataTable_users" class="table table-striped dataTable table-responsive" style="width: 100%">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $datas)
                    <tr data-voiture="{{$datas->id}}">
                        <td>{{$datas->name}}</td>
                        <td>{{$datas->email}}</td>
                        <td>{{$datas->type}}</td>
                        <td class="tdBtn">
                            <div class="divBtnTab">
                                <a class="btn btn-info editButton text-white"  href="/admin/user/edit/{{$datas->id}}"><i class="fa-solid fa-pencil "></i></a>
                                <button class="btn btn-danger delButton" data-voiture="{{$datas->id}}"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{ asset('js/user.js') }}" defer></script>
@endsection
