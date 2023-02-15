<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\VoitureController;
use App\Http\Controllers\AgenceController;
use App\Http\Controllers\EntretienController;
use App\Http\Controllers\ReparationController;
use App\Http\Controllers\AssuranceController;
use App\Http\Controllers\ConsommationController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoitureFournisseurController;
use App\Http\Controllers\chefAgenceController;
use App\Http\Controllers\commandesController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/',  function (){
    //retourne l'utilisateur à la page home s'il est connecté
    if(\Illuminate\Support\Facades\Auth::user()){
        return redirect('/home');
    }
    return view('auth/login');
});
Route::get('/login', function (){
    //retourne l'utilisateur à la page home s'il est connecté
    if(\Illuminate\Support\Facades\Auth::user()){
        return redirect('/home');
    }
    return view('auth/login');
});
Route::get('/register',  function (){
    //retourne l'utilisateur à la page home s'il est connecté
    if(\Illuminate\Support\Facades\Auth::user()){
        return redirect('/home');
    }
    return view('auth/register');
});
Route::view('/PageNotFound', 'errors.404');
//page de modification de mot de passe



Route::view('/UpdatePassword', 'updatePassword');
Route::post('/userPassword/update', [UserController::class, 'updatePassword'])->name('UpdatePassword');

Route::get('/forget-password', [UserController::class, 'forgetPasswordLoad']);
Route::post('/forget-password', [App\Http\Controllers\Api\UserController::class, 'forgetPassword'])->name('forgotPassword');
Route::get('/reset-password', [App\Http\Controllers\Api\UserController::class, 'resetPasswordLoad']);
Route::post('/reset-password', [App\Http\Controllers\Api\UserController::class, 'resetPassword']);



Route::controller(LoginController::class)->group(function (){
    Route::post('login','login')->name('login');
    Route::post('register','register')->name('register');
});

