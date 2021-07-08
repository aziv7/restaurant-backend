<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Modificateur;
use Illuminate\Http\Request;

class ModificateurController extends Controller
{
    public function index()
    {
        return Modificateur::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prix' => 'required',

        ]);
        return Modificateur::create($request->all());
    }

    public function show($id)
    {
        return Modificateur::find($id);
    }

    public function update(Request $request, $id)
    {
        $modificateur = Modificateur::find($id);
        $modificateur->update($request->all());
        return $modificateur;
    }

    public function destroy($id)
    {
        return Modificateur::destroy($id);
    }

    /**
     * Search for a name
     *
     * @param  string  $nom
     * @return \Illuminate\Http\Response
     */
    public function search($nom)
    {
        return Modificateur::where('nom', 'like', '%' . $nom . '%')->get();
    }

    public function affectIngredientToModificateur($modificateur_id, $ingredient_id) {
        $modificateur = Modificateur::find($modificateur_id);
        var_dump($modificateur->nom);
        $ingredient = Ingredient::find($ingredient_id);
        var_dump($ingredient->nom);
        $modificateur->ingredients()->attach($ingredient);
    }
}
