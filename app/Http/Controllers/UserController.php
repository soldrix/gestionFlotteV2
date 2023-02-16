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
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        if(Auth::user()->hasRole('RH')){
            $users = User::join('roles', 'roles.id', '=', "users.id_role")
                ->where('statut', 1)
                ->get([
                    "users.*",
                    "roles.name as role"
                ]);
            return view('users', ['users' => $users]);
        }
        $users = User::join('roles', 'roles.id', '=', "users.id_role")
            ->get([
                "users.*",
                "roles.name as role"
            ]);
        return view('users', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $roles = Role::all();
        return view('form.utilisateur.userCreate',['roles' => $roles]);
    }

    public function show(){
        $user = User::find(Auth::id());
        return view('profil',['user' => $user]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make(array_filter($request->all()), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'id_role' => 'required',
            'password' => 'required|min:10|confirmed',
            'email_receiver' => 'email'
        ],
            [
                'required' => 'Le champ :attribute est requis.',
                'unique' => "Cette  addresse email a un compte éxistant.",
                'password.confirmed' => "Le mot de passe de confirmation ne correspond pas."
            ]);

        // Return errors if validation error occur.
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_role' => $request->id_role
        ]);
        $user->assignRole($request->id_role);

        if($request->email_receiver !== null){
            $data["email"] = $request->email;
            $data["email_receiver"] = $request->email_receiver;
            $data["password"] = $request->password;
            $data['title'] = "Création de compte";

            Mail::send('mail.accountCreatedMail', ['data' => $data],function ($message) use ($data){
                $message->to($data['email_receiver'])->subject($data['title']);
            });
        }

        return back()->with('message','L\'utilisateur a été créer avec succès.');
    }


    /**
     * Show the form for editing the specified resource.
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
        $roles = Role::all();
        return view('form.utilisateur.userEdit',['user' => $user[0], 'roles' => $roles]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     */
    public function editProfil($id)
    {
        $user = User::find($id);
        return view('form.utilisateur.profilEdit',['user' => $user]);
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
        // Validate request data

        $validator = Validator::make(array_filter($request->all()), [
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'email' => 'email|unique:users|max:255',
            'statut' => 'string',
            'password' => 'min:10'
        ],
        [
            'required' => 'Le champ :attribute est requis.',
            'unique' => "Cette  addresse email a un compte éxistant.",
        ]);

        // Return errors if validation error occur.
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        $user = User::find($id);
        $cloneRequest = clone $request;
        unset($cloneRequest->password);
        if($request->password !== null){
            $cloneRequest->merge(['password' => Hash::make($request->password)]);
        }

        //vérifie si l'utilisateur est relié à une agence ou un fournisseur, le/la supprime au changement de role s'il est relié
        if($request->id_role !== null){
            $user->removeRole($user->id_role);
            $user->assignRole($request->id_role);
            $fournisseur = fournisseur::where('id_users' , $id)->get();
            if(count($fournisseur) > 0){
                $fournisseur = fournisseur::find($fournisseur[0]['id']);
                $fournisseur->delete();
            }
            $agence = agence::where('id_user', $id)->get();
            if(count($agence) > 0){
                $agence = agence::find($agence[0]['id']);
                $agence->delete();
            }
        }
        if($request->statut !== null){
            $user->update(array_merge(array_filter($cloneRequest->all()), ["statut" => $request->statut]));
        }else{
            $user->update(array_filter($cloneRequest->all()));
        }


        return back()->with('message','L\'utilisateur a été créer avec succès.');
    }

    /**
     * Remove the specified resource from storage.
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
     * @param int $id
     * @return mixed
    **/
    public function desactivate($id)
    {
        if(Auth::user()->hasRole(['admin', 'RH'])){
            $user = User::find($id);
            $user->statut = 0;
            $user->update();
            return response('ok',200);
        }
        return response([
            'error' => 'request unauthorized'.Auth::user()->id
        ],401);
    }

    //reset password view

    public function forgetPasswordLoad()
    {
        return view('forgetPasswordForm');
    }
}
