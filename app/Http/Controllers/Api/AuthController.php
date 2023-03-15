<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        // Valide les valeurs de la request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:10|confirmed',
            "password_confirmation" => 'required'
        ],
        [
            'required' => 'Le champ est requis.',
            'unique' => "Cette addresse email a un compte éxistant.",
            'password.confirmed' => "Le mot de passe de confirmation ne correspond pas.",
            'email.email'  => "L'adresse mail doit être une adresse mail valide.",
            'min' => "Doit contenir au moins 10 caractères.",
            'max' => "Il y a trop de caractères."
        ]);

        // Retourne les erreurs.
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        // Check if validation pass then create user and auth token. Return the auth token
        //stock les valeurs de l'utilisateur
        $collections = collect($request->all())->replaceRecursive(['password' => Hash::make($request->password)])->mergeRecursive(['id_role' => 1]);
        $user = User::create($collections->all());
        $user->assignRole(1);
        //connect l'utilisateur
        Auth()->attempt($request->only('email', 'password'));
        //créer un token avec expiration
        $token = Auth('sanctum')->user()->createToken('auth_token',['*'],Carbon::now()->addMinutes(20) )->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            "id_user" => Auth("sanctum")->id()
        ]);

    }

    public function login(Request $request)
    {
        //test la connexion de l'utilisateur
        if (Auth()->attempt($request->only('email', 'password'))) {
            //récupère l'utilisateur avec son email
            $user = User::where('email', '=', $request->email)->get();
            //vérifie si l'utilisateur est désactivé
            if($user[0]['statut'] === 0){
                //retourne message erreur
                return response()->json(['message' => 'Le compte est désactiver.', "data" => $request->email],400);
            }

            if(auth('sanctum')->check()){
                //supprime les tokens
                $request->user()->tokens()->delete();
            }
            //créer un token avec expiration
            $token = Auth('sanctum')->user()->createToken('auth_token',['*'],Carbon::now()->addMinutes(20) )->plainTextToken;
            //retourne le token et id de l'utilisateur
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                "id_user" => Auth("sanctum")->id(),
            ]);
        }
        //retourne message erreur
        return response()->json([
            'message' => 'Données de connexion invalides.'
        ], 401);

    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
    }
}
