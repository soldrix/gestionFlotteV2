<?php

namespace App\Http\Controllers;

use App\Models\assurance;
use App\Models\consommation;
use App\Models\entretien;
use App\Models\reparation;
use Illuminate\Http\Request;
use App\Models\voiture;
use App\Models\agence;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VoitureController extends Controller
{
    /**
     * Pour récupérer toutes les voitures.
     *
     */
    public function index()
    {
        $voitures = voiture::all();
        return view('voitures',["voitures"=>$voitures]);
    }
    /**
     * Pour afficher la page de création.
     */
    public function create()
    {
        $agences = agence::all();
        return view('form.voiture.voitureCreate',['agences'=>$agences]);
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
            "image" => ["required","image","mimes:jpg,png,jpeg,gif,svg","max:2048"],
            "carburant" => ["required"],
            "circulation" => ["required",'date_format:Y-m-d'],
            "immatriculation" => ["required", "regex:#[A-Z]{2,4}[\s-]{0,1}[0-9]{1,3}[\s-]{0,1}[A-Z]{2}#"],
            "puissance" => ["required", "integer"],
            "type" => "required",
            "nbPorte" => ["required", "integer"],
            "nbPlace" => ["required", "integer"],
            "prix" => ["required", "numeric"],
            "statut" => ["required", "integer", "max_digits:1"],
            "id_agence" => "required"
        ],
        [
            "required" => "Champs requis.",
            "image" => "Le fichier doit être une image.",
            "mimes" => "Le fichier doit être au format : jpg, png, jpeg, gif ou svg .",
            "regex" => "Doit être correspondre à exemple : AA-150-AA ."
        ]);
        if($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        //ajout l'image dans le storage
        $path = Storage::putFile('image', $request->image);
        $collections = collect($request->all())->replaceRecursive(['image' => $path]);
        if($collections->get('id_agence') === "vide"){
            $collections = $collections->replaceRecursive(['id_agence' => null]);
        }
        voiture::create($collections->all());
        return back()->with('message', 'La voiture à été créer avec succès.');
    }

    /**
     * Pour récupérer les données d'une voiture.
     *
     * @param  int  $id
     *
     */
    public function show($id)
    {
        $voiture = voiture::leftjoin('agence',  'agence.id', '=', 'voitures.id_agence')
            ->where('voitures.id',$id)
            ->get([
                'voitures.*',
                'agence.ville',
                'agence.rue'
            ]);
        //pour récupérer les entretiens par rapport au la voiture
        $entretiens = entretien::where('id_voiture', $id)->get();
        //pour récupérer les assurances par rapport au la voiture
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
     * Pour afficher la page de modification.
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
     * Pour modifier.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     */
    public function update(Request $request,$id)
    {
        $validator =Validator::make(array_filter($request->all()),[
            "image" => ["image","mimes:jpg,png,jpeg,gif,svg","max:2048"],
            "circulation" => ['date_format:Y-m-d'],
            "puissance" => ["integer"],
            "prix" => ["numeric"],
            "nbPlace" => ["numeric"],
            "nbPorte" => ["numeric"],
            "statut" => ["integer", "max_digits:1"],
            "immatriculation" => ["regex:#[A-Z]{2,4}[\s-]{0,1}[0-9]{1,3}[\s-]{0,1}[A-Z]{2}#"]
        ],
        [
            "image" => "Le fichier doit être une image.",
            "mimes" => "Le fichier doit être au format : jpg, png, jpeg, gif ou svg .",
            "regex" => "Doit être correspondre à exemple : AA-150-AA ."
        ]);
        if($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        $voiture = voiture::find($id);
        $collections = collect($request->all())->filter();
        if($collections->get('id_agence') === "vide"){
            $collections = $collections->replaceRecursive(["id_agence" => null]);
        }
        if($collections->get('image') !== null){
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
        $voiture = voiture::find($id);
        Storage::delete($voiture->image);
        $voiture->delete();
    }
}
