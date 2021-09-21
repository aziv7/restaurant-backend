<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use App\Models\RequestedPlat;
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
}
