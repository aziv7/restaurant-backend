<?php

namespace App\Http\Controllers;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class statisticsController extends Controller
{
    public function getTotalPricesPerMonthe($year)
    {
        $statut = 'annulee';
        return DB::select("SELECT SUM(prix_total) as CA, MONTH(created_at) as Month FROM `commandes` WHERE YEAR(created_at)= $year and status not LIKE 'annulee' GROUP BY MONTH(created_at)
");
    }

    public function getNbrOfPayedPlatsGroupByPlat()
    {
        return DB::select('SELECT requested_plats.nom,SUM(commande_requested_plats.quantity) as quantity, SUM(commandes.prix_total) as total_price from requested_plats join commande_requested_plats on requested_plats.id = commande_requested_plats.requested_plat_id JOIN commandes on commande_requested_plats.commande_id = commandes.commande_id GROUP BY requested_plats.nom;');
    }

    public function getNbrOfPayedPlatsGroupByPlatByMounth(Request $request)
    {
        $response = DB::select("SELECT requested_plats.nom,SUM(commande_requested_plats.quantity) as quantity, SUM(commandes.prix_total) as total_price from requested_plats join commande_requested_plats on requested_plats.id = commande_requested_plats.requested_plat_id JOIN commandes on commande_requested_plats.commande_id = commandes.commande_id WHERE commandes.created_at BETWEEN '$request->start' and '$request->end' GROUP BY requested_plats.nom");
        return $response;
    }

    public function getTotalCashByMonth($year)
    {
        return DB::select("SELECT SUM(prix_total) as CA, MONTH(created_at) as Month FROM `commandes` WHERE YEAR(created_at)= $year and status not LIKE 'annulee' AND paiement_modality IS  NULL GROUP BY MONTH(created_at)
");
    }

    public function nbrOfUserConnected()
    {
        return DB::select("SELECT COUNT(*) as connected_users FROM `users` WHERE is_connected = 1");
    }

    public function mostImportentClientBuyin()
    {
        $result = DB::select("SELECT COUNT(*) AS nbrCmd, user_id FROM commandes GROUP BY user_id ORDER BY nbrCmd DESC");
        $user_id = $result[0]->user_id;
        var_dump($user_id);
        $user = User::find($user_id);
        return $user;
    }

    public function userWithHistoric()
    {
        return User::with('Commandes', 'Commandes.custom_offres', 'Commandes.custom_offres.requested_plats',
            'Commandes.custom_offres.requested_plats.customs', 'Commandes.custom_offres.requested_plats.customs.ingredients',
            'Commandes.requested_plat', 'Commandes.requested_plat.customs', 'Commandes.requested_plat.customs.ingredients')
            ->get();
    }

    public function CAMensuel($debut, $fin)
    {
        $statut = 'annulee';
        return DB::select("SELECT SUM(prix_total) as CA FROM `commandes` WHERE created_at between '$debut' and '$fin' and status not LIKE 'annulee'
");
    }

    public function CAMAnnuel()
    {
        $statut = 'annulee';
        $year = Carbon::now()->year;
        return DB::select("SELECT SUM(prix_total) as CA FROM `commandes` WHERE YEAR(created_at) = $year and status not LIKE 'annulee'
");
    }
}
