<?php

namespace App\Http\Controllers;

use App\Models\agence;
use App\Models\User;
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
        $agences = agence::leftjoin('users', 'users.id', '=', 'agence.id_user')
            ->get([
                'agence.*',
                'users.first_name',
                'users.last_name',
                'users.email'
            ]);
        return view('agences',['agences' => $agences]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $users = User::where('type', '=', 'chef agence')->get();
        return view('form.agence.agenceCreate',['users'=> $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function store(Request $request)
    {
        //validator verif les valeurs
        $validator = Validator::make($request->all(),[
            "ville" => "required",
            "rue" => "required",
            "codePostal" => ["required","integer","min_digits:5", "max_digits:5"]
        ]);
        //retourn les erreurs du validator
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        agence::create([
            "ville" => $request->ville,
            "rue" => $request->rue,
            "codePostal" => $request->codePostal,
            'id_user' => ($request->id_user === 'vide') ? null : $request->id_user
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
        $users = User::where('type', '=', 'chef agence')->get();
        return view('form.agence.agenceEdit',['agence' => $agence,'users' => $users]);
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
        //array filter pour suprimer valeur null
        $validator = Validator::make(array_filter($request->all()),[
            "codePostal" => ["integer","min_digits:5", "max_digits:5"]
        ]);
        if ($validator->fails()) return back()->withErrors($validator->errors())->withInput();
        if($request->ville !== null){
            $agence->ville = $request->ville;
        }
        if($request->rue !== null){
            $agence->rue = $request->rue;
        }
        if($request->codePostal !== null){
            $agence->codePostal = $request->codePostal;
        }
        if($request->id_user !== null){
            $agence->id_user = ($request->id_user === 'vide') ? null : $request->id_user;
        }
        $agence->update();
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
