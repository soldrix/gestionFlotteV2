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
    if(\Illuminate\Support\Facades\Auth::user()){
        return view('home');
    }
    return view('auth/login');
});
Route::get('/login', function (){
    if(\Illuminate\Support\Facades\Auth::user()){
        return view('home');
    }
    return view('auth/login');
});
Route::get('/register',  function (){
    if(\Illuminate\Support\Facades\Auth::user()){
        return view('home');
    }
    return view('auth/register');
});
Route::view('/PageNotFound', 'errors.404');
Route::view('/UpdatePassword', 'updatePassword');

Route::post('/userPassword/update', [UserController::class, 'updatePassword'])->name('UpdatePassword');
Route::controller(LoginController::class)->group(function (){

    Route::post('login','login')->name('login');
    Route::post('register','register')->name('register');
    Route::post('logout','logout')->name('logout');
    Route::get('home','index')->name('home');

});

Route::middleware(\App\Http\Middleware\AuthWeb::class)->group(function() {
    Route::view('/profil', 'profil');
        Route::controller(VoitureController::class)->middleware('role:admin,fournisseur,responsable auto')->group(function () {
            Route::get('admin/voitures', 'adminIndex');
            Route::get('admin/voiture/create', 'create');
            Route::post('admin/voiture/store', 'store')->name('createVoiture');
            Route::get('admin/voiture/{id}', 'adminShow')->name('voitureAdmin');
            Route::get('voiture/{id}', 'show')->name('voiture');
            Route::get('admin/voiture/edit/{id}', 'edit');
            Route::put('admin/voiture/update/{id}', 'update')->name('updateVoiture');
            Route::delete('admin/voiture/delete/{id}', 'destroy');
        });

        Route::controller(AgenceController::class)->middleware('role:admin')->group(function (){
            Route::get('admin/agences', 'index');
            Route::get('admin/agence/create', 'create');
            Route::post('admin/agence/store', 'store')->name('createAgence');
            Route::get('admin/agence/edit/{id}', 'edit');
            Route::put('admin/agence/update/{id}', 'update')->name('updateAgence');
            Route::delete('admin/agence/delete/{id}', 'destroy');
        });

        Route::controller(EntretienController::class)->middleware('role:admin,responsable auto')->group(function (){
            Route::get('admin/entretiens', 'index');
            Route::get('admin/entretien/create' , 'create');
            Route::post('admin/entretien/store', 'store')->name('createEntretien');
            Route::get('admin/entretien/edit/{id}', 'edit');
            Route::put('admin/entretien/update/{id}', 'update')->name('updateEntretien');
            Route::delete('admin/entretien/delete/{id}', 'destroy');
        });

        Route::controller(ReparationController::class)->middleware('role:admin,responsable auto')->group(function (){
            Route::get('admin/reparations', 'index');
            Route::get('admin/reparation/create' , 'create');
            Route::post('admin/reparation/store', 'store')->name('createReparation');
            Route::get('admin/reparation/edit/{id}', 'edit');
            Route::put('admin/reparation/update/{id}', 'update')->name('updateReparation');
            Route::delete('admin/reparation/delete/{id}', 'destroy');
        });

        Route::controller(AssuranceController::class)->middleware('role:admin,responsable auto')->group(function (){
            Route::get('admin/assurances', 'index');
            Route::get('admin/assurance/create' , 'create');
            Route::post('admin/assurance/store', 'store')->name('createAssurance');
            Route::get('admin/assurance/edit/{id}', 'edit');
            Route::put('admin/assurance/update/{id}', 'update')->name('updateAssurance');
            Route::delete('admin/assurance/delete/{id}', 'destroy');
        });

        Route::controller(ConsommationController::class)->middleware('role:admin,responsable auto')->group(function (){
            Route::get('admin/consommations', 'index');
            Route::get('admin/consommation/create' , 'create');
            Route::post('admin/consommation/store', 'store')->name('createConsommation');
            Route::get('admin/consommation/edit/{id}', 'edit');
            Route::put('admin/consommation/update/{id}', 'update')->name('updateConsommation');
            Route::delete('admin/consommation/delete/{id}', 'destroy');
        });

        Route::controller(LocationController::class)->middleware('role:admin')->group(function (){
            Route::get('admin/locations', 'adminIndex');
            Route::get('admin/location/create' , 'create');
            Route::post('admin/location/store', 'store')->name('createLocation');
            Route::get('admin/location/edit/{id}', 'edit');
            Route::put('admin/location/update/{id}', 'update')->name('updateLocation');
            Route::delete('admin/location/delete/{id}', 'destroy');
        });
        Route::controller(UserController::class)->middleware('role:admin')->group(function (){
           Route::get('admin/users', 'index');
           Route::get('admin/user/create', 'create');
           Route::get('admin/user/edit/{id}', 'edit');
           Route::post('admin/user/store', 'store')->name('userCreate');
           Route::put('admin/user/update/{id}', 'update')->name('userUpdate');
        });
        Route::delete('admin/user/delete/{id}', [\App\Http\Controllers\UserController::class,'destroy']);


    Route::controller(\App\Http\Controllers\FournisseurController::class)->middleware('role:admin')->group(function (){
           Route::get('admin/fournisseurs', 'index');
           Route::get('admin/fournisseur/create', 'create');
           Route::get('admin/fournisseur/edit/{id}', 'edit');
           Route::post('admin/fournisseur/store', 'store')->name('createFournisseur');
           Route::put('admin/fournisseur/update/{id}', 'update')->name('updateFournisseur');
           Route::delete('admin/fournisseur/delete/{id}', 'destroy');
        });
});





