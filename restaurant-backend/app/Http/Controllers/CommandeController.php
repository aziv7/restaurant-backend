<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
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
        $commandes = Commande::all();

        if ($commandes->isEmpty()) {
            return response(array(
                'message' => ' Not Found',
            ), 404);
        }
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

        $request->validate([
            'user_id' => 'required',
            'plat_id' => 'required',
            'quantite' => 'required|gt:0',
            'prix' => 'gt:0'
        ]);

        $commande = new Command();
        $commande ->user_id = $request->user_id;
        $commande ->plat_id = $request->plat_id;
        $commande ->quantite = $request->quantite;
        $commande ->prix = $request->prix;
        $commande ->created_at = Carbon::now();
        $creation_datetime_string = $commande->created_at ->toDateTimeString();
        $chaine = ($request->user_id + $request->plat_id) * 357;
        $chaine_string = "id" . (string) $chaine . "/" . $creation_datetime_string;
        $commande->commande_id = Hash::make($chaine_string);
        DB::insert('insert into commandes (commande_id, user_id, plat_id, quantite, prix, created_at, date_paiement, date_traitement) values (?,?,?,?,?,?,?,?)', [$commande->commande_id, $commande ->user_id, $commande ->plat_id, $commande ->quantite, $commande ->prix, $commande ->created_at, "2021-06-25", "1993-04-29"]);
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
        return  Commande::withTrashed()->get();
    }

}
