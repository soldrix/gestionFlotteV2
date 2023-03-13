<?php

namespace App\Http\Controllers\Api;

use App\Models\agence;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;


class AgenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index():JsonResponse
    {

        $agence = agence::all();
        return response()->json([
            'data' => $agence
        ]);
    }

    public function searchAgence($search){
        $word =explode(" ",$search);
        $agences = agence::where(function ($query) use($word){
            for($i=0; $i < count($word);$i++){
                if ($i < 2){
                    $query->where('ville', 'like', "%". $word[$i]."%");
                }else{
                    $query->orWhere('ville', 'like', "%". $word[$i]."%");
                }
                $query->orWhere('rue', 'like', "%".$word[$i]."%");
                $query->orWhere('codePostal', 'like', "%".$word[$i]."%");
            }
        })->get();
        return response()->json(["research" => $agences, "status" => (count($agences) < 1) ? false : true ]);
    }
}
