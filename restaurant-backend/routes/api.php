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
use App\Http\Controllers\OffreController;
use App\Http\Controllers\RatingController;
use App\Models\Rating;

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
    Route::post('/loginadmin', [UserController::class, 'loginadmin']);
    Route::post('/Signin/google', [UserController::class, 'GoogleSignIn']);
    Route::post('/Signin/googleWebsite', [UserController::class, 'GoogleSignInWebSite']);
    Route::post('/uploadimguser/{id}', [UserController::class, 'uploadimg']);
    Route::post('resetpwd', '\App\Http\Controllers\ChangePasswordController@passwordResetProcess');
    Route::post('resetCoddepwd/{code}', '\App\Http\Controllers\ResetCodeController@VerifCodeReset');
    Route::get('verifExistanceOfCode/{code}', '\App\Http\Controllers\ResetCodeController@verifExistanceCode');
    Route::post('sendEmailForResetCode', '\App\Http\Controllers\ResetCodeController@sendEmail');
    Route::post('sendresetpwd', '\App\Http\Controllers\PasswordResetRequestController@sendEmail');
    Route::get('verify', '\App\Http\Controllers\Auth\RegisterController@verifyUser')->name('verify.user');
    Route::get('existlogin/{login}', [UserController::class, 'verifyExistanceOfLogin']);
    Route::get('existemail/{email}', [UserController::class, 'verifyExistanceOfEmail']);

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
    Route::post('image-upload', [ImageUploadController::class, 'imageUploadPost']);
    Route::get('/nos_plats', [PlatController::class, 'getPlat']);

    //***************************            Categorie         *************************//

    Route::get('categorie/{id}', [CategorieController::class, 'show']);
    Route::get('categorie', [CategorieController::class, 'index']);

    //***************************            worktime          *************************//
    Route::get('restaurent/holiday', [\App\Http\Controllers\HolidayController::class, 'Verif_Time_Work']);
    Route::get('restaurent/info', [\App\Http\Controllers\RestaurantInfoController::class, 'getInfo']);
    Route::get('holiday', [\App\Http\Controllers\HolidayController::class, 'index']);
    Route::get('holiday/{id}', [\App\Http\Controllers\HolidayController::class, 'show']);
    Route::get('time', [\App\Http\Controllers\ScheduleController::class, 'index']);
    Route::get('time/{id}', [\App\Http\Controllers\ScheduleController::class, 'show']);
    //***************************            offre          *************************//
    Route::get('get/offre', [\App\Http\Controllers\OffreController::class, 'index']);
    Route::get('offre/{id}', [\App\Http\Controllers\OffreController::class, 'show']);


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
    Route::get('/reduction/{code}/{prix}', [CodeReductionController::class, 'Reduction']);
    Route::put('/affect', [CodeReductionController::class, 'AffecterToCommandeCodeReduction']);

    //***************************            Commande          *************************//

    Route::get('commande/{id}', [CommandeController::class, 'show']);
    // Route::post('commande', [CommandeController::class, 'store']);

    Route::get('/stripe', [\App\Http\Controllers\StripeController::class, 'getbananas']);
    Route::get('/stripecharges', [\App\Http\Controllers\StripeController::class, 'charges']);
//***************************            Stripe          *************************//
    Route::post('payment/stripe', [\App\Http\Controllers\CommandeController::class, 'store']);


    //***************************            image          *************************//
    Route::delete('image/{id}', [ImageController::class, 'destroy']);
    //***************************            ratings          *************************//
    Route::post('rating', [RatingController::class, 'AffectRatingToUser']);
    Route::get('getrating', [RatingController::class, 'GetUserRating']);

});
/*
|--------------------------------------------------------------------------
| protected routes for admin role
|--------------------------------------------------------------------------
|
|
|
*/

