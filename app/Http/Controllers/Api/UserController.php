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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class UserController extends Controller
{

    public function update_first_name(Request $request)
    {
        $validator = Validator::make(array_filter($request->all()),[
            "first_name" => ["required", "string", "max:255"],
            "old_password" => ["required", "string", "max:100"]
        ],
        [
            "required" => "Le champ est requis.",
            "first_name.max" => 'Le champ ne peut contenir que 255 caractères.',
            "old_password.max" => 'Le champ ne peut contenir que 100 caractères.'
        ]);
        if($validator->fails())return response()->json(["error" => $validator->errors()],400);
        $user = User::find($request->id);
        if(Hash::check($request->old_password, $user->password)){
            $user->first_name = $request->first_name;
            $user->update();
            return response()->json([
                "success" => "Le profil a été modifié avec succès.",
                "datas" => $request->all()
            ]);
        }
        return response()->json(["error" => ["old_password" => ["Mot de passe incorrect."]]],401);
    }

    public function update_last_name(Request $request)
    {
        $validator = Validator::make(array_filter($request->all()),[
            "last_name" => ["required", "string", "max:255"],
            "old_password" => ["required", "string", "max:100"]
        ],
        [
            "required" => "Le champ est requis.",
            "last_name.max" => 'Le champ ne peut contenir que 255 caractères.',
            "old_password.max" => 'Le champ ne peut contenir que 100 caractères.'
        ]);
        if($validator->fails())return response()->json(["error" => $validator->errors()]);
        $user = User::find($request->id);
        if(Hash::check($request->old_password, $user->password)){
            $user->last_name = $request->last_name;
            $user->update();
            return response()->json([
                "success" => "Le profil a été modifié avec succès.",
                "datas" => $request->all()
            ]);
        }
        return response()->json(["error" => ["old_password" => "Mot de passe incorrect."]]);
    }
    public function update_email(Request $request)
    {
        $validator = Validator::make(array_filter($request->all()),[
            "email" => ["required", "email", "unique:users", "max:255"],
            "old_password" => ["required", "string", "max:100"]
        ],
        [
            "required" => "Le champ est requis.",
            "email.max" => 'Le champ ne peut contenir que 255 caractères.',
            "old_password.max" => 'Le champ ne peut contenir que 100 caractères.',
            "unique" => "L'adresse mail est déjà utiliser."
        ]);
        if($validator->fails())return response()->json(["error" => $validator->errors()]);
        $user = User::find($request->id);
        if(Hash::check($request->old_password, $user->password)){
            $user->email = $request->email;
            $user->update();
            return response()->json([
                "success" => "Le profil a été modifié avec succès.",
                "datas" => $request->all()
            ]);
        }
        return response()->json(["error" => ["old_password" => "Mot de passe incorrect."]]);
    }

    public function update_password(Request $request):JsonResponse
    {
        $validator = Validator::make(array_filter($request->all()),[
            "new_password" => ["required", "string", "max:100", "confirmed"],
            "old_password" => ["required", "string", "max:100"]
        ],
        [
            "required" => "Le champ est requis.",
            "max" => 'Le champ ne peut contenir que 100 caractères.',
            "confirmed" => "Les mots de passe ne correspondent pas."
        ]);
        if($validator->fails())return response()->json(["error" => $validator->errors()]);
        $user = User::find($request->id);
        if(Hash::check($request->old_password, $user->password)){
            $user->password = Hash::make($request->new_password);
            $user->update();
            return response()->json([
                "success" => "Le profil a été modifié avec succès.",
                "datas" => $request->all()
            ]);
        }
        return response()->json(["error" => ["old_password" => "Mot de passe incorrect."]]);
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

//            $data['url'] = $url;
//            $data["email"] = $request->email;
//            $data['title'] = "réinitialisation de mot de passe";
//            $data['body'] = "Veuillez cliquer sur le lien ci-dessous pour réinitialiser votre mot de passe.";

            $data = new \stdClass();
            $data->url = $url;
            $data->email = $request->email;
            $data->title = "réinitialisation de mot de passe";
            $data->body = "Veuillez cliquer sur le lien ci-dessous pour réinitialiser votre mot de passe.";

            Mail::send('mail.forgetPasswordMail', ['data' => $data],function ($message) use ($data){
                $message->to($data->email)->subject($data->title);
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
            return ($request->wantsJson()) ? response()->json(['data' => $user]) : view('resetPassword',compact('user'));
        }
        return ($request->wantsJson()) ? response()->json(['success' => false]) :view('errors.404');
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
