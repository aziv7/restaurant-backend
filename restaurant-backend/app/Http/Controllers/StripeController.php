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

    public function payments(Request $request)
    {
        $access = false;
        $temps = DB::select('select * from schedules where restaurant_info_id = ?', [1]);
        $holidays = DB::select('select * from holidays where restaurant_info_id = ?', [1]);
        $now = Carbon::now();
        // var_dump((int)$now->format('H'));
        $jour = $now->format('D');
        $heure = $now->format('H');
        $minute = $now->format('m');
        $day = '';
        switch ($jour) {
            case 'Mon':
                $day = 'monday';
                break;
            case 'Tue':
                $day = 'tuesday';
                break;
            case 'Wed':
                $day = 'wednesday';
                break;
            case 'Thu':
                $day = 'thursday';
                break;
            case 'Fri':
                $day = 'friday';
                break;
            case 'Sat':
                $day = 'saturday';
                break;
            case 'Sun':
                $day = 'sunday';
                break;
        }
        foreach ($temps as $t) {
            $debutH = (int)substr($t->start, 0, 2);
            $finH = (int)substr($t->end, 0, 2);
            $debutM = (int)substr($t->start, 3, 2);
            $finM = (int)substr($t->end, 3, 2);
            if ($day == $t->day && $debutH <= $heure && $finH >= $heure && $debutM <= $minute && $finM >= $minute) {
                $access = true;
            }
        }
        foreach ($holidays as $h) {
            if (Carbon::now()->isoFormat('Y-MM-DD') == substr($h->holiday, 0, 10))
                $access = false;
        }

        $info = RestaurantInfo::all()->first();
        // in work time
        if ($access) {
            $stripe = new \Stripe\StripeClient(
                $info->public_key_stripe);
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
            // getting the nbr of all cmd to know the order of this cmd
            $nbr_of_precedent_commandes = Commande::all()->count();
            $chaine_string = "id" . (string)$chaine . "/" . $creation_datetime_string . "/" . $nbr_of_precedent_commandes;
            $id = Hash::make($chaine_string);
            $commande->commande_id = $id;
            $commande->prix_total = 0;
            if ($request->method_payment != 'on_delivery') {

                //création de commande sans plats
                DB::insert('insert into commandes (commande_id, user_id,  created_at, date_paiement, date_traitement,status, prix_total) values (?,?,?,?,?,?,?)', [$id, Auth::id(), $commande->created_at, Carbon::now(), null, Statut::getKey(1), null]);
            } else if ($request->method_payment == 'on_delivery')
                DB::insert('insert into commandes (commande_id, user_id,  created_at, date_paiement, date_traitement, prix_total) values (?,?,?,?,?,?)', [$id, Auth::id(), $commande->created_at, null, null, null]);


            $custom = new custom();
            // affectation des plats sans modificateur au commande
            foreach ($request->card as $i => $plat) {
                $commande->prix_total = $commande->prix_total + ($plat["prix"] * $plat["quantity"]);
                $p = Plat::find($plat["id"]);
                // create new requested_plat
                $idrequestedplat = DB::table('requested_plats')->insertGetId(
                    ['nom' => $plat["nom"], 'prix' => $plat["prix"], 'description' => $plat["description"]]
                );
                $requestedPlat = RequestedPlat::find($idrequestedplat);

                //affecter le plat à la commande
                $commande->requested_plat()->attach($requestedPlat, ['quantity' => $plat["quantity"]]);
                $allrequestedplatid = array();
                array_push($allrequestedplatid, $requestedPlat->id);
                //parcourir les plats pour traiter les customs
                foreach ($plat["modificateurs"] as $j => $modificateur) {
                    if ($modificateur["checked"] == true) {
                        $custom->nom = $modificateur["nom"];
                        $custom->prix = $modificateur["prix"] * $plat["quantity"];
                        //insertion du custom dans la base
                        $custom = custom::create($modificateur);
                        //affectation du custm au requested_plat
                        $plat1 = RequestedPlat::find($allrequestedplatid[$j]);
                        DB::insert('insert into requested_plats_custom (custom_id, requested_plats_id) values (?, ?)', [$custom->id, $allrequestedplatid[$j]]);
                        // $plat1->customs()->attach($custom);
                    }
                    //parcourir les modificateurs pour traiter les ingrédients
                    foreach ($modificateur["ingredients"] as $ingredient) {
                        if ($ingredient["checked"] == true) {
                            $ing = Ingredient::find($ingredient["id"]);
                            //affecter ingrédient à son custom
                            $custom->ingredients()->attach($ing);
                            $commande->prix_total = $commande->prix_total + $modificateur["prix"] * $plat["quantity"];
                        }
                    }
                }
            }
            if ($request->cartOffre) {
                foreach ($request->cartOffre as $i => $offre) {
                    $commande->prix_total = $commande->prix_total + ($offre["prix"] * $offre["quantity"]);
                    $c = Commande::where('commande_id', 'like', $id)->first();
                    $o = offre::find($offre["id"]);
                    DB::insert('insert into offre_commande (commande_id, offre_id, created_at, quantity) values (?, ?, ?, ?)', [$id, $o->id, Carbon::now(), $offre["quantity"]]);
                    // $c->Offres()->attach($o);
                }
            }

            $priceStripe = $request->prixtot;
            if ($request->idCodRed) {
                $this->AffecterToCommandeCodeReduction($request->idCodRed, $id);
                $code_reduction = CodeReduction::find($request->idCodRed);
                $taux = $code_reduction->taux_reduction;
                $commande->prix_total = ($commande->prix_total * $taux) / 100;
            }
            // ajout prix de livraison
            if ($request->livraison == true) {
                $prix_livraison = DB::select('SELECT `prixlivraison` FROM `restaurant_infos`');
                $commande->prix_total = $commande->prix_total + $prix_livraison[0]->prixlivraison;
                $commande->longitude = $request->longitude;
                $commande->latitude = $request->latitude;
                $commande->livraison = $request->livraison;
                $commande->date_paiement = Carbon::now();
                $commande->livraison_address = $request->address;
            }
            if ($commande->prix_total == $priceStripe) {
                //inserer le prix total dans la db
                DB::table('commandes')
                    ->where('commande_id', $id)
                    ->update([
                        'prix_total' => $commande->prix_total,
                        'longitude' => $commande->longitude,
                        'latitude' => $commande->latitude,
                        'livraison' => $request->livraison,
                        'livraison_address' => $commande->livraison_address
                    ]);

            } else {
                DB::delete('DELETE FROM `commandes` WHERE `commandes`.`commande_id` =?', [$id]);
                return response(array(
                    'message' => 'disordance de prix',
                ), 403);
            }
            if ($request->method_payment == 'stripe') {
                $pay = $stripe->charges->create([
                    'amount' => $commande->prix_total * 100,
                    'currency' => 'eur',
                    'source' => $request->token,
                    'description' => 'payment',
                ]);
            }

            $c = Commande::where('commande_id', 'like', $id)->first();
            return $response = ['prixtotal' => $request->prixtot,
                'cart' => $request->card,
                'cartOffre' => $request->cartOffre,
                'idCommande' => $id,
                'status' => $c->status,
                'user_id' => Auth::id(),
                'created_at' => Carbon::now(),
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'addresse' => $request->address
            ];
        }
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
}
