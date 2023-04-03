<?php

namespace App\Http\Controllers\Api;

use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
    /**
     * Pour changer prénom d'un utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
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
        //vérifie le mot de passe de l'utilisateur
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
    /**
     * Pour changer le nom d'un utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
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
        if($validator->fails())return response()->json(["error" => $validator->errors()],400);
        $user = User::find($request->id);
        if(Hash::check($request->old_password, $user->password)){
            $user->last_name = $request->last_name;
            $user->update();
            return response()->json([
                "success" => "Le profil a été modifié avec succès.",
                "datas" => $request->all()
            ]);
        }
        return response()->json(["error" => ["old_password" => ["Mot de passe incorrect."]]],401);
    }
    /**
     * Pour changer l'email d'un utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
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
        if($validator->fails())return response()->json(["error" => $validator->errors()],400);
        $user = User::find($request->id);
        if(Hash::check($request->old_password, $user->password)){
            $user->email = $request->email;
            $user->update();
            return response()->json([
                "success" => "Le profil a été modifié avec succès.",
                "datas" => $request->all()
            ]);
        }
        return response()->json(["error" => ["old_password" => ["Mot de passe incorrect."]]],401);
    }

    /**
     * Pour changer le mot de passe d'un utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function update_password(Request $request):JsonResponse
    {
        $validator = Validator::make(array_filter($request->all()),[
            "new_password" => ["required", "string", "max:100", "confirmed", 'min:10'],
            "old_password" => ["required", "string", "max:100"]
        ],
        [
            "required" => "Le champ est requis.",
            "max" => 'Le champ ne peut contenir que 100 caractères.',
            "min" => 'Le champ doit contenir au moins 10 caractères.',
            "confirmed" => "Les mots de passe ne correspondent pas."
        ]);
        if($validator->fails())return response()->json(["error" => $validator->errors()],400);
        $user = User::find($request->id);
        if(Hash::check($request->old_password, $user->password)){
            $user->password = Hash::make($request->new_password);
            $user->update();
            return response()->json([
                "success" => "Le profil a été modifié avec succès."
            ]);
        }
        return response()->json(["error" => ["old_password" => ["Mot de passe incorrect."]]],401);
    }
    /**
     * Pour supprimer un utilisateur.
     *
     * @param  int  $id
     * @return mixed
     */
    public function delete($id)
    {
        $user = User::find($id);
        $user->tokens()->delete();
        $user->removeRole($user->id_role);
        $user->delete();
       return response()->json(["message" => "L'utilisateur a été supprimer avec succès."]);
    }
    public function desactivate(Request $request){
        $user = User::find($request->id);
        $user->statut = 0;
        $user->update();
        $request->user()->tokens()->delete();
        return response(["message" => "Votre compte a été désactiver avec succès."],200);
    }


    /**
     * Pour récupérer un utilisateur.
     *
     * @param  int  $id
     * @return mixed
     */
    public function getUser($id){
        return response()->json([
            "user" => User::where('id', $id)->get()
        ]);
    }

    /**
     * Pour envoyer un email à un utilisateur pour mot de passe oublier.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "email" => ["required", "email"]
        ],
        [
            "required" => "Champs requis.",
            "email" => "L'email doit être une adresse email valide."
        ]);
        if($validator->fails()) return ($request->wantsJson()) ? response()->json(['errors' => $validator->errors()],400) : back()->withErrors($validator->errors())->withInput();
       //récupère l'utilisateur avec l'email de la request
        $user = User::where('email', $request->email)->get();
        //vérifie que l'utilisateur existe
        if(count($user) > 0){
            //créer un token
            $token = Str::random(40);
            $domain = URL::to('/');
            $url = $domain.'/reset-password?token='.$token;
            //pour stocker les données du mail
            $data = new \stdClass();
            $data->url = $url;
            $data->email = $request->email;
            $data->title = "réinitialisation de mot de passe";
            $data->body = "Veuillez cliquer sur le lien ci-dessous pour réinitialiser votre mot de passe.";
            //pour envoyer le mail
            Mail::send('mail.forgetPasswordMail', ['data' => $data],function ($message) use ($data){
                $message->to($data->email)->subject($data->title);
            });
            //créer une date avec l'heure
            $datetime = Carbon::now()->format('Y-m-d H:i:s');
            //pour modifier ou créer la colonne de la table PasswordReset
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

    /**
     * Pour afficher la page de mot de passe oublier.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function resetPasswordLoad(Request $request)
    {
        //récupère la colonne de la table PasswordReset
        $resetData = PasswordReset::where('token',$request->token)->get();
        //vérifie que la colonne existe
        if (isset($request->token) && count($resetData) > 0){
            //récupère l'utilisateur
            $user = User::where('email', $resetData[0]['email'])->get();
            //retourne les données de l'utilisateur
            return ($request->wantsJson()) ? response()->json(['data' => $user]) : view('resetPassword',compact('user'));
        }
        return ($request->wantsJson()) ? response()->json(['success' => false],400) :view('errors.404');
    }

    /**
     * Pour changer le mot de passe de l'utilisateur après oublis de mot de passe.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id' => "required",
            'password' => ['required', "min:10", "string", "confirmed"]
        ],
        [
            "required" => "Champs requis",
            "min" => "Doit au moins contenir 10 caractères.",
            "confirmed" => "Les mots de passe ne corresponde pas."
        ]);
        if($validator->fails()) return ($request->wantsJson()) ? response()->json(['errors' => $validator->errors()]) : back()->withErrors($validator->errors())->withInput();

        $user = User::find($request->id);
        //modifie remplace son ancien mot de passe par le nouveau
        $user->password = Hash::make($request->password);
        $user->save();
        //supprime la colonne de PasswordReset
        PasswordReset::where('email', $user->email)->delete();

        return ($request->wantsJson()) ? response()->json(["message" => "Le mot de passe a été modifié avec succès."]) : redirect('/login')->with('message', 'Le mot de passe a été modifié avec succès.');
    }
    /**
     * Pour afficher la page de suppression de compte si l'utilisateur est désactivé.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function loadDeleteUser(Request $request)
    {
        if(!isset($request->email)) return ($request->wantsJson()) ? response()->json(['user' => false]) : view('errors.404');
        //récupère l'utilisateur désactiver
        $verifEmail = User::where([
            "email" => $request->email,
            "statut" => 0
        ])->get();
        //vérifie que l'utilisateur était bien désactivé, s'il n'est pas désactiver retourne false
        if(count($verifEmail) < 1) return ($request->wantsJson()) ? response()->json(['user' => false]) : view('errors.404');
        return ($request->wantsJson()) ? response()->json(['user' => true, 'data' => $verifEmail[0]]) : view('auth.deleteUser',['user' => $verifEmail[0]]);
    }
    /**
     * Pour supprimer un compte si l'utilisateur est désactivé.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function deleteAccount(Request $request){
        $validator = Validator::make($request->all(),[
           "email" => "required",
           "password" => "required",
            "id" => "required"
        ],
        [
            "required" => "Champs requis."
        ]);
        if($validator->fails()) return ($request->wantsJson()) ? response()->json(['errors' => $validator->errors()]) : back()->withErrors($validator->errors())->withInput();
        //vérifie les données de connexion de l'utilisateur de la request
        if(Auth()->attempt($request->only('email', 'password'))){
            $user = User::find($request->id);
            //supprime l'utilisateur
            $user->delete();
            if(!$request->wantsJson()){
                $request->session()->invalidate();
            }
            return ($request->wantsJson()) ? response()->json(["message" => "L'utilisateur a été supprimer avec succès."]) : redirect("/login")->with('message', "L'utilisateur a été supprimer avec succès.");
        }
        //retourne message erreur
        return ($request->wantsJson()) ? response()->json([
            'message' => 'Données de connexion invalides.',
            "data" => $request->all(),
            'test' => Auth()->attempt($request->only('email', 'password'))
        ], 401) : back()->withErrors(['message' => 'Données de connexion invalides.'])->withInput();
    }
    /**
     * Pour afficher la page de réactivation de compte si l'utilisateur est désactivé.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function loadReactivateUser(Request $request){
        if(!isset($request->email)) return ($request->wantsJson()) ? response()->json(['user' => false]) : view('errors.404');
        $verifEmail = User::where([
            "email" => $request->email,
            "statut" => 0
        ])->get();
        if(count($verifEmail) < 1) return ($request->wantsJson()) ? response()->json(['user' => false],400) : view('errors.404');
        return ($request->wantsJson()) ? response()->json(['user' => true, 'data' => $verifEmail[0]]) : view('auth.reactivateUser',['user' => $verifEmail[0]]);
    }
    /**
     * Pour réactiver un compte si l'utilisateur est désactivé.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function reactivateUser(Request $request){
        $validator = Validator::make($request->all(),[
            "email" => "required",
            "password" => "required",
            "id" => "required"
        ],
        [
            "required" => "Champs requis."
        ]);
        if($validator->fails()) return ($request->wantsJson()) ? response()->json(['errors' => $validator->errors()]) : back()->withErrors($validator->errors())->withInput();
        if(Auth()->attempt($request->only('email', 'password'))){
            $user = User::find($request->id);
            //modifie le statut de l'utilisateur pour réactiver le compte
            $user->update([
                "statut" => 1
            ]);
            if(!$request->wantsJson()){
                $request->session()->invalidate();
            }
            return ($request->wantsJson()) ? response()->json(["message" => "L'utilisateur a été réactiver avec succès."]) : redirect("/login")->with('message', "L'utilisateur a été réactiver avec succès.");
        }
        return ($request->wantsJson()) ? response()->json([
            'message' => 'Données de connexion invalides.'
        ], 401) : back()->withErrors(['message' => 'Données de connexion invalides.'])->withInput();
    }
}
