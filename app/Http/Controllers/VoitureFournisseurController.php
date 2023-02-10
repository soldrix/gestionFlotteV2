<?php

namespace App\Http\Controllers;

use App\Models\agence;
use App\Models\assurance;
use App\Models\consommation;
use App\Models\entretien;
use App\Models\fournisseur;
use App\Models\reparation;
use App\Models\voitureFournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VoitureFournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $voitures = voitureFournisseur::join('fournisseurs', 'fournisseurs.id', '=', 'voitures_fournisseur.id_fournisseur')
            ->get([
                'fournisseurs.name',
                'voitures_fournisseur.*'
            ]);
        return view('voituresFournisseur',["voitures"=>$voitures]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fournisseurs = fournisseur::leftjoin('users', 'users.id', '=', 'fournisseurs.id_users')
            ->get([
            'users.email',
            'fournisseurs.*'
        ]);
        return view('form.voiture_fournisseur.voiture_fournisseurCreate', ['fournisseurs' => $fournisseurs]);
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
            "puissance" => ["required", "integer"],
            "type" => "required",
            "statut" => ["required", "integer", "max_digits:1"]
        ]);
        if($validator->fails()) return back()->withErrors($validator->errors())->withInput();
//        ajout l'image dans le storage
        $path = Storage::putFile('image', $request->image);
         voitureFournisseur::create([
            "image" => $path,
            "marque" => $request->marque,
            "model" => $request->model,
            "carburant" => $request->carburant,
            "puissance" => $request->puissance,
            "type" => $request->type,
            "statut" => $request->statut,
            "id_fournisseur" => $request->id_fournisseur
        ]);
        return back()->with('message', 'La voiture à été créer avec succès.');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     */
    public function edit($id)
    {
        $voiture = voitureFournisseur::find($id);
        $fournisseurs = fournisseur::join('users', 'users.id', '=', 'fournisseurs.id_users')
            ->get([
                'fournisseurs.*',
                'users.email'
            ]);
        return view("form.voiture_fournisseur.voiture_fournisseurEdit",['voiture' => $voiture, 'fournisseurs' => $fournisseurs]);
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
            "puissance" => ["integer"],
            "statut" => ["integer", "max_digits:1"]
        ]);
        if($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        $voiture = voitureFournisseur::find($id);

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
            $voiture = voitureFournisseur::find($id);
            Storage::delete($voiture->image);
            $voiture->delete();
    }
}
