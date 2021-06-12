<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\CodeReductionController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('plat', PlatController::class);
Route::get('/plat/{nom}', [PlatController::class, 'search']);
Route::put('codered/{id_commande}/{id_reduction}', [CodeReductionController::class, 'addReduction']);
Route::put('command/{id_commande}/{id_plat}', [PlatController::class, 'addCommande']);
Route::put('categorie/{id_categorie}/{id_plat}', [CategorieController::class, 'addPlat']);
Route::resource('categorie', CategorieController::class);
Route::resource('commande', CommandeController::class);
Route::resource('codereduction', CodeReductionController::class);
Route::get('/codereduc/{code}', [CodeReductionController::class, 'searchByCode']);
Route::get('/codereduct/{code}', [CodeReductionController::class, 'searchByCodeExact']);
Route::get('/codere/{code}', [CodeReductionController::class, 'VerifExistanceCode']);
Route::get('/codedate/{date}', [CodeReductionController::class, 'searchByDate']);
Route::get('/getcodeverifdate/{date}', [CodeReductionController::class, 'getallVerifDate']);
Route::get('/codeverifdate/{id}', [CodeReductionController::class, 'VerifDateExpire']);
Route::get('/verifvalidite/{code}', [CodeReductionController::class, 'VerifCode']);

/*Route::get('/plat', [PlatController::class, 'index']);
Route::get('/plat/{id}', [PlatController::class, 'show']);
Route::post('/plat', [PlatController::class, 'store']);*/
