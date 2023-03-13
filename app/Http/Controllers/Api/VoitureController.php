<?php

namespace App\Http\Controllers\Api;

use App\Models\voiture;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class VoitureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $voiture = voiture::all();
        return response($voiture);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function indexAgence($id):JsonResponse
    {
        $voiture = voiture::where([
            'id_agence' => $id,
            'statut' => 1
        ])->get();
        if(count($voiture) < 0) return response()->json(['error' => 'Aucune voiture disponible']);
        return response()->json([
            'voitures' => $voiture
        ]);
    }

    public function searchVoiture($search){
        $word =explode(" ",$search);
        $voitures = voiture::leftjoin("locations",'locations.id_voiture', '=', 'voitures.id')
        ->where(function ($query) use($word){
            for($i=0; $i < count($word);++$i){
                if ($i <= 1){
                    $query->where('voitures.marque', 'like', "%".$word[$i]."%");
                }else{
                    $query->orWhere('voitures.marque', 'like', "%".$word[$i]."%");
                }
                $query->orWhere('voitures.model', 'like', "%".$word[$i]."%");
                $query->orWhere('voitures.type', 'like', "%".$word[$i]."%");
                $query->orWhere('voitures.nbPorte', 'like', "%".$word[$i]."%");
                $query->orWhere('voitures.prix', 'like', "%".$word[$i]."%");
                $query->orWhere('locations.commandeNumber', 'like', "%".$word[$i]."%");
            }
        })->get([
            "voitures.*"
        ]);
        return response()->json(["research" => $voitures, "status" => (count($voitures) < 1) ? false : true ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id):JsonResponse
    {
        $voiture = voiture::find($id);
        return response()->json([
            "voiture" => $voiture
        ]);
    }

    public function getImage($path)
    {
        $image = Storage::get($path);
        return response($image, 200)->header('Content-Type', Storage::mimeType($path));
    }
}
