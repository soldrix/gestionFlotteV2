<?php

namespace App\Http\Controllers;

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
        $users = User::all();
        return view('admin.users', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $roles = roles::all();
        return view('admin.userCreate',['roles' => $roles]);
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
            'name' => 'required|string|max:255',
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
            'name' => $request->name,
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
        return view('admin.userEdit',['user' => $user, 'roles' => $roles]);
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
            'name' => 'string|max:255',
            'email' => 'email|unique:users|max:255',
            'type' => 'max:100',
            'password' => 'min:10',
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
            $j = new \stdClass();
            $j->email = $user->email;
            $j->name = $user->name;
            $j->password = $request->password;
            $mailler = new maillerController();
            $mailler->UserPassword($j);
        }


        $user->update(array_filter($cloneRequest->all()));

        return back()->with('message','L\'utilisateur a été créer avec succès.');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
            'new_password' => 'required|min:10|confirmed'
        ]);
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        if(Auth::guard()->check()){
            $user = User::find(Auth::user()->id);
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
            $request->user()->tokens()->delete();
            $request->session()->invalidate();
            Auth::guard()->logout();
            return back()->with('message','Le mot de passe a été modifier avec succès.');
        }
        if(Auth::guard()->attempt($request->only('email', 'password'))){
            $user = User::find(Auth::user()->id);
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
            Auth::guard()->logout();
            return back()->with('message','Le mot de passe a été modifier avec succès.');
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
        if(intval(Auth::user()->id) == intval($id) || Auth::user()->hasRole('admin')){
            $user = User::find($id);
            $user->delete();
            if(Auth::user()->id === $id){
                Auth::user()->tokens()->delete();
                Auth::session()->invalidate();
                Auth::guard()->logout();
            }
            return response('ok',200);
        }
        return response([
            'error' => 'request unauthorized'.Auth::user()->id
        ],401);
    }
}
