<?php

namespace App\Http\Controllers;

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

    public function getIngredients($id)
    {
        $modificateur = Modificateur::find($id);




        return $modificateur->ingredients;
    }




    public function getPlats(Request $request, $id)
    {
        $modificateur = Modificateur::find($id);




        return $modificateur->plats;
    }
    public function getSupplements($id)
    {
        return Modificateur::find($id)->supplements;
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
}
