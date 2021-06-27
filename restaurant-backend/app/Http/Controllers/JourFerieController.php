<?php

namespace App\Http\Controllers;

use App\Models\jourFerie;
use Illuminate\Http\Request;

class JourFerieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $jour_fer =jourFerie::all();

        if ($jour_fer->isEmpty()) {
            return response(array(
                'message' => ' Not Found',
            ), 404);
        }
        return $jour_fer;      }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { $request->validate([
        'date_jour_fer_debut' => 'required',
        'date_jour_fer_fin' => 'required',
    ]);
        return jourFerie::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jour_fer = jourFerie::find($id);
        if (!$jour_fer) {
            return response(array(
                'message' => 'jour ferie Not Found',
            ), 404);
        }
        return $jour_fer;
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
        $jour_fer = jourFerie::find($id);
        $jour_fer->update($request->all());
        return $jour_fer;    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (jourFerie::destroy($id) == 0) {
            return response(array(
                'message' => 'jour ferie Not Found',
            ), 404);
        }
        return jourFerie::destroy($id);
    }
}
