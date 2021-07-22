<?php

namespace App\Http\Controllers;

use App\Models\CodeReduction;
use App\Models\Commande;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CodeReductionController extends Controller
{

    public function index()
    {
        $codered = CodeReduction::all();

        if ($codered->isEmpty()) {
            return response(array(
                'message' => ' Not Found',
            ), 404);
        }
        return $codered;
    }


    public function addReduction($id_commande, $id_reduction)
    {
        $codered = CodeReduction::find($id_reduction);
        $commande = Commande::find($id_commande);
        if (!$codered || !$commande) {
            return response(array(
                'message' => 'Code Reduction Not Found',
            ), 404);
        }
        $codered->Commandes()->save($commande);
        return $codered;
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'taux_reduction' => 'required|gt:0',
            'statut' => 'required'

        ]);
        return CodeReduction::create($request->all());
    }

    public function show($id)
    {
        $code = CodeReduction::find($id);
        if (!$code) {
            return response(array(
                'message' => 'Code Reduction Not Found',
            ), 404);
        }
        return $code;
    }


    public function update(Request $request, $id)
    {
        $code = CodeReduction::find($id);
        if (!$code) {
            return response(array(
                'message' => 'Code Reduction Not Found',
            ), 404);
        }
        $code->update($request->all());
        return $code;
    }

    public function destroy($id)
    {
        if (CodeReduction::destroy($id) == 0) {
            return response(array(
                'message' => 'Code Reduction Not Found',
            ), 404);
        }
        return CodeReduction::destroy($id);
    }

    /**
     * Search by code
     **/
    public function searchByCode($code)
    {
        return CodeReduction::where('code', 'like', '%' . $code . '%')->get();
    }

    public function searchByCodeExact($code)
    {
        return CodeReduction::where('code', 'like', $code)->get()->first();
    }

    /**
     * existance par date
     **/
    public function searchByDate($date)
    {
        return CodeReduction::where('date_expiration', 'like', $date . '%')->get();
    }

    /**
     * verif l'existance du code
     **/
    public function VerifExistanceCode($code)
    {
        return $this->searchByCodeExact($code)!=null;

    }

    /**
     * get all the data with date > now
     **/
    public function getallVerifDate()
    {
        $code = CodeReduction::whereDate('date_expiration', '>', Carbon::now())->get();
        return $code;
    }

    /**
     * verifier une date si elle est expire ou nn
     **/
    public function VerifDateExpire($id)
    {
        $date = CodeReduction::find($id)->date_expiration;
        return $date > Carbon::now();
    }

    /**
     * entrer code => extraire date + verifier validite code reduction (code+ date)
     **/
    /**
     * verifier $codered->user_id si null c'est pour tt le monde sinn id user comparer avec id connecte
     **/

    public function VerifCode($code)
    {$codered=$this->searchByCodeExact($code);
        if (!$codered) {
            return response(array(
                'message' => 'Code Reduction Not Found',
            ), 404);
        }
        if ($codered->user_id == null || $codered->user_id == Auth::id()) {
            $date = CodeReduction::where('code', 'like', $code)->pluck('date_expiration')->first();
            if ($date < Carbon::now() && $date!= null) { //date expiration <Now OU date expiration != null (a un delai)=> erreur
                return response(array(
                    'message' => 'Code Reduction invalid',
                ), 406);
            }//var_dump('true');
            return true;
        }
        return response(array(
            'message' => 'Code Reduction Not Found',
        ), 404);
    }


    /**
     * Reduction prix  if valid
     **/

    public function Reduction($code,$prix){
        $codered=$this->searchByCodeExact($code);
        if (!$codered) {
            return response(array(
                'message' => 'Code Reduction Not Found',
            ), 404);
        }
        if ($codered->user_id == null || $codered->user_id == Auth::id()) {
            $date = CodeReduction::where('code', 'like', $code)->pluck('date_expiration')->first();
            if ($date < Carbon::now() && $date!= null) { //date expiration <Now OU date expiration != null (a un delai)=> erreur
                return response(array(
                    'message' => 'Code Reduction invalid',
                ), 406);
            }//var_dump('true');
            $codered=$this->searchByCodeExact($code);
            $prixreduit=($prix*$codered->taux_reduction)/100;//var_dump($prixreduit);
            $prixfinal=$prix-$prixreduit;
          $response = [
            'prix'=> $prixfinal ,
            'id'=> $codered->id
        ];
        return response($response, 201);
        }
       
        return response(array(
            'message' => 'Code Reduction Not Found',
        ), 404);
        
            }
    /**
     * display all deleted codes
     **/
    public function DisplayDeletedCode()
    {

        return CodeReduction::onlyTrashed()->get();
    }

    /**
     * display all code (+ deleted codes)
     **/
    public function DisplayAllCodes()
    {
        return CodeReduction::withTrashed()->get();
    }

    /**
     * affecter un code de reduction à un id user
     **/
    public function AffecterUserReduction($id_reduction, $id_user)
    {
        $codered = CodeReduction::find($id_reduction);
        $user = User::find($id_user);
        if($codered->user_id!=null)
        CodeReduction::destroy($codered->id);
        if (!$codered) {
            return response(array(
                'message' => 'Code Reduction Not Found',
            ), 404);
        }
        if (!$user) {
            return response(array(
                'message' => ' user Not Found',
            ), 404);
        }
        $codered->user_id = $id_user;
        return $codered;
    }
    
}
