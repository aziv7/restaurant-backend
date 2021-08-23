<?php

namespace App\Http\Controllers;

use App\Models\RestaurantInfo;
use App\Models\schedule;
use App\Models\User;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function getInfo()
    {
        return RestaurantInfo::all()->first();
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

    public function affectHoliday($idWorkTime, $idRestaurantInfo) {
        $worktime = Holiday::find($idWorkTime);
        $restaurantinfo = RestaurantInfo::find($idRestaurantInfo);
        $restaurantinfo->holidays()->save($worktime);
        return $restaurantinfo;
    }

    public function detachHoliday($idworktime)
    {
        DB::table('holidays')->where('id', $idworktime)->update(['restaurant_info_id' => null]);
    }

    public function affectTime($idschedule, $idRestaurantInfo) {
        $worktime = schedule::find($idschedule);
        $restaurantinfo = RestaurantInfo::find($idRestaurantInfo);
        $restaurantinfo->schedules()->save($worktime);
        return $restaurantinfo;
    }

    public function detachTime($idworktime)
    {
        DB::table('schedules')->where('id', $idworktime)->update(['restaurant_info_id' => null]);
    }

    public function user($user_id, $restau_id)
    {
        return DB::update('update restaurant_infos set user_id = ? where id = ?', [$user_id , $restau_id]);
    }

    public function detachUser($restau_id)
    {
        return DB::update('update restaurant_infos set user_id = ? where id = ?', [null , $restau_id]);
    }

    public function myRestau()
    {
        $user_id = Auth::id();
        $restau_info = DB::select('select * from restaurant_infos where user_id = ?', [$user_id]);
        return $restau_info;
    }
}
