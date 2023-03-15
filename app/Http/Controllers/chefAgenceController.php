<?php

namespace App\Http\Controllers;

use App\Models\agence;
use App\Models\voiture;
use Illuminate\Http\Request;

class chefAgenceController extends Controller
{
    /**
     * Pour récupérer toutes les agences par rapport a un chef d'agence.
     *
     * @return mixed
     */
    public function index()
    {
        $agences = agence::leftjoin('users', 'users.id', '=', 'agence.id_user')->get([
            "agence.*",
            "users.first_name",
            "users.last_name",
            "users.email"
        ]);
        return view('chefAgence',['agences' => $agences]);
    }
    /**
     * Pour récuprer toutes les voitures d'une agence.
     * @param int $id
     * @return mixed
     */
    public function indexVoiture($id)
    {
        $voitures = voiture::where('id_agence', '=', $id)->get();
        return view('voituresAgence',['voitures' => $voitures]);
    }

    /**
     * Pour afficher la page de modification de statut d'une voiture.
     *
     * @param  int  $id
     * @return mixed
     */
    public function edit($id)
    {
        $voiture = voiture::find($id);
        return view('form.voiture.statutEdit',['voiture' => $voiture]);
    }

    /**
     * Pour modifier le statut d'une voiture.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        //vérifie que le statut n'est pas vide sinon retourne un message d'erreur
        if($request->statut === null) return back()->withErrors(['statut' => 'Le statut ne peut pas être vide.'])->withInput();
        $voiture = voiture::find($id);
        //modifie le statut
        $voiture->update($request->all());
        return back()->with('message', 'Modification réaliser avec succès.');
    }

}
