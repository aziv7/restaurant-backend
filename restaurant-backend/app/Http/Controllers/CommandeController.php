<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\custom;
use App\Models\Ingredient;
use App\Models\Modificateur;
use App\Models\Plat;
use App\Models\User;
use App\Models\WorkTime;
use Carbon\Carbon;
use Faker\Core\Number;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
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
        $commandes = Commande::with('plat','user','plat.customs','plat.customs.ingredients')
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
        $commande = new Commande();
        $commande->user_id = Auth::id();
        $commande->created_at = Carbon::now();
        $creation_datetime_string = $commande->created_at->toDateTimeString();
        //somme des plats id
        $somme_plat_id = 0;
        // je vais avoir une liste des id des plats choisits pour les affecter au commande
        foreach ($request->cart as $plat) {
            $somme_plat_id = $somme_plat_id + $plat["id"];
        }
        //id de la commande = (userid+somme des platid)*357 puis un / puis la date de la création de commande
        $chaine = (Auth::id() + $somme_plat_id) * 357;
        $chaine_string = "id" . (string)$chaine . "/" . $creation_datetime_string;
        $id = Hash::make($chaine_string);
        $commande->commande_id = $id;
        $commande->prix_total =0;

        if ($request->checkout)
        {
            $commande->datepaiment = Carbon::now(); // $request->checkout["date_payment"];
        } else $commande->datepaiment = null;

        //création de commande sans plats
        DB::insert('insert into commandes (commande_id, user_id,  created_at, date_paiement, date_traitement) values (?,?,?,?,?)', [$commande->commande_id, $commande->user_id, $commande->created_at, $commande->datepaiment, null]);
        $custom = new custom();
        // affectation des plats sans modificateur au commande
        foreach ($request->cart as $i=> $plat) {
            $commande->prix_total = $commande->prix_total + ($plat["prix"]*$plat["quantity"]);
            $p = Plat::find($plat["id"]);
            //affecter le plat à la commande
            $commande->plat()->attach($p);
            //parcourir les plats pour traiter les customs
            foreach ($plat["modificateurs"] as $modificateur) {
                $custom->nom = $modificateur["nom"];
                $custom->prix = $modificateur["prix"];
                //insertion du custom dans la base
                $custom= custom::create($modificateur);
                //affectation du custm au plat
                $plat1 = Plat::find($plat['id']);
                $plat1->customs()->attach($custom);
                $commande->prix_total = $commande->prix_total + $modificateur["prix"];
                //parcourir les modificateurs pour traiter les ingrédients
                foreach ($modificateur["ingredients"] as $ingredient) {
                    $ing = Ingredient::find($ingredient["id"]);
                    //affecter ingrédient à son custom
                    $custom->ingredients()->attach($ing);
                    $commande->prix_total = $commande->prix_total + $ingredient["prix"];
                }
            }
            $priceStripe = $request->checkout["amount"];
            if ($commande->prix_total == $priceStripe) {
                //inserer le prix total dans la db
                DB::update('update commandes set prix_total = ? where commande_id = ?', [$commande->prix_total , $id]);
            } else {
                DB::table("commandes")->delete($id);
                return response(array(
                    'message' => 'disordance de prix',
                ), 403);
            }
        }
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
