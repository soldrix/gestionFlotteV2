<?php

namespace App\Http\Controllers;

use App\Models\agence;
use App\Models\voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class chefAgenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        $agences = agence::all();
        return view('chefAgence',['agences' => $agences]);
    }
    /**
     * Display a listing of the resource.
     * @param int $id
     * @return mixed
     */
    public function indexVoiture($id)
    {
        $voitures = voiture::all()->where('id_agence', '=', $id);
        return view('voituresAgence',['voitures' => $voitures]);
    }

    /**
     * Show the form for editing the specified resource.
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->statut === null) return back()->withErrors(['statut' => 'Le statut ne peut pas être vide.'])->withInput();
        $voiture = voiture::find($id);
        $voiture->update($request->all());
        return back()->with('message', 'Modification réaliser avec succès.');
    }

}
