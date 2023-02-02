<?php

namespace App\Http\Controllers;

use App\Models\reparation;
use App\Models\voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReparationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $reparations = reparation::leftJoin('voitures', 'voitures.id', '=', 'reparations.id_voiture')
            ->get([
                'reparations.*',
                'voitures.immatriculation'
            ]);
        return view('reparations',['reparations' => $reparations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $voitures = voiture::all();
        return view('form.reparation.reparationCreate',["voitures" => $voitures]);
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
            "nom" => "required",
            "type" => "required",
            "date" => ["required","after:2000-01-01"],
            "montant" => ["required","numeric"]
        ],
        [
            "required" => "Le champ est requis.",
            "date.after" => "La date doit être valide et être après 01/01/2000.",
            "numeric" => "Le montant doit être un chiffre ex: ( 10.50)."
        ]);
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        reparation::create([
            "nom" => $request->nom,
            "type" => $request->type,
            "date" => $request->date,
            "montant" => $request->montant,
            "note" => ($request->note === null) ? 'Aucune note.' : $request->note,
            "id_voiture" => ($request->id_voiture === 'null') ? null : $request->id_voiture
        ]);
        return back()->with('message', 'L\'reparation à été créer avec succès.');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     */
    public function edit($id)
    {
        $reparation = reparation::find($id);
        $voitures = voiture::all();
        return view('form.reparation.reparationEdit',['reparation' => $reparation, 'voitures' => $voitures]);
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
            "date" => ["after:2000-01-01"],
            "montant" => ["numeric"]
        ],
        [
            "date.after" => "La date doit être valide et être après 01/01/2000.",
            "numeric" => "Le montant doit être un chiffre ex: ( 10.50)."
        ]);
        if ($validator->fails()) return back()->witherrors($validator->errors())->withInput();
        $reparation = reparation::find($id);
        if (isset($request->note)){
            $reparation->note = ($request->note === null) ? 'Aucune note.' : $request->note;
        }
        if ($request->id_voiture !== null){
            $reparation->id_voiture = ($request->id_voiture === 'vide') ? null : $request->id_voiture;
        }
        if($request->date !== null){
            $reparation->date = $request->date;
        }
        if($request->montant !== null){
            $reparation->montant = $request->montant;
        }
        if($request->type !== null){
            $reparation->type = $request->type;
        }
        if($request->nom !== null){
            $reparation->nom = $request->nom;
        }
        $reparation->update();
        return back()->with('message', 'L\'reparation à été modfié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id):void
    {
        $reparation = reparation::find($id);
        $reparation->delete();
    }
}
