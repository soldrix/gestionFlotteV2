<?php

namespace App\Http\Controllers\Api;

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
            'name' => 'required|string|max:255',
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
        if ($validator->passes()) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type' => 'normal'
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }
    }

    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            if(auth('sanctum')->check()){
                $request->user()->tokens()->delete();
            }
            $token = Auth('sanctum')->user()->createToken('auth_token')->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',]);
        }
        return response()->json([
            'message' => 'Données de connexion invalides.'
        ], 401);

    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
    }
}