Route::middleware(['auth:sanctum', 'admin', 'json.response'])->group(function () {

    Route::get('statisticplat', [\App\Http\Controllers\statisticsController::class, 'getNbrOfPayedPlatsGroupByPlat']);
    Route::post('statisticplat/', [\App\Http\Controllers\statisticsController::class, 'getNbrOfPayedPlatsGroupByPlatByMounth']);

    //***************************           User        *************************//

    Route::get('/user/{nom}', [UserController::class, 'search']);

    Route::get('users', [UserController::class, 'index']);

    //***************************           Roles         *************************//

    Route::put('/role/{role_id}/{user_id}', [RoleController::class, 'addRoleUser']);
    Route::get('/role/{nom}', [RoleController::class, 'search']);
    Route::resource('role', RoleController::class);

    //***************************            Ingrédient          *************************//

    Route::put('ingredient', [IngredientController::class, 'update']);
    Route::put('changeStatusingredient', [IngredientController::class, 'changeStatus']);
    Route::post('ingredient', [IngredientController::class, 'store']);
    Route::delete('ingredient/{id}', [IngredientController::class, 'destroy']);
    Route::post('ingredient/{ingredient_id}/modificateur/{modificateur_id}', [IngredientController::class, 'addIngredientToModificateur']);
    Route::put('affectingredientmodificateur/{modificateur_id}/{ingredient_id}', [ModificateurController::class, 'affectIngredientToModificateur']);
    Route::put('deleteingredientmodificateur/{modificateur_id}/{ingredient_id}', [ModificateurController::class, 'DetachIngredientFromModificateur']);
    Route::get('ingredientAll', [IngredientController::class, 'showall']);
    //***************************            Supplement          *************************//

    Route::post('supplement', [SupplementController::class, 'store']);
    Route::put('supplement/{id}', [SupplementController::class, 'update']);
    Route::delete('supplement/{id}', [SupplementController::class, 'destroy']);

    //***************************            modificateur          *************************//

    Route::post('modificateur', [ModificateurController::class, 'store']);
    Route::put('modificateur', [ModificateurController::class, 'update']);
    Route::delete('modificateur/{id}', [ModificateurController::class, 'destroy']);
    Route::put('affectModificateurToPlat/{id_plat}/{id_modificateur}', [PlatController::class, 'addPlatToModificateur']);
    Route::put('detachModificateurFromPlat/{id_plat}/{id_modificateur}', [PlatController::class, 'detachPlatFromModificateur']);


    //***************************            Categorie         *************************//

    Route::post('categorie', [CategorieController::class, 'store']);
    Route::put('categorie', [CategorieController::class, 'update']);
    Route::delete('categorie/{id}', [CategorieController::class, 'destroy']);
    Route::put('categorie/{id_categorie}/{id_plat}', [CategorieController::class, 'addPlat']);
    Route::put('categorieDetachPlat/{id_categorie}/{id_plat}', [CategorieController::class, 'detachPlat']);


    //***************************           Plat        *************************//

    Route::post('plat/{id}/image', [PlatController::class, 'addImageToPlat']);
    Route::post('plat', [PlatController::class, 'store']);
    Route::put('plat', [PlatController::class, 'update']);
    Route::delete('plat/{id}', [PlatController::class, 'destroy']);
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
    Route::get('/codereduction', [CodeReductionController::class, 'index']);
    Route::post('/codereduction', [CodeReductionController::class, 'store']);
    Route::put('/codereduction', [CodeReductionController::class, 'update']);
    Route::delete('/codereduction/{id}', [CodeReductionController::class, 'destroy']);
    // Route::resource('codereduction', CodeReductionController::class);
    Route::get('/codereduct/{code}', [CodeReductionController::class, 'searchByCodeExact']);
    Route::get('/codedate/{date}', [CodeReductionController::class, 'searchByDate']);
    Route::put('/affectcode/{id_reduction}/{id_user}', [CodeReductionController::class, 'AffecterUserReduction']);

    //***************************          STATISTICS       *************************//

    // this one is for current year
    ROUTE::get('totalpermonththisyear', [\App\Http\Controllers\statisticsController::class, 'getTotalPricesPerMontheCurrentYear']);
    ROUTE::get('totalpermonth/{year}', [\App\Http\Controllers\statisticsController::class, 'getTotalPricesPerMonthe']);

    //***************************          WORKTIMES       *************************//

    Route::post('holiday', [\App\Http\Controllers\HolidayController::class, 'store']);
    Route::delete('holiday/{id}', [\App\Http\Controllers\HolidayController::class, 'destroy']);
    Route::put('holiday', [\App\Http\Controllers\HolidayController::class, 'update']);


    /*});

    Route::middleware(['auth:sanctum', 'msdigital', 'json.response'])->group(function () {*/

//***************************          RESTAU       *************************//
    Route::get('restau', [\App\Http\Controllers\RestaurantInfoController::class, 'index']);
    Route::get('myrestau', [\App\Http\Controllers\RestaurantInfoController::class, 'myrestau']);
    Route::get('restau/{id}', [\App\Http\Controllers\RestaurantInfoController::class, 'show']);
    Route::put('restau', [\App\Http\Controllers\RestaurantInfoController::class, 'update']);
    Route::post('restau', [\App\Http\Controllers\RestaurantInfoController::class, 'store']);
    Route::delete('restau/{id}', [\App\Http\Controllers\RestaurantInfoController::class, 'destroy']);
    Route::put('affectRestauToHoliday/{idWorkTime}/{idRestaurantInfo}', [\App\Http\Controllers\RestaurantInfoController::class, 'affectHoliday']);
    Route::put('affectRestauToTime/{idschedule}/{idRestaurantInfo}', [\App\Http\Controllers\RestaurantInfoController::class, 'affectTime']);
    Route::put('deleteRestauFromHoliday/{idWorkTime}', [\App\Http\Controllers\RestaurantInfoController::class, 'detachHoliday']);
    Route::put('deleteRestauFromTime/{idWorkTime}', [\App\Http\Controllers\RestaurantInfoController::class, 'detachTime']);
    Route::put('affectUserToRestau/{user_id}/{restau_id}', [\App\Http\Controllers\RestaurantInfoController::class, 'user']);
    Route::put('detachUserFromRestau/{restau_id}', [\App\Http\Controllers\RestaurantInfoController::class, 'detachUser']);

    //***************************            offre          *************************//


    Route::post('offre', [\App\Http\Controllers\OffreController::class, 'store']);
    Route::put('offres/affect/{id_offre}/{id_plat}', [\App\Http\Controllers\OffreController::class, 'affectPlatToOffre']);
    Route::put('offres/destroy/{id_offre}/{id_plat}', [\App\Http\Controllers\OffreController::class, 'DetachPlatFromOffre']);
    Route::delete('offres/{id}', [\App\Http\Controllers\OffreController::class, 'destroy']);
    Route::put('offres/{id}', [\App\Http\Controllers\OffreController::class, 'update']);


    //***************************            Requested_plat          *************************//

    Route::get('requestedplats', [\App\Http\Controllers\RequestedPlatsController::class, 'index']);
    Route::get('requestedplats/{$id}', [\App\Http\Controllers\RequestedPlatsController::class, 'show']);
    Route::post('requestedplats', [\App\Http\Controllers\RequestedPlatsController::class, 'store']);
    Route::post('requestedplats/{$id}', [\App\Http\Controllers\RequestedPlatsController::class, 'destroy']);

    //***************************            time          *************************//
    Route::post('time', [\App\Http\Controllers\ScheduleController::class, 'store']);
    Route::put('time', [\App\Http\Controllers\ScheduleController::class, 'update']);
    Route::delete('time/{id}', [\App\Http\Controllers\ScheduleController::class, 'destroy']);

});
