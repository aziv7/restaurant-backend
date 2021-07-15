<?php

namespace App\Http\Controllers;
use App\Models\Ingredient;
use App\Models\Modificateur;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index() {
        return Ingredient::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'quantite' => 'required',
            'prix' => 'required'
        ]);
        return Ingredient::create($request->all());
    }

    public function showPlats($id)
    {
       $ingredient=  Ingredient::find($id);

         return $ingredient->plats();
    }


    public function show($id)
    {
        return Ingredient::find($id);
    }

    public function update(Request $request)
    {
        $ingredient = Ingredient::find($request->id);
        $ingredient->update($request->all());
        return $ingredient;
    }
//affecter un ingredient à un modificateur
    public function addIngredientToModificateur(Request $request, $id_ingredient,$id_modificateur)
    {
        $ingredient = Ingredient::find($id_ingredient);

        $modificateur = Modificateur::find($id_modificateur);
        return $modificateur->ingredients()->save($ingredient);
    }


    public function destroy($id)
    {
        return Ingredient::destroy($id);
    }

    /**
     * Search for a name
     *
     * @param  string  $nom
     * @return \Illuminate\Http\Response
     */
    public function search($nom)
    {
        return Ingredient::where('nom', 'like', '%'.$nom.'%')->get();
    }
}
