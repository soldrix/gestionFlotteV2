<?php

namespace App\Http\Controllers;

use App\Models\entretien;
use App\Models\voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EntretienController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $entretiens = entretien::leftJoin('voitures', 'voitures.id', '=', 'entretiens.id_voiture')
            ->get([
                'entretiens.*',
                'voitures.immatriculation'
            ]);
        return view('entretiens',['entretiens' => $entretiens]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $voitures = voiture::all();
        return view('form.entretien.entretienCreate',["voitures" => $voitures]);
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
        entretien::create([
            "nom" => $request->nom,
            "type" => $request->type,
            "date" => $request->date,
            "montant" => $request->montant,
            "note" => ($request->note === null) ? 'Aucune note.' : $request->note,
            "id_voiture" => ($request->id_voiture === 'null') ? null : $request->id_voiture
        ]);
        return back()->with('message', 'L\'entretien à été créer avec succès.');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     */
    public function edit($id)
    {
        $entretien = entretien::find($id);
        $voitures = voiture::all();
        return view('form.entretien.entretienEdit',['entretien' => $entretien, 'voitures' => $voitures]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     */
    public function update(Request $request,$id)
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
        $entretien = entretien::find($id);
        if (isset($request->note)){
            $entretien->note = ($request->note === null) ? 'Aucune note.' : $request->note;
        }
        if ($request->id_voiture !== null){
            $entretien->id_voiture = ($request->id_voiture === 'vide') ? null : $request->id_voiture;
        }
        if($request->date !== null){
            $entretien->date = $request->date;
        }
        if($request->montant !== null){
            $entretien->montant = $request->montant;
        }
        if($request->type !== null){
            $entretien->type = $request->type;
        }
        if($request->nom !== null){
            $entretien->nom = $request->nom;
        }
        $entretien->update();
        return back()->with('message', 'L\'entretien à été modfié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     */
    public function destroy($id):void
    {
        $entretien = entretien::find($id);
        $entretien->delete();
    }
}
