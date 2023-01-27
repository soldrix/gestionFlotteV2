<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\entretien;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class EntretienController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entretiens = entretien::all();
        return response($entretiens);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request):JsonResponse
    {
        $validator = Validator::make($request->all(),[
            "nom" => "required",
            "type" => "required",
            "date" => ["required","after:2000-01-01"],
            "montant" => ["required","numeric"]
        ],
        [
            "required" => "Le champ est requis.",
            "date.after" => "La date doit être valide et être après 01/01/2000.",
            "numeric" => "Le montant doit être un chiffre ex: ( 10.50)."
        ]);
        if ($validator->fails()) return response()->json(["error" => $validator->errors()],400);
        $entretien = entretien::create([
            "nom" => $request->nom,
            "type" => $request->type,
            "date" => $request->date,
            "montant" => $request->montant,
            "note" => ($request->note === null) ? 'Aucune note.' : $request->note,
            "id_voiture" => ($request->id_voiture === null) ? null : $request->id_voiture
        ]);
        return response()->json([
            "entretien" => $entretien
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id):JsonResponse
    {
        $entretien = entretien::find($id);
        return response()->json([
            "entretien" => $entretien
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request):JsonResponse
    {
        $validator = Validator::make($request->all(),[
            "id" => "required",
            "date" => ["after:2000-01-01"],
            "montant" => ["numeric"]
        ],
        [
            "required" => "Le champ est requis.",
            "date.after" => "La date doit être valide et être après 01/01/2000.",
            "numeric" => "Le montant doit être un chiffre ex: ( 10.50)."
        ]);
        if ($validator->fails()) return response()->json(["error" => $validator->errors()],400);
        $entretien = entretien::find($request->id);
        if ($request->note){
            $request['note'] = ($request->note === null) ? 'Aucune note.' : $request->note;
        }
        if ($request->id_voiture){
            $request['id_voiture'] =  ($request->id_voiture === null) ? null : $request->id_voiture;
        }
        $entretien->update($request->all());
        return response()->json([
            "entretien" => $request->all()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $entretien = entretien::find($id);
        $entretien->delete();
        return response("L'entretien a été supprimé avec succès.");
    }
}
