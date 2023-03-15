<?php

namespace App\Http\Controllers;

use App\Models\entretien;
use App\Models\voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EntretienController extends Controller
{
    /**
     * Pour récupérer les entretiens.
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
     * Pour afficher la page de création.
     *
     */
    public function create()
    {
        $voitures = voiture::all();
        return view('form.entretien.entretienCreate',["voitures" => $voitures]);
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
        if($collections->get('note') === null){
            $collections = $collections->replaceRecursive(['note' => 'Aucune note.']);
        }
        if($collections->get('id_voiture') === 'vide'){
            $collections = $collections->replaceRecursive(['id_voiture' => null]);
        }
        entretien::create($collections->all());
        return back()->with('message', 'L\'entretien à été créer avec succès.');
    }


    /**
     * Pour afficher la page de modification.
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
     * Pour modifier.
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
        $collections = collect($request->all())->filter();
        //pour vérifier si l'utilisateur veut supprimer la note, si oui supprimer la note
        if($collections->get('empty_note') === 'on'){
           $collections = $collections->mergeRecursive(['note'=> 'Aucune note.']);
        }
        if ($collections->get('id_voiture') === 'vide'){
           $collections = $collections->replaceRecursive(["id_voiture" => null]);
        }
        $entretien->update($collections->all());
        return back()->with('message', 'L\'entretien à été modfié avec succès.');
    }

    /**
     * Pour supprimer.
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
