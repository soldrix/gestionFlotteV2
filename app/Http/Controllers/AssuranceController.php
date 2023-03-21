<?php

namespace App\Http\Controllers;

use App\Models\assurance;
use App\Models\voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssuranceController extends Controller
{
    /**
     * Pour récupérer toutes les assurances.
     *
     */
    public function index()
    {
        //pour récupérer toutes les assurances avec l'immatriculation de la voiture si disponible.
        $assurances = assurance::leftJoin('voitures', 'voitures.id', '=', 'assurances.id_voiture')
            ->get([
                'assurances.*',
                'voitures.immatriculation'
            ]);
        return response(['assurances' => $assurances]);
    }

    /**
     * Pour afficher la page de création d'une assurance.
     *
     */
    public function create()
    {
        //récupère toutes les voitures
        $voitures = voiture::all();
        return view('form.assurance.assuranceCreate', ['voitures' => $voitures]);
    }

    /**
     * Pour enregistrer une assurance.
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
            "frais" => ["required","numeric"],
            "id_voiture" => "required"
        ],
        [
            "required" => "le champ est requis.",
            "DateDebut.after_or_equal" => "La date de debut doit être une date valide et doit être après 01/01/2000.",
            "DateFin.after" => "La date de Fin doit être plus grande que la date de debut.",
            "frais.numeric" => "Le montant des frais doivent être des chiffre ex: ( 10.50 ) ."
        ]);
        if($validator->fails()) return response(['errors' => $validator->errors()]);
        $collections = collect($request->all())->filter();
        if($collections->get('id_voiture') === 'vide'){
            $collections = $collections->replaceRecursive(['id_voiture' => null]);
        }
        assurance::create($collections->all());
        return response(['message' => 'L\'assurance a été créer avec succès.']);
    }


    /**
     * Pour afficher la page de modification d'une agence.
     *
     * @param  int  $id
     *
     */
    public function edit($id)
    {
        $assurance = assurance::find($id);
        $voitures = voiture::all();
        return response( ['assurance' => $assurance, 'voitures' => $voitures]);
    }

    /**
     * Pour modifier les données d'une assurance.
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
        if($validator->fails()) return response(['errors' => $validator->errors()]);
        $assurance = assurance::find($id);
        //ajout à la modification des champs non vide
        $collections = collect($request->all())->filter();
        if($collections->get('id_voiture') === 'vide'){
            $collections = $collections->replaceRecursive(['id_voiture' => null]);
        }
        $assurance->update($collections->all());
        return response(['message' => 'L\'assurance a été modifié avec succès.']);
    }

    /**
     * Pour supprimer une assurance.
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
