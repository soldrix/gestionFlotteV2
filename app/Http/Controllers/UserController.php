<?php

namespace App\Http\Controllers;

use App\Models\agence;
use App\Models\fournisseur;
use App\Models\PasswordReset;
use App\Models\roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        if(Auth::user()->hasRole('RH')){
            $users = User::where('statut', 1)->get();
            return view('users', ['users' => $users]);
        }
        $users = User::all();
        return view('users', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $roles = roles::all();
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
            'type' => 'required|max:100',
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

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type
        ]);

        if($request->email_receiver !== null){
            $j = new \stdClass();
            $j->email    = $request->email;
            $j->email_receiver    = $request->email_receiver;
            $j->name     = $request->name;
            $j->password = $request->password;
            $mailler = new maillerController();
            $mailler->createUser($j);
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
        $user = User::find($id);
        $roles = roles::all();
        return view('form.utilisateur.userEdit',['user' => $user, 'roles' => $roles]);
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
            'type' => 'max:100',
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
            if($request->send_email === 'on'){
                $j = new \stdClass();
                $j->email = $user->email;
                $j->name = $user->name;
                $j->password = $request->password;
                $mailler = new maillerController();
                $mailler->UserPassword($j);
            }
        }


        $user->update(array_filter($cloneRequest->all()));

        //vérifie si l'utilisateur est relié à une agence ou un fournisseur, le/la supprime au changement de role s'il est relié
        if($request->type !== null){
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


        return back()->with('message','L\'utilisateur a été créer avec succès.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     */
    public function profilUpdate(Request $request, $id){
        // Validate request data
        $validator = Validator::make(array_filter($request->all()), [
            'name' => 'string|max:255',
            'email' => 'email|unique:users|max:255',
            'password' => 'min:10|required',
        ],
        [
            'required' => 'Le mot de passe est requis.',
            'unique' => "Cette  addresse email a un compte éxistant.",
        ]);

        // Return errors if validation error occur.
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        $user = User::find($id);
        $cloneRequest = clone $request;
        unset($cloneRequest->password);
        if(Auth::guard()->attempt(['email' => Auth::user()->email,'password' => $request->password]))
        {
            $user->update(array_filter($cloneRequest->all()));
            return back()->with('message','L\'utilisateur a été créer avec succès.');
        }
        return back()->withErrors([
            'password' => 'Le mot de passe de connexion est invalide'
        ])->withInput();
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
            'new_password' => 'required|min:10|confirmed'
        ]);
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        //vérifie si l'utilisateur est connecté
        if(auth()->guard()->check()){
            //supprime tous les tokens de l'utilisateur et le déconnecte
            $request->user()->tokens()->delete();
            $request->session()->invalidate();
            Auth::guard()->logout();
        }
        //connect l'utilisateur
        if(Auth::guard()->attempt($request->only('email', 'password'))){
            $request->session()->regenerate();
            //récupère l'utilisateur
            $user = User::find(Auth::user()->id);
            //modifie le mot de passe de l'utilisateur par le nouveau mot de passe
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
            //déconnect l'utlisateur
            $request->session()->invalidate();
            Auth::guard()->logout();
            //redirige l'utilisateur à la page login
            return redirect('/login')->with('message', 'Le mot de passe a été modifier avec succès.');
        }
        return back()->withErrors(['message'=>'Données de connexion invalides.'])->withInput();
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
