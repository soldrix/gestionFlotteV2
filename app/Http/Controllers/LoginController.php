<?php

namespace App\Http\Controllers;

use App\Models\agence;
use App\Models\assurance;
use App\Models\consommation;
use App\Models\entretien;
use App\Models\fournisseur;
use App\Models\location;
use App\Models\reparation;
use App\Models\User;
use App\Models\voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    public function index(){
        $date = date('Y-m-d H:i:s', strtotime("-7 day, +1 hour"));
        $entretiens = entretien::leftJoin('voitures' ,'voitures.id', '=', 'entretiens.id_voiture')->where('entretiens.updated_at', '>=', $date)->get([
            'entretiens.*',
            'voitures.immatriculation'
        ]);
        $assurances = assurance::leftJoin('voitures' ,'voitures.id', '=', 'assurances.id_voiture')->where('assurances.updated_at', '>=', $date)->get([
            'assurances.*',
            'voitures.immatriculation'
        ]);
        $consommations = consommation::leftJoin('voitures' ,'voitures.id', '=', 'consommations.id_voiture')->where('consommations.updated_at', '>=', $date)->get([
            'consommations.*',
            'voitures.immatriculation'
        ]);
        $reparations = reparation::leftJoin('voitures' ,'voitures.id', '=', 'reparations.id_voiture')->where('reparations.updated_at', '>=', $date)->get([
            'reparations.*',
            'voitures.immatriculation'
        ]);
        $locations = location::leftJoin('voitures' ,'voitures.id', '=', 'locations.id_voiture')
            ->leftJoin('users', 'users.id', '=', 'locations.id_users')
            ->where('locations.updated_at', '>=', $date)->get([
            'locations.*',
            'voitures.immatriculation'
        ]);
        $fournisseurs = fournisseur::leftJoin('users' ,'users.id', '=', 'fournisseurs.id_users')->where('fournisseurs.updated_at', '>=', $date)->get([
            'fournisseurs.*',
            'users.email'
        ]);
        $agences = agence::all()->where('updated_at', '>=', $date);

        $voitures = voiture::leftJoin('agence', 'agence.id', '=', 'voitures.id_agence')
            ->leftJoin('fournisseurs', 'fournisseurs.id', '=', 'voitures.id_fournisseur')->where('voitures.updated_at', '>=', $date)->get([
               'fournisseurs.name',
               'voitures.*',
                'agence.ville',
                'agence.rue'
            ]);
        $users = User::where('updated_at', '>=', $date)->get();
        return view('home',['entretiens' => $entretiens, 'assurances' => $assurances, 'consommations' => $consommations, 'reparations' => $reparations, 'locations' => $locations, 'agences' => $agences, 'fournisseurs' => $fournisseurs, 'voitures' => $voitures, 'users' => $users]);
    }

    public function register(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:10|confirmed',
        ],
        [
            'required' => 'Le champ :attribute est requis.',
            'unique' => "Cette  addresse email a un compte éxistant.",
            'password.confirmed' => "Le mot de passe de confirmation ne correspond pas."
        ]);

        // Return errors if validation error occur.
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => 'user'
        ]);
        Auth::guard()->attempt($request->only('email', 'password'));
        $request->session()->regenerate();
        $token = Auth()->user()->createToken('auth_token')->plainTextToken;
        $role = Auth::user()->type;
        Auth()->user()->assignRole($role);
        return redirect('/home')->with('token','Bearer '.$token);
    }

    public function login(Request $request)
    {
        if (Auth::guard()->attempt($request->only('email', 'password'))) {
            if(auth()->guard()->check()){
                $request->user()->tokens()->delete();
            }
            $request->session()->regenerate();
            $token = Auth()->user()->createToken('auth_token')->plainTextToken;
            $role = Auth::user()->type;
            Auth()->user()->assignRole($role);
            return redirect('/home')->with('token','Bearer '.$token);
        }
        return back()->withErrors(['message'=>'Données de connexion invalides.'])->withInput();
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        $request->session()->invalidate();
        Auth::guard()->logout();
        return redirect('/login');
    }
}
