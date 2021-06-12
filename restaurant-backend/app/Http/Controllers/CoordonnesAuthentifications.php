<?php

namespace App\Http\Controllers;

use App\Models\CoordonneesAuthentification;
use Illuminate\Http\Request;

class CoordonnesAuthentifications extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CoordonneesAuthentification::all();
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
            'login' => 'required',
            'password' => 'required'
        ]);
        return CoordonneesAuthentification::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return CoordonneesAuthentification::find($id);
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
        $coordonneesAuthentification = CoordonneesAuthentification::find($id);
        $coordonneesAuthentification->update($request->all());
        return $coordonneesAuthentification;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return CoordonneesAuthentification::destroy($id);
    }
}
