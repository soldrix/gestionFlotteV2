<?php

namespace App\Http\Controllers\Api;

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
     * Pour afficher toutes les locations
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
     * Pour afficher les locations d'un utilisateur.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexUser($id)
    {
        $locations = location::join("voitures", 'voitures.id', '=', 'locations.id_voiture')->where('locations.id_user',$id)
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
        ],
        [
            "required" => "Champs requis.",
            "DateDebut.after" => "La date de debut doit être après 01/01/2000.",
            "DateFin.after" => "La date de fin doit être après la date de debut."
        ]);
        if ($validator->fails()) return response()->json(["error" => $validator->errors()],400);
        $DateDebut = $request->DateDebut;
        $DateFin = $request->DateFin;
        $DParse = Carbon::parse($DateDebut);
        $FParse = Carbon::parse($DateFin);
        //calcule le montant de la location
        $montant = ($DParse->diffInDays($FParse) + 1) * $request->prix ;
        //enregistre la locations
        $location  = location::create([
            "DateDebut" => $DateDebut,
            "DateFin" => $DateFin,
            "montant" => $montant,
            "id_voiture" => ($request->id_voiture === null) ? null : $request->id_voiture,
            "id_user"  => Auth::id(),
            "commandeNumber" => Str::random(15)
        ]);
        //change le format des dates
        $DateDebut = Carbon::createFromFormat('Y-m-d',$request->DateDebut)->format('d/m/Y');
        $DateFin = Carbon::createFromFormat('Y-m-d',$request->DateFin)->format('d/m/Y');
        //stock les donner pour l'email automatique
        $data["email"] = Auth::user()->email;
        $data['title'] = "Création de location";
        $data['DateDebut'] = $DateDebut;
        $data['DateFin'] = $DateFin;
        $data['montant'] = $montant;
        //envoi les données a une view pour créer un email customisable et l'envoyer
        Mail::send('mail.locationMail', ['data' => $data],function ($message) use ($data){
            //configure l'envoi du mail avec son receveur et son sujet
            $message->to($data['email'])->subject($data['title']);
        });

        return response()->json([
            "location" => $location
        ]);
    }
}
