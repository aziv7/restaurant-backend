<?php

namespace App\Http\Controllers;

use App\Enums\Statut;
use App\Models\CodeReduction;
use App\Models\Commande;
use App\Models\custom;
use App\Models\custom_offre;
use App\Models\Ingredient;
use App\Models\Modificateur;
use App\Models\offre;
use App\Models\Plat;
use App\Models\RequestedPlat;
use App\Models\RestaurantInfo;
use App\Models\User;
use App\Models\Holiday;
use Carbon\Carbon;
use Faker\Core\Number;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use MongoDB\BSON\Timestamp;
use PayPal\Api\CustomAmount;
use PhpParser\Node\Expr\Array_;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $commandes = Commande::with('requested_plat', 'user', 'requested_plat.customs', 'requested_plat.customs.ingredients', 'Offres', 'Offres.requested_plats', 'requested_plat.customs', 'requested_plat.customs.ingredients')
            ->get();
        return $commandes;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $access = HolidayController::Verif_Time_Work();
        if ($access) {
            $commande = new Commande();
            $commande->user_id = Auth::id();
            $commande->created_at = Carbon::now()->format('Y-M-d h:m:s');
            $creation_datetime_string = Carbon::now()->format('Y-m-d');
            //somme des plats id
            $somme_plat_id = 0;
            // je vais avoir une liste des id des plats choisits pour les affecter au commande
            foreach ($request->card as $plat) {
                $somme_plat_id = $somme_plat_id + $plat["id"];
            }
            // id de la commande = (userid+somme des platid)*357 puis un / puis la date de la création de commande
            $chaine = (Auth::id() + $somme_plat_id) * 357;
            $chaine_string = "id" . (string)$chaine . "/" . $creation_datetime_string;
            // getting the nbr of all cmd to know the order of this cmd
            $nbr_of_precedent_commandes = Commande::all()->count();
            $chaine_string = "id" . (string)$chaine . "/" . $creation_datetime_string . "/" . $nbr_of_precedent_commandes;
            $id = Hash::make($chaine_string);
            $commande->commande_id = $id;
            $commande->prix_total = 0;

            // list of all requested plats
            $allrequestedPlats = array();
            // creation of requested plats
            foreach ($request->card as $i => $plat) {
                $commande->prix_total = $commande->prix_total + ($plat["prix"] * $plat["quantity"]);
                // create new requested_plat
                $rp = $this->createRequestedPlat($plat);
                array_push($allrequestedPlats, $rp);
                $commande->requested_plat()->attach($rp, ['quantity' => $plat["quantity"]]);

                //parcourir les plats pour traiter les customs
                foreach ($plat["modificateurs"] as $j => $modificateur) {
                    if ($modificateur["checked"] == true)
                    {
                        $cust = $this->createCustom($modificateur, $plat["quantity"]);
                        $allcustoms = array();
                        array_push($allcustoms, $cust);
                        //affectation du custm au requested_plat
                        $rp->customs()->attach($cust);
                        //parcourir les modificateurs pour traiter les ingrédients
                        foreach ($modificateur["ingredients"] as $ingredient) {
                            if ($ingredient["checked"] == true) {
                                $alling = array();
                                array_push($alling, $ingredient);
                                $ing = Ingredient::find($ingredient["id"]);
                                //affecter ingrédient à son custom
                                $cust->ingredients()->attach($ing);
                                $commande->prix_total = $commande->prix_total + $modificateur["prix"] * $plat["quantity"];
                            }
                        }
                    }
                }
            }

            //parcour des offres s'il y'en a
            if ($request->cartOffre) {
                foreach ($request->cartOffre as $i => $offre) {
                    $commande->prix_total = $commande->prix_total + ($offre["prix"] * $offre["quantity"]);
                    $o = offre::find($offre->id);
                    $co = $this->createcustomoffre($o);

                    // DB::insert('insert into offre_commande (commande_id, offre_id, created_at, quantity) values (?, ?, ?, ?)', [$id, $o->id, Carbon::now(), $offre["quantity"]]);
                    $commande->custom_offres()->attach($o, ['quantity' => $offre["quantity"]]);

                    // creation of requested plats
                    foreach ($offre["plats"] as $c => $plat) {
                        // create new requested_plat
                        $rp = $this->createRequestedPlat($plat);
                        $co->requested_plats()->attach($rp, ['quantity' => 1]);
                        //parcourir les plats pour traiter les customs
                        foreach ($plat["modificateurs"] as $m => $mod) {
                                $checked = $mod['checked'];
                                if ($checked == true)
                                {
                                    $cust = $this->createCustom($mod, 1);
                                    //affectation du custm au requested_plat
                                    $rp->customs()->attach($cust);
                                    //parcourir les modificateurs pour traiter les ingrédients
                                    foreach ($mod["ingredients"] as $ingredient) {
                                        if ($ingredient["checked"] == true) {
                                            $ing = Ingredient::find($ingredient["id"]);
                                            //affecter ingrédient à son custom
                                            $cust->ingredients()->attach($ing);
                                        }
                                    }
                                }
                        }
                    }
                }
            }

            if ($request->idCodRed) {
                $commande = CodeReductionController::AffecterToCommandeCodeReduction($request->idCodRed, $commande);
            }

            if ($request->livraison == true) {
                $prix_livraison = DB::select('SELECT `prixlivraison` FROM `restaurant_infos`');
                $commande->prix_total = $commande->prix_total + $prix_livraison[0]->prixlivraison;
                $commande->longitude = $request->longitude;
                $commande->latitude = $request->latitude;
                $commande->livraison = $request->livraison;
                $commande->livraison_address = $request->address;
            }

            $checkout = null;
            $priceStripe = $request->prixtot;
            if ($commande->prix_total == $priceStripe) {
                $info = RestaurantInfo::all()->first();
                $stripe = new \Stripe\StripeClient($info->secret_key_stripe);
                if ($request->method_payment == 'stripe') {
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
                }
            }

            if ($checkout != null) {
                $commande->date_paiement = Carbon::now();
                $commande->status = Statut::getKey(1);
                $commande->paiement_modality = "Stripe";
            } else {
                $commande->status = Statut::getKey(0);
            }

            $success_command_insert = DB::insert('insert into commandes (commande_id, user_id,  created_at, date_paiement, date_traitement,status, prix_total, longitude, latitude, livraison, livraison_address, code_reduction_id) values (?,?,?,?,?,?,?,?,?,?,?,?)', [$id, Auth::id(), $commande->created_at, $commande->date_paiement, null, $commande->status, $commande->prix_total, $commande->longitude, $commande->latitude, $commande->livraison, $commande->livraison_address, $commande->code_reduction_id]);
            if (!$success_command_insert) {
                foreach ($allrequestedPlats as $r => $reqp) {
                    RequestedPlat::destroy($reqp->id);
                }

                foreach ($allcustoms as $cu => $custo) {
                    custom::destroy($custo->id);
                }

                foreach ($alling as $in => $ingred) {
                    Ingredient::destroy($ingred->id);
                }

                $response = [
                    'message' => 'problem'
                ];
                return response($response, 500);
            }
            if ($checkout)
            {
                return $checkout;
            } else
            {
                return ['prixtotal' => $request->prixtot,
                    'cart' => $request->card,
                    'cartOffre' => $request->cartOffre,
                    'idCommande' => $id,
                    'status' => $commande->status,
                    'user_id' => Auth::id(),
                    'created_at' => $commande->created_at,
                    'longitude' => $commande->longitude,
                    'latitude' => $commande->latitude,
                    'addresse' => $commande->address
                ];
            }

        }

    }

    function createRequestedPlat($p)
    {
        $r = new RequestedPlat();
        $r->nom = $p["nom"];
        $r->prix = $p["prix"];
        $r->description = $p["description"];
        $r->statut = $p["statut"];
        $r->created_at = Carbon::now();
        $requestedPlat = RequestedPlat::create($r->toArray());
        return $requestedPlat;
    }

    function createcustomoffre($o)
    {
        $custom_offre = new custom_offre();
        $custom_offre->nom = $o->nom;
        $custom_offre->prix = $o->prix;
        $custom_offre->id = DB::table('custom_offres')
            ->insertGetId(
                [
                    'nom' =>$custom_offre->nom, 'prix' =>$custom_offre->prix
                ]
            );
        return $custom_offre;
    }

    function createCustom($modificateur, $quantity)
    {
        $c = new custom();
        $c->nom = $modificateur["nom"];
        $c->prix = $modificateur["prix"] * $quantity;
        $c->created_at = Carbon::now();
        // $custom = custom::create($c->toArray());
        $c->id = DB::table('customs')
            ->insertGetId(
                ['nom' => $c->nom, 'prix' => $c->prix, 'created_at' => $c->created_at]);
        return $c;
    }

    /**
     * Verif if the creation date in the DB is equal to Creation Date in the token of Command_id  by Id commande entred
     *
     * @param string $commande_id
     * @return \Illuminate\Http\Response
     */
    public function VerifCommande($commande_id)
    {

        $commande = Commande::where('commande_id', 'like', $commande_id)->first();
        if (!$commande) {
            return response(array(
                'message' => 'Commande Not Found',
            ), 404);
        }
        $chaine_date = (string)$commande->created_at;
        $chaine = ($commande->user_id + $commande->plat_id) * 357;
        $chaine_string = "id" . (string)$chaine . "/" . $chaine_date;
        if (!Hash::check($chaine_string, $commande->commande_id)) {
            return response(array(
                'message' => "don't match",
            ), 404);
        }
        return response(array(
            'message' => "accept",
        ), 200);
    }


    public function get_Command_id($commande_id)
    {

    }
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $commande = Commande::find($id);
        if (!$commande) {
            return response(array(
                'message' => 'Commande Not Found',
            ), 404);
        }
        return $commande;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        DB::table('commandes')
            ->where('commande_id', $request->commande_id)
            ->update(['status' => $request->status]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Commande::destroy($id) == 0) {
            return response(array(
                'message' => 'Commande Not Found',
            ), 404);
        }
        return Commande::destroy($id);
    }

    /**
     * display all deleted commandes
     **/
    public function DisplayDeletedCommand()
    {
        return Commande::onlyTrashed()->get();
    }

    /**
     * display all command (+ deleted commandes)
     **/
    public function DisplayAllCommand()
    {
        return Commande::withTrashed()->get();
    }

}
