<?php

namespace App\Http\Controllers;

use App\Models\consommation;
use App\Models\voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsommationController extends Controller
{
    /**
     * Pour récupérer toutes les consommations.
     *
     */
    public function index()
    {
        $consommations = consommation::leftJoin('voitures', 'voitures.id', '=', 'consommations.id_voiture')
            ->get([
                'consommations.*',
                'voitures.immatriculation'
            ]);
        return response(['consommations' => $consommations]);
    }

    /**
     * Pour afficher la page de création.
     *
     */
    public function create()
    {
        $voitures = voiture::all();
        return view('form.consommation.consommationCreate', ['voitures' => $voitures]);
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
            "litre" => ["required","numeric"],
            "montant" => ["required","numeric"],
            "id_voiture" => "required"
        ]);
        if ($validator->fails()) return response(['errors' => $validator->errors()]);
        $collections = collect($request->all());
        if($collections->get('id_voiture') === 'vide'){
            $collections = $collections->replaceRecursive(['id_voiture' => null]);
        }
        consommation::create($collections->all());
        return response(['message' => 'La consommation a été créer avec succès.' ]);
    }


    /**
     * Pour afficher la page de modification.
     *
     * @param  int  $id
     *
     */
    public function edit($id)
    {
        $consommation = consommation::find($id);
        $voitures = voiture::all();
        return response(['consommation' => $consommation, 'voitures' => $voitures]);
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
            "litre" => ["numeric"],
            "montant" => ["numeric"]
        ]);
        if ($validator->fails())return response(['errors' => $validator->errors()]);
        $consommation = consommation::find($id);
        $collections = collect($request->all())->filter();
        if($collections->get('id_voiture') === 'vide'){
            $collections = $collections->replaceRecursive(['id_voiture' => null]);
        }
        $consommation->update($collections->all());
        return response(['message' => 'La consommation a été modifié aevc succès.']);
    }

    /**
     * Pour supprimer.
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
