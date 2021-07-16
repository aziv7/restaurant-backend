<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Events\test;
use App\Http\Controllers\ModificateurController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SupplementController;

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





Route::get('/nos_plats', [PlatController::class, 'getPlat']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Accessible by everyone
|--------------------------------------------------------------------------
|
|
|
*/



Route::middleware(['json.response'])->group(function () {
    //***************************             USER           *************************//
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'store']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('resetpwd', '\App\Http\Controllers\ChangePasswordController@passwordResetProcess');
    Route::post('sendresetpwd', '\App\Http\Controllers\PasswordResetRequestController@sendEmail');
    Route::get('verify', '\App\Http\Controllers\Auth\RegisterController@verifyUser')->name('verify.user');
    //***************************            modificateur          *************************//


    Route::get('modificateur/{id}', [ModificateurController::class, 'show']);
    Route::get('modificateur', [ModificateurController::class, 'index']);
    Route::get('modificateur/ingredients/{id}', [ModificateurController::class, 'getIngredients']);
    Route::get('modificateur/plats/{id}', [ModificateurController::class, 'getPlats']);

    //***************************            Ingrédient          *************************//
    Route::get('ingredient', [IngredientController::class, 'index']);
    Route::get('ingredient/{id}', [IngredientController::class, 'show']);
    //***************************            Supplement          *************************//

    Route::get('supplement', [SupplementController::class, 'index']);
    Route::get('supplement/{id}', [SupplementController::class, 'show']);

    //***************************            Plat          *************************//

    Route::get('plat/{id}/supplement', [PlatController::class, 'getSupplements']);
    Route::get('/plat/{id}/modificateurs', [PlatController::class, 'getModificateurs']);
    Route::get('plat', [PlatController::class, 'index']);
    Route::get('plat/{id}', [PlatController::class, 'show']);
    Route::get('/plat/{nom}', [PlatController::class, 'search']);

    //***************************            Categorie         *************************//

    Route::get('categorie/{id}', [CategorieController::class, 'show']);
    Route::get('categorie', [CategorieController::class, 'index']);
    //***************************            Stripe          *************************//
    Route::post('payment/stripe', [\App\Http\Controllers\StripeController::class, 'payments']);


});



/*
|--------------------------------------------------------------------------
| protected routes for connected people
|--------------------------------------------------------------------------
|
|
|
*/


Route::middleware(['auth:sanctum', 'json.response'])->group(function () {

    //***************************            User          *************************//

    Route::post('logout', [UserController::class, 'logout']);
    Route::post('/uploadimguser/{id}', [UserController::class, 'uploadimg']);
    Route::get('connected', [UserController::class, 'Connected']);
    Route::get('getuser/id/{id}', [UserController::class, 'GetUserByIdWithCoordonnes']);
    Route::get('getuser', [UserController::class, 'GetUsersWithCoordonnes']);
    Route::put('user/{id}', [UserController::class, 'update']);

    //***************************            Ingrédient          *************************//


    //***************************            modificateur          *************************//


    //***************************            Plat          *************************//

    Route::put('command/{id_commande}/{id_plat}', [PlatController::class, 'addCommande']);

    //***************************            Code reduction          *************************//

    Route::put('codered/{id_commande}/{id_reduction}', [CodeReductionController::class, 'addReduction']);
    Route::get('/codere/{code}', [CodeReductionController::class, 'VerifExistanceCode']);
    Route::get('/codeverifdate/{id}', [CodeReductionController::class, 'VerifDateExpire']);
    Route::get('/verifvalidite/{code}', [CodeReductionController::class, 'VerifCode']);

    //***************************            Commande          *************************//

    Route::get('commande/{id}', [CommandeController::class, 'show']);
    Route::post('commande', [CommandeController::class, 'store']);
});
Route::get('/stripe', [\App\Http\Controllers\StripeController::class, 'getbananas']);
Route::get('/stripecharges', [\App\Http\Controllers\StripeController::class, 'charges']);

/*
|--------------------------------------------------------------------------
| protected routes for admin role
|--------------------------------------------------------------------------
|
|
|
*/

