<?php

namespace App\Http\Controllers;

use App\Models\agence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $agences = agence::all();
        return view('agences',['agences' => $agences]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('form.agence.agenceCreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "ville" => "required",
            "rue" => "required",
            "codePostal" => ["required","integer","min_digits:5", "max_digits:5"]
        ]);
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        agence::create([
            "ville" => $request->ville,
            "rue" => $request->rue,
            "codePostal" => $request->codePostal
        ]);
        return back()->with('message', 'L\'agence à été créer avec succès.');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     */
    public function edit($id)
    {
        $agence = agence::find($id);
        return view('form.agence.agenceEdit',['agence' => $agence]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     */
    public function update(Request $request,$id)
    {
        $agence = agence::find($id);
        $validator = Validator::make(array_filter($request->all()),[
            "codePostal" => ["integer","min_digits:5", "max_digits:5"]
        ]);
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        $agence->update(array_filter($request->all()));
        return back()->with('message', 'L\'agence à été créer avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     */
    public function destroy($id):void
    {
        $agence = agence::find($id);
        $agence->delete();
    }
}
