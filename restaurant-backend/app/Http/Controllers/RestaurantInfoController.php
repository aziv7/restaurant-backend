<?php

namespace App\Http\Controllers;

use App\Models\RestaurantInfo;
use App\Models\WorkTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestaurantInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RestaurantInfo::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return RestaurantInfo::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return RestaurantInfo::find($id);
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
        $restaurant = RestaurantInfo::find($request->id);
        return $restaurant->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return RestaurantInfo::destroy($id);
    }

    public function affectWorkTime($idWorkTime, $idRestaurantInfo) {
        $worktime = WorkTime::find($idWorkTime);
        $restaurantinfo = RestaurantInfo::find($idRestaurantInfo);
        $restaurantinfo->work_times()->save($worktime);
        return $restaurantinfo;
    }

    public function detachWorkTime($idworktime)
    {
        DB::table('work_times')->where('id', $idworktime)->update(['restaurant_info_id' => null]);
    }
}
