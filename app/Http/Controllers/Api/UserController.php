<?php

namespace App\Http\Controllers\Api;

use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class UserController extends Controller
{
    public function update(Request $request):JsonResponse
    {
        $validator = Validator::make(array_filter($request->all()),[
            "first_name" => ["string", "max:255"],
            "last_name" => ["string", "max:255"],
            "email" => ["email", "unique:users","max:255"],
            "new_password" => ["string", "max:100", "confirmed"],
            "old_password" => ["required", "string", "max:100"]
        ]);
        if($validator->fails())return response()->json(["error" => $validator->errors()]);
        $user = User::find($request->id);
        if(Hash::check($request->old_password, $user->password)){
            if($request->first_name !== null){
                $user->first_name = $request->first_name;
            }
            if($request->last_name !== null){
                $user->last_name = $request->last_name;
            }
            if($request->email !== null){
                $user->email = $request->eamil;
            }
            if($request->new_password !== null){
                $user->password = $request->new_password;
            }
            $user->update();
        }
        return response()->json([
            "success" => "Le profil a été modifié avec succès.",
            "datas" => $request->all()
        ]);
    }



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

    //forget password

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "email" => ["required", "email"]
        ]);
        if($validator->fails()) return ($request->wantsJson()) ? response()->json(['errors' => $validator->errors()],400) : back()->withErrors($validator->errors())->withInput();
        $user = User::where('email', $request->email)->get();
        if(count($user) > 0){
            $token = Str::random(40);
            $domain = URL::to('/');
            $url = $domain.'/reset-password?token='.$token;

            $data['url'] = $url;
            $data["email"] = $request->email;
            $data['title'] = "réinitialisation de mot de passe";
            $data['body'] = "Veuillez cliquer sur le lien ci-dessous pour réinitialiser votre mot de passe.";

            Mail::send('forgetPasswordMail', ['data' => $data],function ($message) use ($data){
                $message->to($data['email'])->subject($data['title']);
            });

            $datetime = Carbon::now()->format('Y-m-d H:i:s');

            PasswordReset::updateOrCreate(
                ["email" => $request->email],
                [
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => $datetime
                ]
            );
            return ($request->wantsJson()) ? response()->json(['success' => true, 'msg' =>  'Veuillez vérifier votre e-mail pour réinitialiser votre mot de passe.']) : back()->with('message', 'Veuillez vérifier votre e-mail pour réinitialiser votre mot de passe.');
        }
        return ($request->wantsJson()) ? response()->json(['success' => false, 'msg' =>  'Utilisateur non trouvé.'],400) : back()->withErrors(['email' => ['Utilisateur non trouvé.']])->withInput();
    }


    public function resetPasswordLoad(Request $request)
    {
        $resetData = PasswordReset::where('token',$request->token)->get();
        if (isset($request->token) && count($resetData) > 0){
            $user = User::where('email', $resetData[0]['email'])->get();
            return view('resetPassword',compact('user'));
        }
        return view('errors.404');
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'password' => ['required', "min:10", "string", "confirmed"]
        ]);
        if($validator->fails()) return ($request->wantsJson()) ? response()->json(['errors' => $validator->errors()]) : back()->withErrors($validator->errors())->withInput();

        $user = User::find($request->id);
        $user->password = Hash::make($request->password);
        $user->save();
        PasswordReset::where('email', $user->email)->delete();

        return ($request->wantsJson()) ? response()->json(["message" => "Le mot de passe a été modifié avec succès."]) : redirect('/login')->with('message', 'Le mot de passe a été modifié avec succès.');
    }
}
