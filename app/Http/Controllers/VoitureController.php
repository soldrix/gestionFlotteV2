<?php

namespace App\Http\Controllers;

use App\Models\assurance;
use App\Models\consommation;
use App\Models\entretien;
use App\Models\fournisseur;
use App\Models\reparation;
use Illuminate\Http\Request;
use App\Models\voiture;
use App\Models\agence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VoitureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $voitures = voiture::all();
        return view('voitures',["voitures"=>$voitures]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $agences = agence::all();
        return view('form.voiture.voitureCreate',['agences'=>$agences]);
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
            "marque" => "required",
            "model"  => "required",
            "image" => ["required","image","mimes:jpg,png,jpeg,gif,svg","max:2048","dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000"],
            "carburant" => ["required"],
            "circulation" => ["required",'date_format:Y-m-d'],
            "immatriculation" => ["required", "regex:#[A-Z]{2,4}[\s-]{0,1}[0-9]{1,3}[\s-]{0,1}[A-Z]{2}#"],
            "puissance" => ["required", "integer"],
            "type" => "required",
            "nbPorte" => ["required", "integer"],
            "nbPlace" => ["required", "integer"],
            "prix" => ["required", "numeric"],
            "statut" => ["required", "integer", "max_digits:1"]
        ]);
        if($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        //ajout l'image dans le storage
        $path = Storage::putFile('image', $request->image);
        voiture::create([
            "image" => $path,
            "marque" => $request->marque,
            "model" => $request->model,
            "carburant" => $request->carburant,
            "circulation" => $request->circulation,
            "immatriculation" => $request->immatriculation,
            "puissance" => $request->puissance,
            "type" => $request->type,
            "nbPorte" => $request->nbPorte,
            "nbPlace" => $request->nbPlace,
            "prix" => $request->prix,
            "statut" => $request->statut,
            "id_agence" => ($request->id_agence !== 'null') ? $request->id_agence : null,
        ]);
        return back()->with('message', 'La voiture à été créer avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     */
    public function show($id)
    {
        $voiture = voiture::find($id);
        return view('voiture',['voiture' => $voiture]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     */
    public function adminShow($id)
    {
        $voiture = voiture::leftjoin('agence',  'agence.id', '=', 'voitures.id_agence')
            ->where('voitures.id',$id)
            ->get([
                'voitures.*',
                'agence.ville',
                'agence.rue'
            ]);
        $entretiens = entretien::where('id_voiture', $id)->get();
        $assurances = assurance::where('id_voiture', $id)->get();
        $reparations = reparation::where('id_voiture' ,$id)->get();
        $consommations =consommation::where('id_voiture', $id)->get();
        return view('voiture',
            [
                'voitureData' => $voiture,
                'nbEnt' => count($entretiens),
                'nbAssu' => count($assurances),
                'nbRep' => count($reparations),
                'nbCons' => count($consommations),
                'entretiens' => $entretiens,
                'assurances' => $assurances,
                'reparations' => $reparations,
                'consommations' => $consommations
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     */
    public function edit($id)
    {
        $voiture = voiture::find($id);
        $agences = agence::all();
        return view("form.voiture.voitureEdit",['voiture' => $voiture, 'agences' => $agences]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     */
    public function update(Request $request,$id)
    {
        $validator =Validator::make(array_filter($request->all()),[
            "image" => ["image","mimes:jpg,png,jpeg,gif,svg","max:2048","dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000"],
            "circulation" => ['date_format:Y-m-d'],
            "puissance" => ["integer"],
            "prix" => ["numeric"],
            "nbPlace" => ["numeric"],
            "nbPorte" => ["numeric"],
            "statut" => ["integer", "max_digits:1"],
            "immatriculation" => ["regex:#[A-Z]{2,4}[\s-]{0,1}[0-9]{1,3}[\s-]{0,1}[A-Z]{2}#"]
        ]);
        if($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        $voiture = voiture::find($id);

        if($request->id_agence !== null){
           $voiture->id_agence = ($request->id_agence === 'vide') ? null : $request->id_agence;
        }
        if($request->circulation !== null){
            $voiture->circulation = $request->circulation;
        }
        if($request->puissance !== null){
            $voiture->puissance = $request->puissance;
        }
        if($request->prix !== null){
            $voiture->prix = $request->prix;
        }
        if($request->statut !== null){
            $voiture->statut = $request->statut;
        }
        if($request->marque !== null){
            $voiture->marque = $request->marque;
        }
        if($request->model !== null){
            $voiture->model = $request->model;
        }
        if($request->carburant !== null){
            $voiture->carburant = $request->carburant;
        }
        if($request->type !== null){
            $voiture->type = $request->type;
        }
        if($request->nbPorte !== null){
            $voiture->nbPorte = $request->nbPorte;
        }
        if($request->nbPlace !== null){
            $voiture->nbPlace = $request->nbPlace;
        }
        if($request->immatriculation !== null){
            $voiture->immatriculation = $request->immatriculation;
        }
        if ($request->image !== null){
            //si l'image supprime l'ancienne image du storage
            Storage::delete($voiture->image);
            //ajout la nouvelle image dans le storage
            $path =  Storage::putFile('image', $request->image);
            //ajoute le chemin de l'image dans la modification
            $voiture->image = $path;
        }

        $voiture->update();
        return back()->with('message', 'La modification à été réaliser avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     */
    public function destroy($id):void
    {
        $voiture = voiture::find($id);
        Storage::delete($voiture->image);
        $voiture->delete();
    }
}
