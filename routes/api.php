<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AgenceController;
use App\Http\Controllers\Api\voitureController;
use App\Http\Controllers\Api\LocationController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/', function (){
    return view('welcome');
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
//recuperer une image
Route::get('/image/{path}', [voitureController::class, 'getImage'])->where('path', '.*');


Route::post('/forget-password', [UserController::class, 'forgetPassword']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);
Route::get('/reset-password', [UserController::class, 'resetPasswordLoad']);


Route::get('/delete-user',[App\Http\Controllers\Api\UserController::class, 'loadDeleteUser']);
Route::post('/delete-user',[App\Http\Controllers\Api\UserController::class, 'deleteAccount'])->name('deleteAccount');
Route::get('/reactivate-account',[App\Http\Controllers\Api\UserController::class, 'loadReactivateUser']);
Route::post('/reactivate-account',[App\Http\Controllers\Api\UserController::class, 'reactivateUser'])->name('reactivateAccount');

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(voitureController::class)->group(function (){
        Route::get('/voiture/{id}','show');
        Route::get('/voitures/agence/{id}','indexAgence');
        Route::get('/voitures/search/{search}', 'searchVoiture');
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::controller(UserController::class)->group(function (){
        Route::post('user/edit/first_name', "update_first_name");
        Route::post('user/edit/last_name', "update_last_name");
        Route::post('user/edit/email', "update_email");
        Route::post('user/edit/password', "update_password");
        Route::delete('/user/delete/{id}', 'delete');
        Route::get('/user/{id}','getUser');
    });

    Route::controller(AgenceController::class)->group(function (){
        Route::get('/agences/search/{search}', 'searchAgence');
        Route::get('/agences','index');
    });

    Route::controller(LocationController::class)->group(function (){
        Route::post('/location/create', 'store');
        Route::get('/locations',  "index");
        Route::get('/locations/user/{id}', 'indexUser');
        Route::get('/location/{id}', "show");
    });
    //route protéger par des roles

    //todo test
    Route::controller(\App\Http\Controllers\UserController::class)->group(function (){
        Route::post('user/store', 'store')->name('userCreate');
        Route::get('users', 'index');
        Route::post('user/desactivate', 'desactivate')->name('desactivateUser');
        Route::put('user/update/{id}', 'update')->name('userUpdate');
    });


    Route::controller(\App\Http\Controllers\VoitureController::class)
        ->group(function () {
            Route::get('voitures', 'index');
            Route::post('voiture/store', 'store')->name('createVoiture');
            Route::put('voiture/update/{id}', 'update')->name('updateVoiture');
            Route::delete('voiture/delete/{id}', 'destroy');
        });

    Route::controller(\App\Http\Controllers\VoitureFournisseurController::class)->group(function () {
        Route::get('voitures-fournisseur', 'index');
        Route::get('voitures-fournisseur/{id}', 'edit');
        Route::post('voiture-fournisseur/store', 'store')->name('createVoiture-fournisseur');
        Route::put('voiture-fournisseur/update/{id}', 'update')->name('updateVoiture_fournisseur');
        Route::delete('voiture-fournisseur/delete/{id}', 'destroy');
    });

    Route::controller(\App\Http\Controllers\AgenceController::class)->group(function (){
        Route::get('agences/admin', 'index');
        Route::get('/agence/{id}', 'edit');
        Route::post('agence/store', 'store')->name('createAgence');
        Route::put('agence/update/{id}', 'update')->name('updateAgence');
        Route::delete('agence/delete/{id}', 'destroy');
    });

    Route::controller(\App\Http\Controllers\EntretienController::class)->group(function (){
        Route::get('entretiens', 'index');
        Route::post('entretien/store', 'store')->name('createEntretien');
        Route::put('entretien/update/{id}', 'update')->name('updateEntretien');
        Route::delete('entretien/delete/{id}', 'destroy');
    });

    Route::controller(\App\Http\Controllers\ReparationController::class)->group(function (){
        Route::get('reparations', 'index');
        Route::post('reparation/store', 'store')->name('createReparation');
        Route::put('reparation/update/{id}', 'update')->name('updateReparation');
        Route::delete('reparation/delete/{id}', 'destroy');
    });

    Route::controller(\App\Http\Controllers\AssuranceController::class)->group(function (){
        Route::get('assurances', 'index');
        Route::post('assurance/store', 'store')->name('createAssurance');
        Route::put('assurance/update/{id}', 'update')->name('updateAssurance');
        Route::delete('assurance/delete/{id}', 'destroy');
    });

    Route::controller(\App\Http\Controllers\ConsommationController::class)->group(function (){
        Route::get('consommations', 'index');
        Route::post('consommation/store', 'store')->name('createConsommation');
        Route::put('consommation/update/{id}', 'update')->name('updateConsommation');
        Route::delete('consommation/delete/{id}', 'destroy');
    });

    Route::controller(\App\Http\Controllers\LocationController::class)->group(function (){
        Route::get('locations', 'index');
        Route::post('location/store', 'store')->name('createLocation');
        Route::put('location/update/{id}', 'update')->name('updateLocation');
        Route::delete('location/delete/{id}', 'destroy');
    });



    Route::controller(\App\Http\Controllers\FournisseurController::class)->group(function (){
        Route::get('fournisseurs', 'index');
        Route::post('fournisseur/store', 'store')->name('createFournisseur');
        Route::put('fournisseur/update/{id}', 'update')->name('updateFournisseur');
        Route::delete('fournisseur/delete/{id}', 'destroy');
    });


    Route::controller(\App\Http\Controllers\chefAgenceController::class)->group(function (){
        Route::get('chef-agence', 'index');
        Route::get('chef-agence/voitures/{id}', 'indexVoiture');
        Route::put('chef-agence/voiture/update/{id}', 'update')->name('updateStatut');
    });


    Route::controller(\App\Http\Controllers\commandesController::class)->group(function (){
        Route::get('commandes', 'index');
        Route::post('commande/store', 'store')->name('createCommande');
        Route::put('commande/update/{id}', 'update')->name('updateCommande');
        Route::delete("commande/delete/{id}", "destroy");
    });

});