//route qui nécessite une connexion
Route::middleware(\App\Http\Middleware\AuthWeb::class)->group(function() {
    Route::controller(LoginController::class)->group(function (){
        Route::post('logout','logout')->name('logout');
        Route::get('home','index')->name('home');
    });


    //route protéger par des roles
        Route::controller(VoitureController::class)
            ->middleware('role:admin,responsable auto')->group(function () {
            Route::get('voitures', 'index');
            Route::get('voiture/create', 'create');
            Route::post('voiture/store', 'store')->name('createVoiture');
            Route::get('voiture/{id}', 'adminShow')->name('voitureAdmin');
            Route::get('voiture/edit/{id}', 'edit');
            Route::put('voiture/update/{id}', 'update')->name('updateVoiture');
            Route::delete('voiture/delete/{id}', 'destroy');
        });

    Route::controller(VoitureFournisseurController::class)->middleware('role:admin,responsable fournisseur')->group(function () {
        Route::get('voitures-fournisseur', 'index');
        Route::get('voiture-fournisseur/create', 'create');
        Route::post('voiture-fournisseur/store', 'store')->name('createVoiture-fournisseur');
        Route::get('voiture-fournisseur/edit/{id}', 'edit');
        Route::put('voiture-fournisseur/update/{id}', 'update')->name('updateVoiture_fournisseur');
        Route::delete('voiture-fournisseur/delete/{id}', 'destroy');
    });

        Route::controller(AgenceController::class)->middleware('role:admin,chef agence')->group(function (){
            Route::get('agences', 'index');
            Route::get('agence/create', 'create');
            Route::post('agence/store', 'store')->name('createAgence');
            Route::get('agence/edit/{id}', 'edit');
            Route::put('agence/update/{id}', 'update')->name('updateAgence');
            Route::delete('agence/delete/{id}', 'destroy');
        });

        Route::controller(EntretienController::class)->middleware('role:admin,responsable auto')->group(function (){
            Route::get('entretiens', 'index');
            Route::get('entretien/create' , 'create');
            Route::post('entretien/store', 'store')->name('createEntretien');
            Route::get('entretien/edit/{id}', 'edit');
            Route::put('entretien/update/{id}', 'update')->name('updateEntretien');
            Route::delete('entretien/delete/{id}', 'destroy');
        });

        Route::controller(ReparationController::class)->middleware('role:admin,responsable auto')->group(function (){
            Route::get('reparations', 'index');
            Route::get('reparation/create' , 'create');
            Route::post('reparation/store', 'store')->name('createReparation');
            Route::get('reparation/edit/{id}', 'edit');
            Route::put('reparation/update/{id}', 'update')->name('updateReparation');
            Route::delete('reparation/delete/{id}', 'destroy');
        });

        Route::controller(AssuranceController::class)->middleware('role:admin,responsable auto')->group(function (){
            Route::get('assurances', 'index');
            Route::get('assurance/create' , 'create');
            Route::post('assurance/store', 'store')->name('createAssurance');
            Route::get('assurance/edit/{id}', 'edit');
            Route::put('assurance/update/{id}', 'update')->name('updateAssurance');
            Route::delete('assurance/delete/{id}', 'destroy');
        });

        Route::controller(ConsommationController::class)->middleware('role:admin,responsable auto')->group(function (){
            Route::get('consommations', 'index');
            Route::get('consommation/create' , 'create');
            Route::post('consommation/store', 'store')->name('createConsommation');
            Route::get('consommation/edit/{id}', 'edit');
            Route::put('consommation/update/{id}', 'update')->name('updateConsommation');
            Route::delete('consommation/delete/{id}', 'destroy');
        });

        Route::controller(LocationController::class)->middleware('role:admin')->group(function (){
            Route::get('locations', 'index');
            Route::get('location/create' , 'create');
            Route::post('location/store', 'store')->name('createLocation');
            Route::get('location/edit/{id}', 'edit');
            Route::put('location/update/{id}', 'update')->name('updateLocation');
            Route::delete('location/delete/{id}', 'destroy');
        });
        Route::controller(UserController::class)->middleware('role:admin')->group(function (){
           Route::get('user/edit/{id}', 'edit');
           Route::put('user/update/{id}', 'update')->name('userUpdate');
        });
        Route::controller(UserController::class)->middleware('role:admin,RH')->group(function (){
            Route::post('user/store', 'store')->name('userCreate');
            Route::get('user/create', 'create');
            Route::get('users', 'index');
            Route::put('user/desactivate/{id}', 'desactivate');
        });
        Route::controller(\App\Http\Controllers\Api\UserController::class)->group(function (){
            Route::post('user/edit/first_name', "update");
        });
        Route::controller(UserController::class)->group(function (){




           Route::get('profil/edit/{id}', 'editProfil');
           Route::put('profil/update/{id}', 'profilUpdate')->name('profilUpdate');
           Route::delete('user/delete/{id}', 'destroy');
           Route::get('/profil', 'show');
        });

//        Route::delete('user/delete/{id}', [\App\Http\Controllers\UserController::class,'destroy']);


    Route::controller(\App\Http\Controllers\FournisseurController::class)->middleware('role:admin,responsable fournisseur')->group(function (){
           Route::get('fournisseurs', 'index');
           Route::get('fournisseur/create', 'create');
           Route::get('fournisseur/edit/{id}', 'edit');
           Route::post('fournisseur/store', 'store')->name('createFournisseur');
           Route::put('fournisseur/update/{id}', 'update')->name('updateFournisseur');
           Route::delete('fournisseur/delete/{id}', 'destroy');
    });
    Route::controller(chefAgenceController::class)->middleware('role:chef agence,admin')->group(function (){
        Route::get('chef-agence', 'index');
        Route::get('chef-agence/voitures/{id}', 'indexVoiture');
        Route::get('chef-agence/voiture/edit/{id}', 'edit')->name('statutVoiture');
        Route::put('chef-agence/voiture/update/{id}', 'update')->name('updateStatut');
    });
    Route::controller(commandesController::class)->middleware('role:admin,RH')->group(function (){
       Route::get('commandes', 'index');
       Route::get('commande/create', 'create');
       Route::get('commande/edit/{id}', 'edit');
       Route::post('commande/store', 'store')->name('createCommande');
       Route::put('commande/update/{id}', 'update')->name('updateCommande');
       Route::delete("commande/delete/{id}", "destroy");
    });
});





