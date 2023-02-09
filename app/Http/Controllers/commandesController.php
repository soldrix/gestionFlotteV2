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
        $commandes = commande::all();
        return view('commandes',['commandes' => $commandes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return mixed
     */
    public function create()
    {
        $voitures = voitureFournisseur::all();
        return view('form.createCommandes',['voitures' => $voitures]);
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
        return back()->with('message', 'la comande a été créer avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
