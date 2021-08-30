<?php

namespace App\Http\Controllers;

use App\Models\Plat;
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

    public function getNbrOfPayedPlatsGroupByPlat(){
        $names = $orders = DB::table('plats')
            ->select('nom')
            ->groupBy('nom')
            ->get();
        $unique_names = $names->unique('nom');
       foreach ($unique_names as $name) {
           var_dump($name->nom);

       }
        return $unique_names;
    }
}
