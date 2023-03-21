<?php

namespace App\Http\Controllers;

use App\Models\agence;
use App\Models\assurance;
use App\Models\commande;
use App\Models\consommation;
use App\Models\entretien;
use App\Models\fournisseur;
use App\Models\location;
use App\Models\reparation;
use App\Models\User;
use App\Models\voiture;
use App\Models\voitureFournisseur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Pour récupérer toutes les dernières modifications des sept derniers jours.
     *
     *
     * @return mixed
     *
     */
    public function index(){
        $date = date('Y-m-d H:i:s', strtotime("-7 day, +1 hour"));

        //récupère touts les entretiens créer ou modifier depuis les 7 derniers jours
        $entretiens = entretien::leftJoin('voitures' ,'voitures.id', '=', 'entretiens.id_voiture')->where('entretiens.updated_at', '>=', $date)->get([
            'entretiens.*',
            'voitures.immatriculation'
        ]);
        //récupère toutes les assurances créer ou modifier depuis les 7 derniers jours
        $assurances = assurance::leftJoin('voitures' ,'voitures.id', '=', 'assurances.id_voiture')->where('assurances.updated_at', '>=', $date)->get([
            'assurances.*',
            'voitures.immatriculation'
        ]);
        //récupère toutes les consommations créer ou modifier depuis les 7 derniers jours
        $consommations = consommation::leftJoin('voitures' ,'voitures.id', '=', 'consommations.id_voiture')->where('consommations.updated_at', '>=', $date)->get([
            'consommations.*',
            'voitures.immatriculation'
        ]);
        //récupère toutes les réparations créer ou modifier depuis les 7 derniers jours
        $reparations = reparation::leftJoin('voitures' ,'voitures.id', '=', 'reparations.id_voiture')->where('reparations.updated_at', '>=', $date)->get([
            'reparations.*',
            'voitures.immatriculation'
        ]);
        //récupère toutes les locations créer ou modifier depuis les 7 derniers jours
        $locations = location::leftJoin('voitures' ,'voitures.id', '=', 'locations.id_voiture')
            ->leftJoin('users', 'users.id', '=', 'locations.id_user')
            ->where('locations.updated_at', '>=', $date)->get([
            'locations.*',
            'voitures.immatriculation'
        ]);
        //récupère touts les fournisseurs créer ou modifier depuis les 7 derniers jours
        $fournisseurs = fournisseur::where('updated_at', '>=', $date)->get();
        //récupère toutes les agences créer ou modifier depuis les 7 derniers jours
        $agences = agence::where('updated_at', '>=', $date)->get();
        //récupère toutes les voitures créer ou modifier depuis les 7 derniers jours
        $voitures = voiture::leftJoin('agence', 'agence.id', '=', 'voitures.id_agence')->where('voitures.updated_at', '>=', $date)->get([
               'voitures.*',
                'agence.ville',
                'agence.rue'
            ]);
        //récupère touts les utilisateurs créer ou modifier depuis les 7 derniers jours
        $users = User::join('roles', 'roles.id', '=', 'users.id_role')->where('users.updated_at', '>=', $date)->get([
            "users.*",
            "roles.name as role"
        ]);
        $commandes = commande::join('voitures_fournisseur', 'voitures_fournisseur.id', '=', 'commandes.id_voiture_fournisseur')->where('commandes.updated_at', '>=', $date)->get([
            'commandes.*',
            'voitures_fournisseur.marque',
            'voitures_fournisseur.model'
        ]);
        $voitures_fournisseurs= voitureFournisseur::join('fournisseurs', 'fournisseurs.id', '=', 'voitures_fournisseur.id_fournisseur')->where('voitures_fournisseur.updated_at', '>=', $date)->get([
           "voitures_fournisseur.*",
            "fournisseurs.name",
            "fournisseurs.email"
        ]);
        return view('home',
            [
                'entretiens' => $entretiens,
                'assurances' => $assurances,
                'consommations' => $consommations,
                'reparations' => $reparations,
                'locations' => $locations,
                'agences' => $agences,
                'fournisseurs' => $fournisseurs,
                'voitures' => $voitures,
                'users' => $users,
                'commandes' => $commandes ,
                'voitures_fournisseurs' => $voitures_fournisseurs
            ]);
    }
    /**
     * Pour enregistrer un utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return   mixed
     *
     */
    public function register(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
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
        $collections = collect($request->all())->merge(["id_role" => 1])->replaceRecursive(['password' => Hash::make($request->password)]);
        $user = User::create($collections->all());
        //ajoute le role user
        $user->assignRole(1);

        //login l'utilisateur apres création du compte
        Auth::guard()->attempt($request->only('email', 'password'));
        $request->session()->regenerate();
        date_default_timezone_set('Europe/Paris');
        $token = Auth()->user()->createToken('auth_token',['*'],Carbon::now()->addMinutes(config('session.lifetime')))->plainTextToken;
        return redirect('/home')->with('token','Bearer '.$token);
    }

    /**
     * Pour connecter un utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return  mixed
     *
     */
    public function login(Request $request)
    {
        $user = User::where('email', '=', $request->email)->get();
        if(count($user) < 1 ) return back()->withErrors(['message'=>'Données de connexion invalides.'])->withInput();
        if($user[0]['statut'] === 0){
            return back()->withErrors(['message' => 'Le compte est désactiver.', "data" => $request->email])->withInput();
        }
        //essaye de connecter l'utilisateur avec les informations transmises
        if (Auth::guard()->attempt($request->only('email', 'password'))) {
                if(auth()->guard()->check()){
                    //suppression des tokens si utilisateur est connecté
                    $request->user()->tokens()->delete();
                }
                $request->session()->regenerate();
                //créer le token de connexion
            date_default_timezone_set('Europe/Paris');

            $token = Auth()->user()->createToken('auth_token',['*'],Carbon::now()->addMinutes(config('session.lifetime')))->plainTextToken;
                return redirect('/home')->with('token','Bearer '.$token);
        }
        return back()->withErrors(['message'=>'Données de connexion invalides.'])->withInput();
    }
    /**
     * Pour déconnecter un utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return  mixed
     *
     */
    public function logout(Request $request){
        //détruis les tokens et la session puis redirect l'utilisateur au login
        $request->user()->tokens()->delete();
        $request->session()->invalidate();
        Auth::guard()->logout();
        return redirect('/login');
    }
}
