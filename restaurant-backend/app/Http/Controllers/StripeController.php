<?php

namespace App\Http\Controllers;

use App\Models\RequestedPlat;
use Carbon\Carbon;
use App\Models\CodeReduction;
use \App\Enums\Statut;
use App\Models\RestaurantInfo;

use App\Models\Commande;
use App\Models\custom;
use App\Models\Ingredient;
use App\Models\Modificateur;
use App\Models\offre;
use App\Models\Plat;
use App\Models\User;
use App\Models\Holiday;
use Faker\Core\Number;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use MongoDB\BSON\Timestamp;
use PayPal\Api\CustomAmount;
use PhpParser\Node\Expr\Array_;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function getbananas()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        return $stripe->balance->retrieve();

    }

    public static function payments($request, $commande, $id)
    {
        $info = RestaurantInfo::all()->first();
        $stripe = new \Stripe\StripeClient($info->secret_key_stripe);
        $pay = $stripe->charges->create([
            'amount' => $request->prixtot * 100,
            'currency' => 'eur',
            'source' => $request->token,
            'description' => 'payment',
        ]);
        $checkout = ['prixtotal' => $request->prixtot,
            'cart' => $request->card,
            'cartOffre' => $request->cartOffre,
            'idCommande' => $id,
            'status' => $commande->status,
            'user_id' => Auth::id(),
            'created_at' => Carbon::now(),
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'addresse' => $request->address
        ];
        return $checkout;
    }

    public function charges(Request $request)
    {
        $stripe = new \Stripe\StripeClient(
            'sk_test_51J9zB2EQevdhZyUKTOZeSfMyd57956WAdKdnUIAS59wkTw7yPXzavY18a92czBGuqNzfXDANAZNRsFcX81jdP04p00t5heW0dE'
        );

        return $stripe->charges->all();
    }

    public function AffecterToCommandeCodeReduction($id_reduction, $id_commande)
    {

        $codered = CodeReduction::find($id_reduction);
        $Commande = Commande::where('commande_id', 'like', $id_commande)->first();
        if (!$codered) {
            return response(array(
                'message' => 'Code Reduction Not Found',
            ), 404);
        }
        if (!$Commande) {
            return response(array(
                'message' => 'Commande Not Found',
            ), 404);
        }
        if ($codered->statut == 0) {
            return response(array(
                'message' => 'Code reduction already used',
            ), 404);
        }
        //   $Commande->code_reduction_id=$codered->id;
        //   $editdata = array(
        //     'code_reduction_id' => $codered->id
        //);
        // $Commande->update($editdata);
        DB::table('commandes')->where('commande_id', $id_commande)->update([

            'code_reduction_id' => $codered->id
        ]);
        if ($codered->user_id != null) {
            $editdata = array(
                'statut' => 0
            );
            $codered->update($editdata);
        }
        return $codered;

    }

    public  function getPublicKeyStripe()
{
    $info = RestaurantInfo::all()->first();
    return response(array(
        'message' => $info->public_key_stripe,
    ));

}
}
