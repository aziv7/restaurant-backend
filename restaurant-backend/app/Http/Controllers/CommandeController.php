<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Ingredient;
use App\Models\Modificateur;
use App\Models\Plat;
use App\Models\User;
use Carbon\Carbon;
use Faker\Core\Number;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use MongoDB\BSON\Timestamp;
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
        $commandes = Commande::with('plat','user')->get();
        foreach ($commandes as $i => $cmd) {
            foreach ($cmd->plat as $j => $plat) {
                $p =Plat::with('modificateurs')
                    ->where('id','like',$plat->id)
                    ->first();
                $plat = $p;
                $commandes[$i]->plat[$j]= $p;
                foreach ($plat->modificateurs as $k => $modif) {
                    $m = Modificateur::with('ingredients')
                        ->where('id', 'like',$modif->id)
                        ->first();
                    $commandes[$i]->plat[$j]->modificateurs[$k] = $m;
                }
            }
        }
       /* $commandes = DB::table('commandes')
            ->join('commande_plats', 'commandes.commande_id', '=', 'commande_plats.commande_id')
            ->leftJoin('plats', 'commande_plats.plat_id', '=', 'plats.id')
            ->select('commandes.*','commande_plats.*','plats.*')
            ->get();*/

        //$commandes = DB::select('select * from commandes as C, commande_plats as CP, plats as P where C.commande_id = CP.commande_id and CP.plat_id = P.id group by C.commande_id');
        /*$plats = DB::table('commande_plats')->select('plat_id');
        $commandes = DB::table('commandes')
            ->joinSub($plats, 'commande_plats', function ($join) {
                $join->on('commande_plats.plat_id', '=', 'plats.id');
            })->get();*/
        /*$commandes = DB::table('commandes')
            ->leftjoin('commande_plats', 'commandes.commande_id', '=', 'commande_plats.commande_id')
            ->leftjoin('plats', 'commande_plats.plat_id', '=', 'plats.id')
            ->select('commandes.*', 'plats.*')->groupBy("commande_plats.commande_id")
            ->get();*/


        /*$commandes=DB::table('commandes','C')
            ->leftjoin('commande_plats as CP', function($join) {
                $join->on('C.commande_id', '=', 'CP.commande_id');
            })
            ->leftjoin('plats as P', function($join) {
                $join->on('CP.plat_id', '=', 'P.id');
            })
            ->groupBy('C.user_id')
            ->get();*/

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
        $commande->prix = $request->prix;
        $commande->created_at = Carbon::now();
        $creation_datetime_string = $commande->created_at->toDateTimeString();
        //je vais avoir une liste des id des plats choisits pour les affecter au commande
        $list_plat_id = $request->plats;
        //somme des plats id
        $somme_plat_id = 0;
        foreach ($list_plat_id as $plat_id) {
            $somme_plat_id = $somme_plat_id + (int)$plat_id;
        }
        //id de la commande = (userid+somme des platid)*357 puis un / puis la date de la création de commande
        $chaine = ($request->user_id + $somme_plat_id) * 357;
        $chaine_string = "id" . (string)$chaine . "/" . $creation_datetime_string;
        $commande->commande_id = Hash::make($chaine_string);

        //création de commande sans plats
        DB::insert('insert into commandes (commande_id, user_id,  created_at, date_paiement, date_traitement) values (?,?,?,?,?)', [$commande->commande_id, $commande->user_id, $commande->created_at, "2021-06-25", "1993-04-29"]);
        // affectation des plats au commande
        foreach ($list_plat_id as $plat_id) {
            //fetch plat by id of plat sending in request
            $plat = Plat::find($plat_id);
            //affecter le plat à la commande
            $commande->plat()->attach($plat);
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
    public function update(Request $request, $id)
    {
        $commande = Commande::find($id);
        if (!$commande) {
            return response(array(
                'message' => 'Commande Not Found',
            ), 404);
        }
        $commande->update($request->all());
        return $commande;
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
