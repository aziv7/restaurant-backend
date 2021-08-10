<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CodeReduction;
use \App\Enums\Statut;

use App\Models\Commande;
use App\Models\custom;
use App\Models\Ingredient;
use App\Models\Modificateur;
use App\Models\offre;
use App\Models\Plat;
use App\Models\User;
use App\Models\WorkTime;
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

    public function payments(Request $request)
    {
        $stripe = new \Stripe\StripeClient(
            'sk_test_51J9zB2EQevdhZyUKTOZeSfMyd57956WAdKdnUIAS59wkTw7yPXzavY18a92czBGuqNzfXDANAZNRsFcX81jdP04p00t5heW0dE'
        );

        $commande = new Commande();
        $commande->user_id = Auth::id();
        $commande->created_at = Carbon::now();
        $creation_datetime_string = $commande->created_at->toDateTimeString();
        //somme des plats id
        $somme_plat_id = 0;
        // je vais avoir une liste des id des plats choisits pour les affecter au commande
        foreach ($request->card as $plat) {
            $somme_plat_id = $somme_plat_id + $plat["id"];

        }
        //id de la commande = (userid+somme des platid)*357 puis un / puis la date de la création de commande
        $chaine = (Auth::id() + $somme_plat_id) * 357;
        $chaine_string = "id" . (string)$chaine . "/" . $creation_datetime_string;
        $id = Hash::make($chaine_string);
        $commande->commande_id = $id;
        $commande->prix_total = 0;

        if ($request->checkout) {
            $commande->datepaiment = Carbon::now(); // $request->checkout["date_payment"];
        } else $commande->datepaiment = null;

        //création de commande sans plats
        DB::insert('insert into commandes (commande_id, user_id,  created_at, date_paiement, date_traitement,status) values (?,?,?,?,?,?)', [$commande->commande_id, Auth::id(), $commande->created_at, $commande->datepaiment, null,Statut::getKey(0)]);
        $custom = new custom();
    // var_dump(Statut::getKey(0));
        // affectation des plats sans modificateur au commande
        foreach ($request->card as $i => $plat) {
            $commande->prix_total = $commande->prix_total + ($plat["prix"] * $plat["quantity"]);
            $p = Plat::find($plat["id"]);
            //affecter le plat à la commande
            $commande->plat()->attach($p);
            //parcourir les plats pour traiter les customs
            foreach ($plat["modificateurs"] as $j => $modificateur) {
                if ($modificateur["checked"] == true) {
                    $custom->nom = $modificateur["nom"];
                    $custom->prix = $modificateur["prix"]*$plat["quantity"];
                    //insertion du custom dans la base
                    $custom = custom::create($modificateur);
                    //affectation du custm au plat
                    $plat1 = Plat::find($plat['id']);
                    $plat1->customs()->attach($custom);
                    $commande->prix_total = $commande->prix_total + $modificateur["prix"];
                    var_dump($commande->prix_total);
                }
                //parcourir les modificateurs pour traiter les ingrédients
                foreach ($modificateur["ingredients"] as $ingredient) {
                    if ($ingredient["checked"]==true){
                        $ing = Ingredient::find($ingredient["id"]);
                        //affecter ingrédient à son custom
                        $custom->ingredients()->attach($ing);
                        $commande->prix_total = $commande->prix_total + $ingredient["prix"]*$plat["quantity"];
                    }
                }
            }
        }
        if ($request->cartOffre) {
            foreach ($request->cartOffre as $i => $offre) {
                $commande->prix_total = $commande->prix_total + ($offre["prix"] * $offre["quantity"]);
                $c = Commande::where('commande_id', 'like', $id)->first();
                $o = offre::find($offre["id"]);
                DB::insert('insert into offre_commande (commande_id, offre_id, created_at) values (?, ?, ?)', [$id, $o->id, Carbon::now()]);
                // $c->Offres()->attach($o);
            }
        }
        $priceStripe = $request->prixtot;
if($request->idCodRed)
      {  $this->AffecterToCommandeCodeReduction($request->idCodRed,$id);
 $code_reduction=CodeReduction::find($request->idCodRed);
$taux=$code_reduction->taux_reduction;
$commande->prix_total=($commande->prix_total*$taux)/100;//var_dump($prixreduit);

  } var_dump($priceStripe);
var_dump($commande->prix_total);   
  if ($commande->prix_total== $priceStripe) {
            //inserer le prix total dans la db
            DB::update('update commandes set prix_total = ? where commande_id = ?', [$commande->prix_total, $id]);
        } else {
            DB::delete('DELETE FROM `commandes` WHERE `commandes`.`commande_id` =?', [$id]);
            return response(array(
                'message' => 'disordance de prix',
            ), 403);
        }
        $pay = $stripe->charges->create([
            'amount' => $commande->prix_total * 100,
            'currency' => 'eur',
            'source' => $request->token,
            'description' => 'payment',
        ]);


      return   $response = [   'prixtotal'=> $request->prixtot,
      'cart'=>$request->card,
      'cartOffre'=>$request->cartOffre,
      'idCommande'=>$c->commande_id,
'status'=>$c->status

        ];

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
        //  var_dump($Commande);
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
}
