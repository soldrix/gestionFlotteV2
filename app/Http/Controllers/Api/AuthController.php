<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:10|confirmed',
            "password_confirmation" => 'required'
        ],
        [
            'required' => 'Le champ est requis.',
            'unique' => "Cette  addresse email a un compte éxistant.",
            'password.confirmed' => "Le mot de passe de confirmation ne correspond pas.",
            'email.email'  => "L'adresse mail doit être une adresse mail valide.",
            'min' => "Doit contenir au moins 10 caractères.",
            'max' => "Il y a trop de caractères."
        ]);

        // Return errors if validation error occur.
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        // Check if validation pass then create user and auth token. Return the auth token
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_role' => 1
        ]);
        $user->assignRole(1);

        $token = $user->createToken('auth_token',['*'],Carbon::now()->addMinutes(env('SESSION_LIFETIME',1)))->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            "id_user" => Auth("sanctum")->id()
        ]);

    }

    public function login(Request $request)
    {
        if (Auth()->attempt($request->only('email', 'password'))) {
            if(auth('sanctum')->check()){
                $request->user()->tokens()->delete();
            }
            $token = Auth('sanctum')->user()->createToken('auth_token',['*'],Carbon::now()->addMinutes(20) )->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                "id_user" => Auth("sanctum")->id(),
            ]);
        }
        return response()->json([
            'message' => 'Données de connexion invalides.'
        ], 401);

    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
    }
}
