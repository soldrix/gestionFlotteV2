<?php

namespace App\Http\Controllers;

use App\Models\fournisseur;
use App\Models\voitureFournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VoitureFournisseurController extends Controller
{
    /**
     * Pour récupérer toutes les voitures fournisseurs.
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
     * Pour afficher la page de création.
     */
    public function create()
    {
        $fournisseurs = fournisseur::all();
        return view('form.voiture_fournisseur.voiture_fournisseurCreate', ['fournisseurs' => $fournisseurs]);
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
            "marque" => "required",
            "model"  => "required",
            "image" => ["required","image","mimes:jpg,png,jpeg,gif,svg","max:2048","dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000"],
            "carburant" => ["required"],
            "puissance" => ["required", "integer"],
            "type" => "required",
            "statut" => ["required", "integer", "max_digits:1"],
            "id_fournisseur" => "required"
        ]);
        if($validator->fails()) return back()->withErrors($validator->errors())->withInput();
//        ajout l'image dans le storage
        $path = Storage::putFile('image', $request->image);
        $collections = collect($request->all())->replaceRecursive(['image' => $path]);
         voitureFournisseur::create($collections->all());
        return back()->with('message', 'La voiture à été créer avec succès.');
    }


    /**
     * Pour afficher la page de modification.
     *
     * @param  int  $id
     *
     */
    public function edit($id)
    {
        $voiture = voitureFournisseur::find($id);
        $fournisseurs = fournisseur::all();
        return view("form.voiture_fournisseur.voiture_fournisseurEdit",['voiture' => $voiture, 'fournisseurs' => $fournisseurs]);
    }

    /**
     * Pour modifier.
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
        $collections = collect($request->all())->filter();

        if ($collections->get('image') !== null){
            //si l'image supprime l'ancienne image du storage
            Storage::delete($voiture->image);
            //ajout la nouvelle image dans le storage
            $path =  Storage::putFile('image', $request->image);
            //ajoute le chemin de l'image dans la modification
            $collections = $collections->replaceRecursive(['image' => $path]);
        }
        $voiture->update($collections->all());
        return back()->with('message', 'La modification à été réaliser avec succès.');
    }

    /**
     * Pour supprimer.
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
