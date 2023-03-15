<?php

namespace App\Http\Controllers;

use App\Models\commande;
use App\Models\voitureFournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class commandesController extends Controller
{
    /**
     * récupérer toutes les commandes.
     *
     * @return mixed
     *
     */
    public function index()
    {
        //récupérer toutes les commandes par rapport au fournisseur
        $commandes = commande::join('voitures_fournisseur', 'voitures_fournisseur.id', '=', 'commandes.id_voiture_fournisseur')
        ->get([
            'voitures_fournisseur.marque',
            'voitures_fournisseur.model',
            'commandes.*'
        ]);
        return view('commandes',['commandes' => $commandes]);
    }

    /**
     * Pour afficher la page de création.
     *
     * @return mixed
     */
    public function create()
    {
        $voitures = voitureFournisseur::where('statut', '=', "1")->get();
        return view('form.commandes.createCommande',['voitures' => $voitures]);
    }

    /**
     * Pour enregistrer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
           "DateDebut" => ["required", "date"],
           "DateFin" => ["required", "date", "after:".$request->DateDebut],
            "id_voiture_fournisseur" => "required"
        ],
        [
            "required" => "Champs requis.",
            "date" => "Doit être une date valide.",
            "after" => "Doit être après la date de debut."
        ]);
        if($validator->fails())return back()->withErrors($validator->errors())->withInput();
        commande::create($request->all());
        $voiture = voitureFournisseur::find($request->id_voiture_fournisseur);
        $voiture->update([
            "statut" => 0
        ]);
        return back()->with('message', 'La commande a été créer avec succès.');
    }


    /**
     * Pour afficher la page de modification.
     *
     * @param  int  $id
     * @return mixed
     */
    public function edit($id)
    {
        $commande = commande::join('voitures_fournisseur', 'voitures_fournisseur.id', '=', 'commandes.id_voiture_fournisseur')->where([
            'commandes.id' => $id
        ])
        ->get([
            'voitures_fournisseur.marque',
            'voitures_fournisseur.model',
            'commandes.*'
        ]);
        $voitures = voitureFournisseur::where('statut', '=', "1")->get();
        return view('form.commandes.editCommande',['commande' => $commande[0], "voitures" => $voitures]);
    }

    /**
     * Pour modifier les données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(array_filter($request->all()),[
            "DateDebut" => ["date","after_or_equal:2000-01-01"],
            "DateFin" => [($request->DateDebut !== null) ? "required" : "", "date", "after:".$request->DateDebut]
        ],[
            "after" => "la date doit être après la date de debut."
        ]);
        if($validator->fails())return back()->withErrors($validator->errors())->withInput();
        $commande = commande::find($id);
        $commande->update(array_filter($request->all()));
        return back()->with('message', 'La commande a été modifié avec succès.');
    }

    /**
     * Pour supprimer.
     *
     * @param  int  $id
     * @return void
     */
    public function destroy($id)
    {
        $commande = commande::find($id);
        $commande->delete();
    }
}
