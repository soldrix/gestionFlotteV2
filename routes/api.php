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
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/voiture/{id}',[voitureController::class, 'show']);
    Route::get('/voitures/agence/{id}',[voitureController::class, 'indexAgence']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::controller(UserController::class)->group(function (){
        Route::post('user/edit/first_name', "update_first_name");
        Route::post('user/edit/last_name', "update_last_name");
        Route::post('user/edit/email', "update_email");
        Route::post('user/edit/password', "update_password");
    });
    Route::delete('/user/delete/{id}', [UserController::class, 'delete']);
    Route::get('/user/{id}',[UserController::class, 'getUser']);

    Route::get('/agences',[AgenceController::class , 'index']);

    Route::post('/location/create',[LocationController::class, 'store']);
    Route::get('/locations', [LocationController::class, "index"]);
    Route::get('/locations/user/{id}',[LocationController::class, 'indexUser']);
    Route::get('/location/{id}', [LocationController::class, "show"]);
    Route::post('/location/update', [LocationController::class, "update"]);
    Route::delete('/location/delete/{id}',[LocationController::class, 'destroy']);
});