Route::middleware(['auth:sanctum', 'admin', 'json.response'])->group(function () {

    //***************************           User        *************************//

    Route::get('/user/{nom}', [UserController::class, 'search']);

    Route::get('users', [UserController::class, 'index']);

    //***************************           Roles         *************************//

    Route::put('/role/{role_id}/{user_id}', [RoleController::class, 'addRoleUser']);
    Route::get('/role/{nom}', [RoleController::class, 'search']);
    Route::resource('role', RoleController::class);

    //***************************            Ingrédient          *************************//

    Route::put('ingredient', [IngredientController::class, 'update']);
    Route::post('ingredient', [IngredientController::class, 'store']);
    Route::delete('ingredient/{id}', [IngredientController::class, 'destroy']);
    Route::post('ingredient/{ingredient_id}/modificateur/{modificateur_id}', [IngredientController::class, 'addIngredientToModificateur']);
    Route::put('affectingredientmodificateur/{modificateur_id}/{ingredient_id}', [ModificateurController::class, 'affectIngredientToModificateur']);
    //***************************            Supplement          *************************//

    Route::post('supplement', [SupplementController::class, 'store']);
    Route::put('supplement/{id}', [SupplementController::class, 'update']);
    Route::delete('supplement/{id}', [SupplementController::class, 'destroy']);

    //***************************            modificateur          *************************//

    Route::post('modificateur',  [ModificateurController::class, 'store']);
    Route::put('modificateur',  [ModificateurController::class, 'update']);
    Route::delete('modificateur/{id}',  [ModificateurController::class, 'destroy']);
    Route::put('affectModificateurToPlat/{id_plat}/{id_modificateur}', [PlatController::class, 'addPlatToModificateur']);


    //***************************            Categorie         *************************//

    Route::post('categorie', [CategorieController::class, 'store']);
    Route::put('categorie/{id}', [CategorieController::class, 'update']);
    Route::delete('categorie/{id}', [CategorieController::class, 'destroy']);
    Route::put('categorie/{id_categorie}/{id_plat}', [CategorieController::class, 'addPlat']);


    //***************************           Plat        *************************//

    Route::post('plat/{id}/image', [PlatController::class, 'addImageToPlat']);
    Route::post('plat',  [PlatController::class, 'store']);
    Route::put('plat',  [PlatController::class, 'update']);
    Route::delete('plat/{id}',  [PlatController::class, 'destroy']);
    Route::post('image-upload', [ImageUploadController::class, 'imageUploadPost']);
    Route::post('plat/{plat_id}/modificateur/{modificateur_id}', [PlatController::class, 'addPlatToModificateur']);
    Route::post('plat/{id}/supplement/{supplement_id}', [PlatController::class, 'addSupplementToPlat']);


    //***************************           Commande        *************************//

    Route::get('/deletecommandes', [CommandeController::class, 'DisplayDeletedCommand']);
    Route::get('/allcommandes', [CommandeController::class, 'DisplayAllCommand']); //even the deleted ones
    Route::get('commande', [CommandeController::class, 'index']);
    Route::put('commande', [CommandeController::class, 'update']);
    Route::delete('commande/{id}', [CommandeController::class, 'destroy']);
    Route::get('verifcommand/{id}', [CommandeController::class, 'VerifCommande']);

    //***************************          Code de reduction       *************************//

    Route::get('/deletecodes', [CodeReductionController::class, 'DisplayDeletedCode']);
    Route::get('/codereduc/{code}', [CodeReductionController::class, 'searchByCode']);
    Route::get('/allcodes', [CodeReductionController::class, 'DisplayAllCodes']);
    Route::get('/getcodeverifdate/{date}', [CodeReductionController::class, 'getallVerifDate']);
    Route::resource('codereduction', CodeReductionController::class);
    Route::get('/codereduct/{code}', [CodeReductionController::class, 'searchByCodeExact']);
    Route::get('/codedate/{date}', [CodeReductionController::class, 'searchByDate']);
    Route::put('/affectcode/{id_reduction}/{id_user}', [CodeReductionController::class, 'AffecterUserReduction']);

    //***************************          STATISTICS       *************************//

    // this one is for current year
    ROUTE::get('totalpermonththisyear', [\App\Http\Controllers\statisticsController::class, 'getTotalPricesPerMontheCurrentYear']);
    ROUTE::get('totalpermonth/{year}', [\App\Http\Controllers\statisticsController::class, 'getTotalPricesPerMonthe']);
});
