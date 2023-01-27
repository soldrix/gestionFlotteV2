<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    public function index(){
        return view('home');
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
            'type' => 'normal'
        ]);
        Auth::guard()->attempt($request->only('email', 'password'));
        $request->session()->regenerate();
        $token = Auth()->user()->createToken('auth_token')->plainTextToken;
        if(Auth::user()->type === 'admin'){
            return redirect('/admin/voitures');
        }
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
            if(Auth::user()->type == 'admin'){
                return redirect('/admin/voitures');
            }
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
