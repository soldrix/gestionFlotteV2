<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function updateName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'required|min:10',
        ],
            [
                'required' => 'Le champ :attribute est requis.'
            ]);

        // Return errors if validation error occur.
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }
        if(Hash::check($request->password, Auth::user()->password)){
            $user = Auth::user();
            $user->name = $request->name;
            $user->save();
            return response()->json([
                "user" => $user
            ]);
        }
        return response()->json([
            "message" => "Mot de passe incorrect."
        ]);
    }
    public function updateEmail(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:10',
        ],
            [
                'required' => 'Le champ :attribute ne peut être vide.',
                'email' => "L'email doit être une addresse email valide."
            ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }
        if(Hash::check($request->password, Auth::user()->password)){
            $user = Auth::user();
            $user->email = $request->email;
            $user->save();
            return response()->json([
                "user" => $user
            ]);
        }
        return response()->json([
            "message" => "Mot de passe incorrect."
        ]);
    }
    public function updatePassword(Request $request){
        $validator = Validator::make($request->all(),[
            "old_password" => "required",
            "new_password" => "required|confirmed"
        ],
        [
            "old_password.required" => "Le mot de passe est requis.",
            "new_password.required" => "Le nouveau mot de passe est requis.",
            "confirmed" => "La confirmation du nouveau mot de passe ne correspond pas."
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }
        if(Hash::check($request->old_password, Auth::user()->password)){
            $user = Auth::user();
            $user->password = Hash::make($request->new_password);
            $user->save();
            return response()->json([
                "user" => $user
            ]);
        }
        return response()->json([
            "message" => "Mot de passe incorrect."
        ]);
    }
    public function delete($id)
    {
        User::where('id', $id)->delete();
       return response("L'utilisateur a été supprimer avec succès.");
    }
    public function getUser($id){
        return response()->json([
            "user" => User::where('id', $id)->get()
        ]);
    }

    public function index(){
        $user = User::all();
        return response($user);
    }
}
