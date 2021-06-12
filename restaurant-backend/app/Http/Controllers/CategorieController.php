<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Plat;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        return Categorie::all();
    }


    public function addPlat($id_categorie, $id_plat)
    {
        $categorie = Categorie::find($id_categorie);
        $plat = Plat::find($id_plat);
        $categorie->plats()->save($plat);
        return $categorie;
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',

        ]);
        return Categorie::create($request->all());
    }

    public function show($id)
    {
        return Categorie::find($id);
    }


    public function update(Request $request, $id)
    {
        $categorie = Categorie::find($id);
        $categorie->update($request->all());
        return $categorie;
    }

    public function destroy($id)
    {
        return Categorie::destroy($id);
    }

    /**
     * Search for a name
     *
     * @param string $nom
     * @return \Illuminate\Http\Response
     */
    public function search($nom)
    {
        return Categorie::where('nom', 'like', '%' . $nom . '%')->get();
    }
}
