<?php

namespace App\Http\Controllers;

use App\Models\CodeReduction;
use App\Models\Commande;
use Illuminate\Http\Request;

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
}
