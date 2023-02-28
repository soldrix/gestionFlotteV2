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
            "DateFin" => ["required", "after:".$request->DateDebut]
        ]);
        if ($validator->fails()) return response()->json(["error" => $validator->errors()],400);
        $DateDebut = $request->DateDebut;
        $DateFin = $request->DateFin;
        $DParse = Carbon::parse($DateDebut);
        $FParse = Carbon::parse($DateFin);
        $montant = ($DParse->diffInDays($FParse) + 1) * $request->prix ;
        $location  = location::create([
            "DateDebut" => $DateDebut,
            "DateFin" => $DateFin,
            "montant" => $montant,
            "id_voiture" => ($request->id_voiture === null) ? null : $request->id_voiture,
            "id_users"  => Auth::id(),
            "commandeNumber" => Str::random(15)
        ]);

        $DateDebut = Carbon::createFromFormat('Y-m-d',$request->DateDebut)->format('d/m/Y');
        $DateFin = Carbon::createFromFormat('Y-m-d',$request->DateFin)->format('d/m/Y');
        $data["email"] = Auth::user()->email;
        $data['title'] = "Création de location";
        $data['DateDebut'] = $DateDebut;
        $data['DateFin'] = $DateFin;
        $data['montant'] = $montant;

        Mail::send('mail.locationMail', ['data' => $data],function ($message) use ($data){
            $message->to($data['email'])->subject($data['title']);
        });

        return response()->json([
            "location" => $location
        ]);
    }
}
