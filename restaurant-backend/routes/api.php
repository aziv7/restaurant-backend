<?php

use App\Http\Controllers\ModificateurController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SupplementController;
use App\Http\Controllers\StreamController;



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

Route::get('/stream', [PlatController::class, 'StreamController']);
//Route::get('image-upload', [ ImageUploadController::class, 'imageUpload' ]);


Route::get('modificateur/{id}/supplements', [ModificateurController::class, 'getSupplements']);
Route::post('supplement/{id}/modificateur/{modificateur_id}', [SupplementController::class, 'addSupplementToModificateur']);

Route::get('plat/{id}/supplements', [PlatController::class, 'getSupplements']);

Route::post('plat/{id}/image', [PlatController::class, 'addImageToPlat']);
Route::post('image-upload', [ImageUploadController::class, 'imageUploadPost']);
Route::get('modificateur/ingredients/{id}', [ModificateurController::class, 'getIngredients']);
Route::get('modificateur/plats/{id}', [ModificateurController::class, 'getPlats']);

Route::resource('modificateur', ModificateurController::class);



Route::post('plat/{id}/supplement/{supplement_id}', [PlatController::class, 'addSupplementToPlat']);

Route::resource('supplement', SupplementController::class);

Route::post('plat/{plat_id}/modificateur/{modificateur_id}', [PlatController::class, 'addPlatToModificateur']);


Route::post('ingredient/{ingredient_id}/modificateur/{modificateur_id}', [IngredientController::class, 'addIngredientToModificateur']);
Route::resource('ingredient', IngredientController::class);

Route::get('/plat/{id}/modificateurs', [PlatController::class, 'getModificateurs']);
Route::resource('plat', PlatController::class);
Route::get('/plat/{nom}', [PlatController::class, 'search']);

Route::put('categorie/{id_categorie}/{id_plat}', [CategorieController::class, 'addPlat']);
Route::resource('categorie', CategorieController::class);
/*Route::get('/plat', [PlatController::class, 'index']);
Route::get('/plat/{id}', [PlatController::class, 'show']);
Route::post('/plat', [PlatController::class, 'store']);*/
