<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CommandeController;
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

Route::put('categorie/{id_categorie}/{id_plat}', [CategorieController::class, 'addPlat']);
Route::resource('categorie', CategorieController::class);
Route::resource('commande', CommandeController::class);

/*Route::get('/plat', [PlatController::class, 'index']);
Route::get('/plat/{id}', [PlatController::class, 'show']);
Route::post('/plat', [PlatController::class, 'store']);*/
