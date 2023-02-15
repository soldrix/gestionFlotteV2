<?php

namespace App\Http\Controllers;

use App\Models\commande;
use App\Models\voiture;
use App\Models\voitureFournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class commandesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return mixed
     *
     */
    public function index()
    {
        $commandes = commande::join('voitures_fournisseur', 'voitures_fournisseur.id', '=', 'commandes.id_voiture')
        ->get([
            'voitures_fournisseur.marque',
            'voitures_fournisseur.model',
            'commandes.*'
        ]);
        return view('commandes',['commandes' => $commandes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return mixed
     */
    public function create()
    {
        $voitures = voitureFournisseur::where('statut', '=', "1")->get();
        return view('form.commandes.createCommande',['voitures' => $voitures]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
           "DateDebut" => ["required", "date"],
           "DateFin" => ["required", "date", "after:".$request->DateDebut]
        ]);
        if($validator->fails())return back()->withErrors($validator->errors())->withInput();
        commande::create([
            "DateDebut" => $request->DateDebut,
            "DateFin" => $request->DateFin,
            "id_voiture" => $request->id_voiture
        ]);
        $voiture = voitureFournisseur::find($request->id_voiture);
        $voiture->update([
            "statut" => 0
        ]);
        return back()->with('message', 'La commande a été créer avec succès.');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return mixed
     */
    public function edit($id)
    {
        $commande = commande::join('voitures_fournisseur', 'voitures_fournisseur.id', '=', 'commandes.id_voiture')->where([
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
