<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModificateurController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ImageUploadController;

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

Route::get('image-upload', [ ImageUploadController::class, 'imageUpload' ]);
Route::post('image-upload', [ ImageUploadController::class, 'imageUploadPost' ]);

Route::get('modificateur/ingredients/{id}',[ModificateurController::class, 'getIngredients'] );
Route::get('modificateur/plats/{id}',[ModificateurController::class, 'getPlats'] );
Route::resource('modificateur', ModificateurController::class);


Route::post('plat/{plat_id}/modificateur/{modificateur_id}',[PlatController::class, 'addPlatToModificateur'] );


Route::post('ingredient/{ingredient_id}/modificateur/{modificateur_id}', [IngredientController::class, 'addIngredientToModificateur']);
Route::resource('ingredient', IngredientController::class);

Route::get('/plat/{id}/modificateurs', [PlatController::class, 'getModificateurs']);
Route::resource('plat', PlatController::class);
Route::get('/plat/{nom}', [PlatController::class, 'search']);

Route::put('codered/{id_commande}/{id_reduction}', [CodeReductionController::class, 'addReduction']);

Route::put('command/{id_commande}/{id_plat}', [PlatController::class, 'addCommande']);

Route::put('categorie/{id_categorie}/{id_plat}', [CategorieController::class, 'addPlat']);
Route::resource('categorie', CategorieController::class);

Route::get('/user/{nom}', [UserController::class, 'search']);
Route::resource('user', UserController::class);


Route::put('/role/{role_id}/{user_id}',[RoleController::class,'addRoleUser']);
Route::get('/role/{nom}', [RoleController::class, 'search']);
Route::resource('role', RoleController::class);

//Route::get('/add-roles',[RoleController::class,'addRole']);

Route::resource('commande', CommandeController::class);
Route::resource('codereduction', CodeReductionController::class);
Route::get('/codereduc/{code}', [CodeReductionController::class, 'searchByCode']);
/*Route::get('/plat', [PlatController::class, 'index']);
Route::get('/plat/{id}', [PlatController::class, 'show']);
Route::post('/plat', [PlatController::class, 'store']);*/
