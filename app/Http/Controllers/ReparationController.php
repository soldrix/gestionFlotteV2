<?php

namespace App\Http\Controllers;

use App\Models\reparation;
use App\Models\voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReparationController extends Controller
{
    /**
     * Pour récupérer toutes les réparations.
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
     * Pour afficher la page de création.
     *
     */
    public function create()
    {
        $voitures = voiture::all();
        return view('form.reparation.reparationCreate',["voitures" => $voitures]);
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
            "nom" => "required",
            "type" => "required",
            "date" => ["required","after:2000-01-01"],
            "montant" => ["required","numeric"],
            "id_voiture" => "required"
        ],
        [
            "required" => "Le champ est requis.",
            "date.after" => "La date doit être valide et être après 01/01/2000.",
            "numeric" => "Le montant doit être un chiffre ex: ( 10.50)."
        ]);
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        $collections = collect($request->all());
        if ($collections->get('note') === null){
            $collections = $collections->replaceRecursive(['note' => 'Aucune note.']);
        }
        if ($collections->get('id_voiture') === "vide"){
            $collections = $collections->replaceRecursive(['id_voiture' => null]);
        }
        reparation::create($collections->all());
        return back()->with('message', 'L\'reparation à été créer avec succès.');
    }


    /**
     * Pour afficher la page de modification.
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
     * Pour modifier.
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
        $collections = collect($request->all())->filter();
        //tester et supprimer une note
        if($collections->get('empty_note') === "on"){
            $collections = $collections->mergeRecursive(['note' => 'Aucune note.']);
        }
        if($collections->get('id_voiture') === 'vide'){
            $collections = $collections->replaceRecursive(['id_voiture' => null]);
        }
        $reparation->update($collections->all());
        return back()->with('message', 'L\'reparation à été modfié avec succès.');
    }

    /**
     * Pour supprimer.
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
