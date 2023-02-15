<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AgenceController;
use App\Http\Controllers\Api\voitureController;
use App\Http\Controllers\Api\AssuranceController;
use App\Http\Controllers\Api\ConsommationController;
use App\Http\Controllers\Api\EntretienController;
use App\Http\Controllers\Api\ReparationController;
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
Route::get('/image/{path}', [voitureController::class, 'getImage'])->where('path', '.*');


Route::post('/forget-password', [UserController::class, 'forgetPassword']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/voitures/agence/{id}',[voitureController::class, 'indexAgence']);


    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user/edit/name', [UserController::class, 'updateName']);
    Route::post('/user/edit/email', [UserController::class, 'updateEmail']);
    Route::post('/user/edit/password', [UserController::class, 'updatePassword']);
    Route::delete('/user/delete/{id}', [UserController::class, 'delete']);
    Route::get('/user/{id}',[UserController::class, 'getUser']);
    Route::get('/users',[UserController::class, 'index']);

    Route::get('/agences',[AgenceController::class , 'index']);
    Route::get('/agence/{id}',[AgenceController::class , 'show']);
    Route::post('/agence/create',[AgenceController::class , 'store']);
    Route::post('/agence/update',[AgenceController::class , 'update']);
    Route::delete('/agence/delete/{id}',[AgenceController::class , 'destroy']);

    Route::post('/voiture/create', [voitureController::class, 'store']);
    Route::get('/voiture/{id}', [voitureController::class, 'show'])->where('path', '.*');
    Route::get('/voitures',[voitureController::class, 'index']);
    Route::post('/voiture/update',[voitureController::class, 'update']);
    Route::delete('/voiture/delete/{id}',[voitureController::class, 'destroy']);

    Route::post('/assurance/create',[AssuranceController::class, 'store']);
    Route::get('/assurances', [AssuranceController::class, "index"]);
    Route::get('/assurance/{id}', [AssuranceController::class, "show"]);
    Route::post('/assurance/update', [AssuranceController::class, "update"]);
    Route::delete('/assurance/delete/{id}',[AssuranceController::class, 'destroy']);

    Route::post('/consommation/create',[ConsommationController::class, 'store']);
    Route::get('/consommations', [ConsommationController::class, "index"]);
    Route::get('/consommation/{id}', [ConsommationController::class, "show"]);
    Route::post('/consommation/update', [ConsommationController::class, "update"]);
    Route::delete('/consommation/delete/{id}',[ConsommationController::class, 'destroy']);

    Route::post('/entretien/create',[EntretienController::class, 'store']);
    Route::get('/entretiens', [EntretienController::class, "index"]);
    Route::get('/entretien/{id}', [EntretienController::class, "show"]);
    Route::post('/entretien/update', [EntretienController::class, "update"]);
    Route::delete('/entretien/delete/{id}',[EntretienController::class, 'destroy']);

    Route::post('/reparation/create',[ReparationController::class, 'store']);
    Route::get('/reparations', [ReparationController::class, "index"]);
    Route::get('/reparation/{id}', [ReparationController::class, "show"]);
    Route::post('/reparation/update', [ReparationController::class, "update"]);
    Route::delete('/reparation/delete/{id}',[ReparationController::class, 'destroy']);

    Route::post('/location/create',[LocationController::class, 'store']);
    Route::get('/locations', [LocationController::class, "index"]);
    Route::get('/location/{id}', [LocationController::class, "show"]);
    Route::post('/location/update', [LocationController::class, "update"]);
    Route::delete('/location/delete/{id}',[LocationController::class, 'destroy']);
});
