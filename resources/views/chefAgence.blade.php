@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        {{--Bouton pour créer une agence--}}
        <a  class="btn btn-primary float-end" href="/agence/create">
            Ajouter agence
        </a>
        <div class="container">
            <h2>Page agences</h2>
                @foreach($agences as $datas)
                @if(\Illuminate\Support\Facades\Auth::user()->hasRole('chef agence'))
                    @if(\Illuminate\Support\Facades\Auth::id() === $datas->id_user)
                    <div class="col-8">
                        <a href="/chef-agence/voitures/{{$datas->id}}"></a>
                    </div>
                    @endif
                @else
                    <div class="col-8">
                        <a href="/chef-agence/voitures/{{$datas->id}}"></a>
                    </div>
                @endforeach
        </div>
    </div>
    <script src="{{ asset('js/agence.js') }}" defer></script>
@endsection
