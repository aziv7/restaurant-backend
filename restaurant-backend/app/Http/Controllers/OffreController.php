<?php

namespace App\Http\Controllers;

use App\Models\offre;
use Illuminate\Http\Request;
use App\Models\Plat;

class OffreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
return Offre::with('plats','plats.modificateurs', 'plats.images', 'plats.modificateurs.ingredients')->get();
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
            'description' => 'required',
'prix'=>'required'
        ]);
        return Offre::create($request->all());    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Offre::find($id);
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
        $Offre = Offre::find($id);
        $Offre->update($request->all());
        return $Offre;    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Offre::destroy($id);
    }
    public function affectPlatToOffre($id_offre, $id_plat) {
        $offre =$this->show($id_offre);
        $plat = Plat::find($id_plat);
        $offre->plats()->attach($plat);
        return $offre;
    }
    public function DetachPlatFromOffre($id_offre, $id_plat) {
        $offre = Offre::find($id_offre);
        $plat = Plat::find($id_plat);
        $offre->plats()->detach($plat);
        return $offre;
    }
}
