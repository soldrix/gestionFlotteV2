@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center bg-p h-100C w-100">
        <div class="d-flex align-self-center flex-column align-items-center">
            <h2>Oops</h2>
            <p>Page not found</p>
            <a class="btn btn-outline-light" href="/home">Return</a>
        </div>
    </div>
    <style>
        h2{
            color: white;
            font-size: 4rem;
        }
        p{
            font-size: 3rem;
            color: white;
            margin: 0;
        }
    </style>
@endsection
