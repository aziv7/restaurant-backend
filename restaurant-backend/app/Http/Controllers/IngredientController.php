<?php

namespace App\Http\Controllers;
use App\Models\Ingredient;
use App\Models\Modificateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngredientController extends Controller
{
    public function index() {
        return DB::select('select * from ingredients where statut = true');
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
       $ingredient=  DB::select('select * from ingredients where statut = true and id = ?', [$id]);
         return $ingredient->plats();
    }


    public function show($id)
    {
        return DB::select('select * from ingredients where statut = true and id = ? or nom like ?', [$id, $id]);
    }

    public function showall()
    {
        return Ingredient::all();
    }

    public function update(Request $request)
    {
        $ingredient = Ingredient::where('id' == $request->id)->get();
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
        return  Ingredient::where('nom', 'like', '%'.$nom.'%', '&&', 'statut', '==', 'true')->get();
    }

    public function changeStatus(Request $request)
    {
    DB::UPDATE('UPDATE `ingredients` SET `statut` = ? WHERE `ingredients`.`id` = ?', [$request->statut, $request->id]);
    return Ingredient::find($request->id);
    }
}
