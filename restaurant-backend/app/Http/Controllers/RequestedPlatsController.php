<?php

namespace App\Http\Controllers;

use App\Models\Modificateur;
use App\Models\RequestedPlat;
use Illuminate\Http\Request;

class RequestedPlatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RequestedPlat::with('customs', 'customs.ingredients')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prix' => 'required',
            'description' => 'required',
        ]);
        $requested_plat = RequestedPlat::create([
            'nom' => $request->get('nom'),
            'prix' => $request->get('prix'),
            'description' => $request->get('description'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return RequestedPlat::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $plat = RequestedPlat::find($request->get('id'));
        $plat->update($request->all());
        return $plat;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return RequestedPlat::destroy($id);
    }

    /** affecter un plat a un modificateur */
    public function addRequestedPlatToModificateur($id_plat, $id_modificateur)
    {
        $plat = RequestedPlat::find($id_plat);
        $modificateur = Modificateur::find($id_modificateur);
        $plat->modificateurs()->attach($modificateur);
        return $plat;
    }

    /** détacher un plat d'un modificateur */
    public function detachRequestedPlatFromModificateur($id_plat, $id_modificateur)
    {
        $plat = RequestedPlat::find($id_plat);
        $modificateur = Modificateur::find($id_modificateur);
        $plat->modificateurs()->detach($modificateur);
        return $plat;
    }
}
