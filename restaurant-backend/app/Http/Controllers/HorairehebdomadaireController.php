<?php

namespace App\Http\Controllers;

use App\Models\Horairehebdomadaire;
use Illuminate\Http\Request;

class HorairehebdomadaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $horaires = Horairehebdomadaire::all();

        if ($horaires->isEmpty()) {
            return response(array(
                'message' => ' Not Found',
            ), 404);
        }
        return $horaires;    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'jour' => 'required',
            'heure_debut' => 'required',
            'heure_fermeture' => 'required',
        ]);
        return Horairehebdomadaire::create($request->all());

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Horaire = Horairehebdomadaire::find($id);
        if (!$Horaire) {
            return response(array(
                'message' => 'Horaire Not Found',
            ), 404);
        }
        return $Horaire;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $horaire = Horairehebdomadaire::find($id);
        //var_dump($horaire->update($request->all()));
        $horaire->update($request->all());
        return $horaire;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Horairehebdomadaire::destroy($id) == 0) {
            return response(array(
                'message' => 'Horaire Not Found',
            ), 404);
        }
        return Horairehebdomadaire::destroy($id);
    }
}
