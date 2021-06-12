<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Plat;
use Illuminate\Http\Request;

class PlatController extends Controller
{
    public function index() {
        return Plat::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prix' => 'required',
            'description' => 'required'
        ]);
        return Plat::create($request->all());
    }

    public function show($id)
    {
        return Plat::find($id);
    }

    public function update(Request $request, $id)
    {
        $plat = Plat::find($id);
        $plat->update($request->all());
        return $plat;
    }

    public function destroy($id)
    {
        return Plat::destroy($id);
    }

    /**
     * Search for a name
     *
     * @param  string  $nom
     * @return \Illuminate\Http\Response
     */
    public function search($nom)
    {
        return Plat::where('nom', 'like', '%'.$nom.'%')->get();
    }

    /**
     *affectation commande plat
     */
    public function addCommande($id_commande,$id_plat) {
        $plat =Plat::find($id_plat);
        $commande=Commande::find($id_commande);
        $plat->Commandes()->save($commande);
        return $plat;
    }

}
