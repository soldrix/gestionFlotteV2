<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\maillerController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\location;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = location::all();
        return response([
            "data" => $locations
        ]);
    }/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexUser($id)
    {
        $locations = location::join("voitures", 'voitures.id', '=', 'locations.id_voiture')->where('locations.id_users',$id)
            ->get([
                'locations.*',
                'voitures.marque',
                'voitures.model'
            ]);
        return response([
            "locations" => $locations
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
            "DateDebut"  => ["required","after:2000-01-01"],
            "DateFin" => ["required", "after_or_equal:".$request->DateDebut]
        ]);
        if ($validator->fails()) return response()->json(["error" => $validator->errors()],400);
        $DateDebut = Carbon::createFromFormat('d/m/Y',$request->DateDebut)->format('Y-m-d');
        $DateFin = Carbon::createFromFormat('d/m/Y',$request->DateFin)->format('Y-m-d');
        $DParse = Carbon::parse($DateDebut);
        $FParse = Carbon::parse($DateFin);
        $montant = ($DParse->diffInDays($FParse) + 1) * $request->prix ;
        $location  = location::create([
            "DateDebut" => $DateDebut,
            "DateFin" => $DateFin,
            "montant" => $montant,
            "id_voiture" => ($request->id_voiture === null) ? null : $request->id_voiture,
            "id_users"  => Auth::id(),
        ]);

        $data["email"] = Auth::user()->email;
        $data['title'] = "Création de location";
        $data['DateDebut'] = $request->DateDebut;
        $data['DateFin'] = $request->DateFin;
        $data['montant'] = $montant;

        Mail::send('mail.locationMail', ['data' => $data],function ($message) use ($data){
            $message->to($data['email'])->subject($data['title']);
        });

        return response()->json([
            "location" => $location
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
        $location =  location::find($id);
        return response()->json([
            "location" => $location
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
            "DateDebut"  => ["after:2000-01-01"],
            "DateFin" => ["after_or_equal:".$request->DateDebut,($request->DateDebut) ? "required" : ""],
            "montant" => ["numeric"]
        ]);
        if ($validator->fails()) return response()->json(["error" => $validator->errors()],400);
        $location = location::find($request->id);
        if($request->id_voiture) $request['id_voiture'] = ($request->id_voiture === null) ? null : $request->id_voiture;
        $location->update(array_merge($request->all(),["id_users"  => Auth::id()]));
        return response()->json([
            "location" => $location
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
        $location = location::find($id);
        $location->delete();
        return response("La location a été supprimé avec succès.");
    }
}
