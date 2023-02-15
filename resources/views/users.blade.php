@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Button trigger modal -->
        <a href="/user/create" class="btn btn-primary float-end">
            Ajouter un utilisateur
        </a>
        <div class="container p-0">
            <h2>Page utilisateurs</h2>
            <table id="DataTable_users" class="table table-dark mt-2 table-hover table-striped dataTable table-responsive" style="width: 100%">
                <thead class="border-1 border-bottom border-white">
                <tr>
                    <th>Prenom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Type</th>
                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                        <th>Statut</th>
                    @endif
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $datas)
                    @if($datas->id !== \Illuminate\Support\Facades\Auth::user()->id)
                    <tr data-voiture="{{$datas->id}}">
                        <td>{{$datas->first_name}}</td>
                        <td>{{$datas->last_name}}</td>
                        <td>{{$datas->email}}</td>
                        <td>{{$datas->type}}</td>
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                            <td>{{($datas->statut === 0) ? 'Désactiver' : 'Activer'}}</td>
                        @endif
                        <td class="tdBtn">
                            <div class="divBtnTab">
                                <a class="btn btn-info editButton text-white"  href="/user/edit/{{$datas->id}}"><i class="fa-solid fa-pencil "></i></a>
                                @if(\Illuminate\Support\Facades\Auth::user()->hasRole('RH'))
                                    <button class="btn btn-danger desButton" data-voiture="{{$datas->id}}"><i class="fa-solid fa-trash-can"></i></button>
                                @else
                                    <button class="btn btn-danger delButton" data-voiture="{{$datas->id}}"><i class="fa-solid fa-trash-can"></i></button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{ asset('js/user.js') }}" defer></script>
@endsection
