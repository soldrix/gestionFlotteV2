<?php

namespace App\Http\Controllers;

use App\Models\fournisseur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        $fournisseurs = fournisseur::join('users', 'users.id', '=', 'fournisseurs.id_users')
            ->get([
                'fournisseurs.*',
                'users.email'
            ]);
        return view('fournisseurs', ['fournisseurs' => $fournisseurs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return mixed
     */
    public function create()
    {
        $users = User::where('type', '=', 'fournisseur')->get();
        return view('form.fournisseur.fournisseurCreate', ['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'id_users' => 'required'
        ]);
        if($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        fournisseur::create([
            'name' => $request->name,
            'id_users' => $request->id_users
        ]);
        return back()->with('message', 'Le fournisseur a été créer avec succès.');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return mixed
     */
    public function edit($id)
    {
        $fournisseur = fournisseur::find($id);
        $users = User::where('type', '=', 'fournisseur')->get();
        return view('form.fournisseur.fournisseurEdit',['fournisseur' => $fournisseur, 'users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(array_filter($request->all()),[
            'name' => 'string|max:255',
        ]);
        if($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        $fournisseur = fournisseur::find($id);
        $fournisseur->update(array_filter($request->all()));
        return back()->with('message', 'Le fournisseur a été modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return void
     */
    public function destroy($id):void
    {
        $fournisseur = fournisseur::find($id);
        $fournisseur->delete();
    }
}
