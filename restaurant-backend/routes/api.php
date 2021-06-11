<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\CategorieController;
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

Route::get('plat-ingredients/{id}',[PlatController::class, 'getIngredient'] );

Route::put('ingredient/{id}',[PlatController::class, 'setIngredient'] );
Route::get('ingredient/{id}',[IngredientController::class, 'showPlats'] );
Route::resource('ingredient', IngredientController::class);

Route::resource('plat', PlatController::class);
Route::get('/plat/{nom}', [PlatController::class, 'search']);

Route::put('categorie/{id_categorie}/{id_plat}', [CategorieController::class, 'addPlat']);
Route::resource('categorie', CategorieController::class);
/*Route::get('/plat', [PlatController::class, 'index']);
Route::get('/plat/{id}', [PlatController::class, 'show']);
Route::post('/plat', [PlatController::class, 'store']);*/
