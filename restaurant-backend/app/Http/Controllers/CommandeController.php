<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commandes = Commande::all();

        if ($commandes->isEmpty()) {
            return response(array(
                'message' => ' Not Found',
            ), 404);
        }
        return $commandes;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'user_id' => 'required',
            'plat_id' => 'required',
            'quantite' => 'required|gt:0',
            'prix' => 'gt:0'
        ]);
        return Commande::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $commande = Commande::find($id);
        if (!$commande) {
            return response(array(
                'message' => 'Commande Not Found',
            ), 404);
        }
        return $commande;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $commande = Commande::find($id);
        if (!$commande) {
            return response(array(
                'message' => 'Commande Not Found',
            ), 404);
        }
        $commande->update($request->all());
        return $commande;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Commande::destroy($id) == 0) {
            return response(array(
                'message' => 'Commande Not Found',
            ), 404);
        }
        return Commande::destroy($id);
    }

}
