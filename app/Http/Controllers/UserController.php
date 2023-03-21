<?php

namespace App\Http\Controllers;

use App\Models\agence;
use App\Models\fournisseur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Pour afficher touts les utilisateurs.
     *
     */
    public function index()
    {
        //vérifie le rôle de l'utilisateur pour changer de request de données
        if(Auth::user()->hasRole('RH')){

            //récupère touts les utilisateurs sauf les utilisateurs avec le rôle admin
            $users = User::join('roles', 'roles.id', '=', "users.id_role")
                ->where('roles.name', 'NOT LIKE', 'admin')
                ->get([
                    "users.*",
                    "roles.name as role"
                ]);
            return response( ['users' => $users]);
        }
        $users = User::join('roles', 'roles.id', '=', "users.id_role")
            ->get([
                "users.*",
                "roles.name as role"
            ]);
        return response(['users' => $users]);
    }

    /**
     * Pour afficher la page de création.
     *
     */
    public function create()
    {
        if(Auth::user()->hasRole('RH')){
            return view('form.utilisateur.userCreate');
        }
        $roles = Role::all();
        return view('form.utilisateur.userCreate',['roles' => $roles]);
    }

    /**
     * Pour récupérer les données d'un utilisateur.
     *
     */
    public function show(){
        $user = User::find(Auth::id());
        return view('profil',['user' => $user]);
    }


    /**
     * Pour enregistrer.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function store(Request $request)
    {
        // Validate request data
        if(Auth::user()->hasRole('RH')){
            $request->request->add(['id_role' => 1]); //add request
        }
        $validator = Validator::make(array_filter($request->all()), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'id_role' => 'required',
            'email_receiver' => 'email|required'
        ],
            [
                'required' => 'Le champ :attribute est requis.',
                'unique' => "Cette  addresse email a un compte éxistant.",
                'password.confirmed' => "Le mot de passe de confirmation ne correspond pas."
            ]);

        // Return errors if validation error occur.
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        //créer un mot de passe aléatoire
        $password = Str::random(10);
        //stock les données du request sauf la valeur email_receiver
        $collections = collect($request->all())->except(['email_receiver'])->replaceRecursive(['password' => Hash::make($password)]);
        $user = User::create($collections->all());
        $user->assignRole($request->id_role);

        $data["email"] = $request->email;
        $data["email_receiver"] = $request->email_receiver;
        $data["password"] = $password;
        $data['title'] = "Création de compte";

        Mail::send('mail.accountCreatedMail', ['data' => $data],function ($message) use ($data){
            $message->to($data['email_receiver'])->subject($data['title']);
        });


        return response(['message' => 'L\'utilisateur a été créer avec succès.']);
    }


    /**
     * Pour afficher la page de modification.
     *
     * @param  int  $id
     *
     */
    public function edit($id)
    {
        $user = User::join('roles', 'roles.id', '=', 'users.id_role')
        ->where('users.id', $id)
        ->get([
            "users.*",
            "roles.name as role"
        ]);
        if(Auth::user()->hasRole('RH')) return view('form.utilisateur.userEdit',['user' => $user[0]]);
        $roles = Role::all();
        return view('form.utilisateur.userEdit',['user' => $user[0], 'roles' => $roles]);
    }

    /**
     * Pour modifier les données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     */
    public function update(Request $request, $id)
    {
        // Validate request data
        if(Auth::user()->hasRole('RH')){
            $request->request->add(['id_role' => 1]); //add request
        }
        $validator = Validator::make(array_filter($request->all()), [
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'email' => 'email|unique:users|max:255',
            'statut' => 'string'
        ],
        [
            'required' => 'Le champ :attribute est requis.',
            'unique' => "Cette  addresse email a un compte éxistant.",
        ]);

        // Return errors if validation error occur.
        if ($validator->fails()) return response(["errors"=>$validator->errors()]);
        $user = User::find($id);

        //vérifie si l'utilisateur est relié à une agence ou un fournisseur, le/la supprime au changement de role s'il est relié
        if($request->id_role !== null){
            //retire l'ancien role
            $user->removeRole($user->id_role);
            //ajoute le nouveau role
            $user->assignRole($request->id_role);
            $agence = agence::where('id_user', $id)->get();
            if(count($agence) > 0){
                $agence = agence::find($agence[0]['id']);
                $agence->delete();
            }
        }
        if($request->statut !== null){
            $user->update(array_merge(array_filter($request->all()), ["statut" => $request->statut]));
        }else{
            $user->update(array_filter($request->all()));
        }


        return response(['message' => 'L\'utilisateur a été créer avec succès.']);
    }

    /**
     * Pour supprimer.
     *
     * @param  int  $id
     *
     */
    public function destroy($id)
    {
        //vérifie si l'utilisateur à supprimé est l'utilisateur connecté ou non
        if(intval(Auth::user()->id) == intval($id) || Auth::user()->hasRole('admin')){
            $user = User::find($id);
            $user->delete();
            //supprime la connexion de l'utilisateur connecté
            if(intval(Auth::user()->id) === intval($id)){
                Auth::user()->tokens()->delete();
                session()->invalidate();
                Auth::logout();
                return response('logout');
            }
            return response('ok',200);
        }
        return response([
            'error' => 'request unauthorized'.Auth::user()->id
        ],401);
    }


    /**
     * Desactivate user.
     *
     * @param $request Request
     * @return mixed
    **/
    public function desactivate(Request $request)
    {
            $user = User::find($request->id);
            $user->statut = 0;
            $user->update();
            return response('ok',200);
    }


    //reset password view

    public function forgetPasswordLoad()
    {
        return view('forgetPasswordForm');
    }
}
