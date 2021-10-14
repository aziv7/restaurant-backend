<?php

namespace App\Http\Controllers;


use App\Models\CodeReduction;
use App\Models\Commande;
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
        return DB::select("SELECT COUNT(*) AS nbrCmd, user_id, users.* FROM commandes JOIN users ON commandes.user_id = users.id GROUP BY user_id ORDER BY nbrCmd DESC, users.is_connected, users.prenom  LIMIT 10;");
    }

    public function userWithHistoric()
    {
        $users = User::with('Commandes', 'Commandes.custom_offres', 'Commandes.custom_offres.requested_plats',
            'Commandes.custom_offres.requested_plats.customs', 'Commandes.custom_offres.requested_plats.customs.ingredients',
            'Commandes.requested_plat', 'Commandes.requested_plat.customs', 'Commandes.requested_plat.customs.ingredients')
            ->orderByDesc('is_connected')
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();
        /*foreach ($users as $u => $user)
        {
            var_dump($user);
            foreach ($user['commandes'] as $c => $cmd)
            {
                $id = CommandeController::get_Command_id($cmd->commande_id);
                $cmd->id = $id;
            }
        }*/
        return $users;
    }

    public function CAMensuel($debut, $fin)
    {
        $statut = 'annulee';
        return DB::select("SELECT SUM(prix_total) as CA FROM `commandes` WHERE created_at between '$debut' and '$fin' and status not LIKE 'annulee'
");
    }

    public function CAMAnnuel($year)
    {
        $statut = 'annulee';
        return DB::select("SELECT SUM(prix_total) as CA FROM `commandes` WHERE YEAR(created_at) = $year and status not LIKE 'annulee'
");
    }

    public function CountactiveCodes()
    {
        $date = Carbon::now();
        return CodeReduction::where([
            ['date_expiration', '<', $date],
            ['statut', '=', true],
        ])->count();
    }

    public function CAOffreByMonthOfYear($year)
    {
        // calcul chiffre d'affaire offre avec reduction
        return DB::select("SELECT SUM((custom_offres.prix -(custom_offres.prix * code_reductions.taux_reduction) / 100) * commandes_custom_offres.quantity) as ca, offres.nom as offre FROM `commandes` JOIN commandes_custom_offres on commandes.commande_id = commandes_custom_offres.command_id JOIN custom_offres on commandes_custom_offres.custom_offre_id = custom_offres.id JOIN code_reductions ON code_reductions.id = commandes.code_reduction_id JOIN offres ON offres.nom = custom_offres.nom where commandes.status != 'annulee' AND YEAR(commandes.created_at) = $year GROUP BY offres.id;");
    }

    public function QuantityOffreByYear($year)
    {
        return DB::select("SELECT offres.nom as offre, SUM(commandes_custom_offres.quantity) as quantity FROM commandes JOIN commandes_custom_offres ON commandes.commande_id = commandes_custom_offres.command_id JOIN custom_offres ON commandes_custom_offres.custom_offre_id = custom_offres.id JOIN offres ON offres.nom = custom_offres.nom WHERE YEAR(commandes.created_at) = $year AND STATUS NOT LIKE 'annulee' GROUP BY offres.id;");
    }

    public function nbrusecoderedbyyear($year)
    {
        return DB::select("SELECT code_reductions.code as code, COUNT(*) as nbruse FROM commandes JOIN code_reductions ON code_reductions.id = commandes.code_reduction_id WHERE commandes.status NOT LIKE 'annulee' AND YEAR(commandes.created_at) = $year AND code_reduction_id != 0 GROUP BY code_reductions.id;");
    }

    public  function nbrusecoderedbyyearbyuser($year)
    {
        return DB::select("SELECT users.nom, users.prenom, users.email , COUNT(*) as nbruse, code_reductions.code FROM `commandes` JOIN users ON commandes.user_id = users.id JOIN code_reductions ON code_reductions.id = commandes.code_reduction_id WHERE code_reduction_id != 0 AND status NOT LIKE 'annulee' and YEAR(commandes.created_at) = $year GROUP BY users.id, code_reductions.id;");
    }
}
