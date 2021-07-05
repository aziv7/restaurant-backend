<?php

namespace App\Http\Controllers;

use App\Models\custom;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class customController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return custom::with('ingredients');
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

        ]);
        return Custom::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Custom::find($id);
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
        $Custom = Custom::find($id);
        $Custom->update($request->all());
        return $Custom;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Custom::destroy($id);
    }

    public function affectIngredientToModificateur($custom_id, $ingredient_id) {
        $custom = Custom::find($custom_id);
        $ingredient = Ingredient::find($ingredient_id);
        $custom->ingredients()->attach($ingredient);
    }
}
