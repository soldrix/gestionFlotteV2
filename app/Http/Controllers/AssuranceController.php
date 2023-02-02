<?php

namespace App\Http\Controllers;

use App\Models\assurance;
use App\Models\voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $assurances = assurance::leftJoin('voitures', 'voitures.id', '=', 'assurances.id_voiture')
            ->get([
                'assurances.*',
                'voitures.immatriculation'
            ]);
        return view('assurances',['assurances' => $assurances]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $voitures = voiture::all();
        return view('form.assurance.assuranceCreate', ['voitures' => $voitures]);
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
            "DateDebut" => ["required","after_or_equal:2000-01-01"],
            "DateFin" => ["required",'after:'.$request->DateDebut],
            "frais" => ["required","numeric"]
        ],
        [
            "required" => "le champ est requis.",
            "DateDebut.after_or_equal" => "La date de debut doit être une date valide et doit être après 01/01/2000.",
            "DateFin.after" => "La date de Fin doit être plus grande que la date de debut.",
            "frais.numeric" => "Le montant des frais doivent être des chiffre ex: ( 10.50 ) ."
        ]);
        if($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        assurance::create([
            "nom" => $request->nom,
            "DateDebut" => $request->DateDebut,
            "DateFin" => $request->DateFin,
            "frais" => $request->frais,
            "id_voiture" => ($request->id_voiture === 'null') ? null : $request->id_voiture
        ]);
        return back()->with('message', 'L\'assurance a été créer avec succès.');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     */
    public function edit($id)
    {
        $assurance = assurance::find($id);
        $voitures = voiture::all();
        return view('form.assurance.assuranceEdit', ['assurance' => $assurance, 'voitures' => $voitures]);
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
            "DateDebut" => ["after_or_equal:2000-01-01"],
            "DateFin" => ['after:'.$request->DateDebut,($request->DateDebut) ? 'required' : ''],
            "frais" => ["numeric"]
        ],
        [
            "DateDebut.after_or_equal" => "La date de debut doit être une date valide et doit être après 01/01/2000.",
            "DateFin.after" => "La date de Fin doit être plus grande que la date de debut.",
            "frais.numeric" => "Le montant des frais doivent être des chiffre ex: ( 10.50 ) ."
        ]);
        if($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        $assurance = assurance::find($id);
        //ajout à la modification des champs non vide
        if($request->id_voiture){
            //pour retirer id_voiture ou changer par un autre
            $assurance->id_voiture = ($request->id_voiture === 'vide') ? null : $request->id_voiture;
        }
        if($request->DateDebut !== null){
            $assurance->DateDebut = $request->DateDebut;
        }
        if($request->DateFin !== null){
            $assurance->DateFin = $request->DateFin;
        }
        if($request->frais !== null){
            $assurance->frais = $request->frais;
        }
        if($request->nom !== null){
            $assurance->nom = $request->nom;
        }
        $assurance->update();
        return back()->with('message', 'L\'assurance a été modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     */
    public function destroy($id):void
    {
        $assurance = assurance::find($id);
        $assurance->delete();
    }
}
