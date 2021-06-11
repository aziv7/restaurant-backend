<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
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


    public function setIngredient(Request $request, $id)
    {
        $plat = Plat::find(1);
        
        $ingredient=Ingredient::find($id);

        
        $plat->ingredients()->attach($ingredient);
        return $plat;
    }

    public function getIngredient(Request $request, $id)
    {
        $plat = Plat::find($id);
        
      

        
        return $plat->ingredients;
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
}
