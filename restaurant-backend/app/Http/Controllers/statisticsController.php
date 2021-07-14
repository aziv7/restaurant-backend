<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class statisticsController extends Controller
{
    public function getTotalPricesPerMontheCurrentYear() {

        return DB::select("SELECT SUM(prix_total) as CA, MONTH(created_at) as Month FROM `commandes` WHERE YEAR(created_at)= YEAR(SYSDATE()) GROUP BY MONTH(created_at)");
    }

    public function getTotalPricesPerMonthe($year) {

        return DB::select("SELECT SUM(prix_total) as CA, MONTH(created_at) as Month FROM `commandes` WHERE YEAR(created_at)= $year GROUP BY MONTH(created_at)");
    }
}
