<?php

namespace App\Http\Controllers;

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
        return view('admin.userCreate');
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'type' => 'required|max:100',
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
            'type' => $request->type
        ]);
        return back()->with('message','L\'utilisateur a été créer avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        return view('admin.userEdit',['user' => $user]);
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
            'password' => 'min:10|confirmed',
        ],
            [
                'required' => 'Le champ :attribute est requis.',
                'unique' => "Cette  addresse email a un compte éxistant.",
                'password.confirmed' => "Le mot de passe de confirmation ne correspond pas."
            ]);

        // Return errors if validation error occur.
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        $user = User::find($id);
        $cloneRequest = clone $request;
        unset($cloneRequest->password);
        if($request->password !== null){
            $cloneRequest->merge(['password' => Hash::make($request->password)]);
        }
        $user->update(array_filter($cloneRequest->all()));
        return back()->with('message','L\'utilisateur a été créer avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        if(Auth::user()->id === $id){
            Auth::user()->tokens()->delete();
            Auth::session()->invalidate();
            Auth::guard()->logout();
        }
    }
}
