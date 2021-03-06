<?php

namespace App\Http\Controllers;

use App\Models\RestaurantInfo;
use App\Models\schedule;
use App\Models\User;
use App\Models\Holiday;
use http\Env\Response;
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return RestaurantInfo::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return RestaurantInfo::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return RestaurantInfo::destroy($id);
    }

    public function affectHoliday($idWorkTime, $idRestaurantInfo)
    {
        $worktime = Holiday::find($idWorkTime);
        $restaurantinfo = RestaurantInfo::find($idRestaurantInfo);
        $restaurantinfo->holidays()->save($worktime);
        return $restaurantinfo;
    }

    public function detachHoliday($idworktime)
    {
        DB::table('holidays')->where('id', $idworktime)->update(['restaurant_info_id' => null]);
    }

    public function affectTime($idschedule, $idRestaurantInfo)
    {
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
        $user = User::find($user_id);
        $restau = RestaurantInfo::find($restau_id);
        $user->restaurant_infos()->attach($restau);
        return $restau;
    }

    public function detachUser($user_id, $restau_id)
    {
        $user = User::find($user_id);
        $restau = RestaurantInfo::find($restau_id);
        $user->restaurant_infos()->detach($restau);
        return $restau;
    }

    public function myRestau()
    {
        $user_id = Auth::id();
        $restau_info = DB::table('restaurant_infos')
            ->select("*")
            ->leftJoin("restaurant_info_user", 'restaurant_infos.id', '=', 'restaurant_info_user.restaurant_info_id')
            ->where('restaurant_info_user.user_id', '=', $user_id)
            ->get();
        return $restau_info;
    }

    public function frontRestuInfo()
    {
        $restaurant =  DB::table('restaurant_infos')
            ->select(['address', 'tel', 'nom_restaurant', 'num_siret',
                'num_tva_intercommunautaire', 'logo', 'prixlivraison', 'livraison',
                'emporter', 'sur_place', 'cash', 'carte_bancaire',
                'facebook', 'instagram', 'tik_tok', 'snapchat', 'androidApp',
                'iosApp', 'email'])
            ->first();
        return response(['restaurant' => $restaurant], 200);
    }

    public function getAllDeliveryDistance()
    {
        $restau_info_id = DB::table('restaurant_infos')
            ->select(['id'])
            ->first()->id;
        if ($restau_info_id == null) {
            return response(array(
                'message' => 'this user doesn\'t have a restaurant or problem mapping table restau info',
            ), 404);
        }

        $distances = DB::select("SELECT * FROM delivery_distances WHERE restaurant_info_id = $restau_info_id AND deleted_at is null ");
        if ($distances!= null) {
            return $distances;
        } else
            return response(array(
            'message' => 'this table doesn\'t have delivery distances or problem mapping between delivery_distances table and restaurant_info tablet',
        ), 404);
    }
}
