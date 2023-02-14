<?php

namespace App\Http\Controllers;

use App\Models\location;
use App\Models\User;
use App\Models\voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        $locations = location::leftJoin('voitures', 'voitures.id', '=', 'locations.id_voiture')
            ->leftjoin('users', 'users.id', '=', 'locations.id_users')
            ->get([
                'locations.*',
                'voitures.immatriculation',
                'users.email'
            ])
        ;
        return view('locations',["locations" => $locations]);
    }
    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $voitures =  voiture::all();
        $users = User::all();
        return view('form.location.locationCreate',['voitures' => $voitures, 'users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "DateDebut"  => ["required","after:2000-01-01"],
            "DateFin" => ["required", "after_or_equal:".$request->DateDebut],
            "montant" => ["required", "numeric"]
        ]);
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        location::create([
            "DateDebut" => $request->DateDebut,
            "DateFin" => $request->DateFin,
            "montant" => $request->montant,
            "id_voiture" => ($request->id_voiture === 'null') ? null : $request->id_voiture,
            "id_users"  => ($request->id_user === 'null') ? null : $request->id_user
        ]);
        return back()->with('message', 'La location a été créer avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     */
    public function edit($id)
    {
        $location = location::find($id);
        $voitures = voiture::all();
        $users = User::all();
        return view('form.location.locationEdit', ['location' => $location, 'voitures' => $voitures, 'users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(array_filter($request->all()),[
            "DateDebut"  => ["after:2000-01-01"],
            "DateFin" => ["after_or_equal:". ($request->DateDebut) ? $request->DateDebut : '2000-01-01',($request->DateDebut) ? "required" : ""],
            "montant" => "numeric",
        ]);
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        $location = location::find($id);

        if($request->id_voiture !== null){
            $location->id_voiture = ($request->id_voiture === 'vide') ? null :  $request->id_voiture;
        }
        if($request->id_users !== null){
            $location->id_users = ($request->id_users === 'vide') ? null :  $request->id_users;
        }
        if($request->DateDebut !== null){
            $location->DateDebut = $request->DateDebut;
        }
        if($request->DateFin !== null){
            $location->DateFin = $request->DateFin;
        }
        if(isset($request->montant)){
            $location->montant = $request->montant;
        }

        $location->update();
        return back()->with('message', 'La location a été modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     */
    public function destroy($id):void
    {
        $location = location::find($id);
        $location->delete();
    }
}
