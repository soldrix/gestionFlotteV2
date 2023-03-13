<?php

namespace App\Http\Controllers;

use App\Models\fournisseur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        $fournisseurs = fournisseur::all();
        return view('fournisseurs', ['fournisseurs' => $fournisseurs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return mixed
     */
    public function create()
    {
        return view('form.fournisseur.fournisseurCreate');
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
            "email" => 'required|email'
        ]);
        if($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        fournisseur::create([
            'name' => $request->name,
            'email' => $request->email
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
        return view('form.fournisseur.fournisseurEdit');
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
            'email' => 'email'
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
