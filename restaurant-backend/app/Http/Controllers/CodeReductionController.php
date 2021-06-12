<?php

namespace App\Http\Controllers;

use App\Models\CodeReduction;
use App\Models\Commande;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CodeReductionController extends Controller
{

    public function index() {
        return CodeReduction::all();
    }


    public function addReduction($id_commande,$id_reduction) {
        $codered = CodeReduction::find($id_reduction);
        $commande=Commande::find($id_commande);
        $codered->Commandes()->save($commande);
        return $codered;
    }

    public function store(Request $request)
    {
        $request->validate([
           'code' => 'required',
            'taux_reduction'  => 'required',
            'statut' => 'required'

        ]);
        return CodeReduction::create($request->all());
    }

    public function show($id)
    {
        return CodeReduction::find($id);
    }




    public function update(Request $request, $id)
    {
        $categorie = CodeReduction::find($id);
        $categorie->update($request->all());
        return $categorie;
    }

    public function destroy($id)
    {
        return CodeReduction::destroy($id);
    }

    /**
     * Search by code
     **/
    public function searchByCode($code)
    {
        return CodeReduction::where('code', 'like', '%'.$code.'%')->get();
    }
    public function searchByCodeExact($code)
    {
        return CodeReduction::where('code', 'like',$code)->get();
    }
    /**
     * existance par date
     **/
    public function searchByDate($date)
    {
        Log::info('This is some useful information.');
        return CodeReduction::where('date_expiration', 'like',$date.'%')->get();
    }
    /**
     * verif l'existance du code
     **/
    public function VerifExistanceCode($code)
    { return !$this->searchByCodeExact($code)->isEmpty();

    }

    /**
     * get all the data with date > now
     **/
    public function getallVerifDate($date)
    {$code=CodeReduction::whereDate('date_expiration', '>', Carbon::now())->get();
        return $code;
    }

    /**
     * verifier une date si elle est expire ou nn
     **/
    public function VerifDateExpire($id)
    { $date=CodeReduction::find($id)->date_expiration;
        return $date> Carbon::now();

    }
    /**
     * entrer code => extraire date + verifier validite code reduction (code+ date)
     **/
    public function VerifCode($code)
    { $date=CodeReduction::where('code', 'like',$code)->pluck('date_expiration');
      /*  print_r(Carbon::now());        print_r($date);

        var_dump($date[0] > Carbon::now() );*/
        return $date[0] > Carbon::now() && $this->VerifExistanceCode($code);
    }
}
