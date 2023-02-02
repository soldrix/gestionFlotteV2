<?php

namespace App\Http\Controllers;

use App\Models\consommation;
use App\Models\voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsommationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $consommations = consommation::leftJoin('voitures', 'voitures.id', '=', 'consommations.id_voiture')
            ->get([
                'consommations.*',
                'voitures.immatriculation'
            ]);
        return view('consommations', ['consommations' => $consommations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $voitures = voiture::all();
        return view('form.consommation.consommationCreate', ['voitures' => $voitures]);
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
            "litre" => ["required","numeric"],
            "montant" => ["required","numeric"]
        ]);
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        consommation::create([
            "litre" => $request->litre,
            "montant" => $request->montant,
            "id_voiture" => ($request->id_voiture === 'null') ? null : $request->id_voiture
        ]);
        return back()->with('message', 'La consommation a été créer avec succès.');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     */
    public function edit($id)
    {
        $consommation = consommation::find($id);
        $voitures = voiture::all();
        return view('form.consommation.consommationEdit', ['consommation' => $consommation, 'voitures' => $voitures]);
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
            "litre" => ["numeric"],
            "montant" => ["numeric"]
        ]);
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        $consommation = consommation::find($id);
        if($request->id_voiture !== null){
            $consommation->id_voiture = ($request->id_voiture !== 'vide') ? $request->id_voiture : null;
        }
        if($request->litre !== null){
            $consommation->litre = $request->litre;
        }
        if($request->montant !== null){
            $consommation->montant = $request->montant;
        }
        $consommation->update();
        return back()->with('message', 'La consommation a été modifié aevc succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     */
    public function destroy($id):void
    {
        $consommation = consommation::find($id);
        $consommation->delete();
    }
}
