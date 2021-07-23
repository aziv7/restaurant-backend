<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Rating::all();
    }
    public function AffectRatingToUser(request $request)
    {
        $rating = $request->rate;
        $rating = Rating::where('user_id', 'like', $request->user_id)->where('plat_id', 'like', $request->plat_id)->get()->first();
        //var_dump($rating);
        if ($rating) {
            $editdata = array(
                'note' => $request->note
            );
            return  $rating->update($editdata);
        }
        $editdata = array(
            'note' => $request->note,
            'user_id' => $request->user_id,
            'plat_id' => $request->plat_id
        );
        return Rating::create($editdata);
    }






    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
