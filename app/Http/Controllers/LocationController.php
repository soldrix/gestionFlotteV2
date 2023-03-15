<?php

namespace App\Http\Controllers;

use App\Models\location;
use App\Models\User;
use App\Models\voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    /**
     * Pour récupérer toutes les locations.
     *
     * @return mixed
     */
    public function index()
    {
        $locations = location::leftJoin('voitures', 'voitures.id', '=', 'locations.id_voiture')
            ->leftjoin('users', 'users.id', '=', 'locations.id_user')
            ->get([
                'locations.*',
                'voitures.immatriculation',
                'users.email'
            ])
        ;
        return view('locations',["locations" => $locations]);
    }
    /**
     * Pour afficher la page de création.
     *
     */
    public function create()
    {
        $voitures =  voiture::all();
        $users = User::all();
        return view('form.location.locationCreate',['voitures' => $voitures, 'users' => $users]);
    }

    /**
     * Pour enregistrer.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "DateDebut"  => ["required","after:2000-01-01"],
            "DateFin" => ["required", "after_or_equal:".$request->DateDebut],
            "montant" => ["required", "numeric"],
            "id_voiture" => "required",
            "id_user" => "required"
        ],
        [
            "required" => "Champs requis.",
            "after" => "Doit être après 01/01/2000.",
            "after_or_equal" => "Doit être supérieur ou égal à la date de début."
        ]);
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        $collections =collect($request->all())->merge(["commandeNumber" => Str::random(15)]);
        if($collections->get('id_voiture') === "vide"){
            $collections = $collections->replaceRecursive(["id_voiture" => null]);
        }
        if($collections->get('id_user') === "vide"){
            $collections = $collections->replaceRecursive(['id_user' => null]);
        }
        location::create($collections->all());
        return back()->with('message', 'La location a été créer avec succès.');
    }

    /**
     * Pour afficher la page de modification.
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
     * Pour modifier.
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
        $collections =collect($request->all())->filter();
        if($collections->get('id_voiture') === "vide"){
            $collections = $collections->replaceRecursive(["id_voiture" => null]);
        }
        if($collections->get('id_user') === "vide"){
            $collections = $collections->replaceRecursive(['id_user' => null]);
        }
        $location->update($collections->all());
        return back()->with('message', 'La location a été modifié avec succès.');
    }

    /**
     * Pour supprimer.
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
