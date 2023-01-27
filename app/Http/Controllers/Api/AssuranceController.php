<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\models\assurance;

class AssuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assurance = assurance::all();
        return response([
            "data" => $assurance
        ]);
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
            "DateDebut" => ["required","after_or_equal:2000-01-01"],
            "DateFin" => ["required",'after:'.$request->DateDebut],
            "frais" => ["required","numeric"]
        ],
        [
            "required" => "le champ est requis.",
            "DateDebut.after_or_equal" => "La date de debut doit être une date valide et doit être après 01/01/2000.",
            "DateFin.after" => "La date de Fin doit être plus grande que la date de debut.",
            "frais.numeric" => "Le montant des frais doivent être des chiffre ex: ( 10.50 ) ."
        ]);
        if($validator->fails()) return response()->json(["error" => $validator->errors()],400);
        $assurance = assurance::create([
            "nom" => $request->nom,
            "DateDebut" => $request->DateDebut,
            "DateFin" => $request->DateFin,
            "frais" => $request->frais,
            "id_voiture" => ($request->id_voiture === null) ? null : $request->id_voiture
        ]);
        return response()->json([
            "assurance" => $assurance
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
        $assurance = assurance::find($id);
        return response()->json([
            "assurance" => $assurance
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request):JsonResponse
    {
        $validator = Validator::make($request->all(),[
            "id" => "required",
            "DateDebut" => ["after_or_equal:2000-01-01"],
            "DateFin" => ['after:'.$request->DateDebut,($request->DateDebut) ? 'required' : ''],
            "frais" => ["numeric"]
        ],
        [
            "required" => "le champ est requis.",
            "DateDebut.after_or_equal" => "La date de debut doit être une date valide et doit être après 01/01/2000.",
            "DateFin.after" => "La date de Fin doit être plus grande que la date de debut.",
            "frais.numeric" => "Le montant des frais doivent être des chiffre ex: ( 10.50 ) ."
        ]);
        if($validator->fails()) return response()->json(["error" => $validator->errors()],400);
        $assurance = assurance::find($request->id);
        if($request->id_voiture){
            $request['id_voiture'] = ($request->id_voiture === null) ? null : $request->id_voiture;
        }
        $assurance->update($request->all());
        return response()->json([
            "assurance" => $request->all()
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
        $assurance = assurance::find($id);
        $assurance->delete();
        return response("L'assurance à été supprimé avec succès.");
    }
}
