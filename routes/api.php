<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AgenceController;
use App\Http\Controllers\Api\VoitureController;
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
        Route::post('user/desactivate', 'desactivate');

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

});
