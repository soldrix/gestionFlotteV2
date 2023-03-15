<?php

namespace App\Http\Controllers;

use App\Models\agence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgenceController extends Controller
{
    /**
     * Pour récupérer toutes les agences.
     *
     */
    public function index()
    {
        $agences = agence::leftjoin('users', 'users.id', '=', 'agence.id_user')
            ->get([
                'agence.*',
                'users.first_name',
                'users.last_name',
                'users.email'
            ]);

        //retourne les données à une view
        return view('agences',['agences' => $agences]);
    }

    /**
     * Pour afficher la page de création d'une agence.
     *
     */
    public function create()
    {
        //récupère tout les utilisateurs avec le role chef agence
        $users = User::join("roles", "roles.id", "=", "users.id_role")->where('roles.name', '=', 'chef agence')->get([
            "users.*"
        ]);
        return view('form.agence.agenceCreate',['users'=> $users]);
    }

    /**
     * Pour créer une agence.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function store(Request $request)
    {
        //validator verif les valeurs
        $validator = Validator::make($request->all(),[
            "ville" => "required",
            "rue" => "required",
            "codePostal" => ["required","integer","min_digits:5", "max_digits:5"]
        ],
        [
            "required" => "Champs requis.",
            "integer" => "Code postal invalide.",
            "min_digits" => "Code postal invalide.",
            "max_digits" => "Code postal invalide."
        ]);
        //retourn les erreurs du validator
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        //stock toute les valeurs du request
        $collections = collect($request->all());
        //test la valeur de id_user
        if($collections->get('id_user') === 'vide'){
            //remplace la valeur de id_user par null
            $collections = $collections->replaceRecursive(['id_user' => null]);
        }
        agence::create($collections->all());
        return back()->with('message', 'L\'agence à été créer avec succès.');
    }


    /**
     * Pour afficher la page de modification d'une agence.
     *
     * @param  int  $id
     *
     */
    public function edit($id)
    {
        //récupère les données de l'agence
        $agence = agence::find($id);
        $users = User::join("roles", "roles.id", "=", "users.id_role")->where('roles.name', '=', 'chef agence')->get([
            "users.*"
        ]);
        return view('form.agence.agenceEdit',['agence' => $agence,'users' => $users]);
    }

    /**
     * Pour modifier les données d'une agence.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     */
    public function update(Request $request,$id)
    {
        $agence = agence::find($id);
        //array filter pour suprimer valeur null
        $validator = Validator::make(array_filter($request->all()),[
            "codePostal" => ["integer","min_digits:5", "max_digits:5"]
        ],
        [
            "integer" => "Code postal invalide.",
            "min_digits" => "Code postal invalide.",
            "max_digits" => "Code postal invalide."
        ]);
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        //stock toutes les valeurs du request et retire les valeurs null
        $collections = collect($request->all())->filter();
        //test la valeur de id_user (cette partie permet de retirer un utilisateur d'une agence)
        if($collections->get('id_user') === 'vide'){
            //remplace la valeur de id_user par null
            $collections = $collections->replaceRecursive(['id_user' => null]);
        }
        $agence->update($collections->all());
        return back()->with('message', 'L\'agence à été créer avec succès.');
    }

    /**
     * Pour supprimer une agence.
     *
     * @param  int  $id
     *
     */
    public function destroy($id):void
    {
        $agence = agence::find($id);
        $agence->delete();
    }
}
