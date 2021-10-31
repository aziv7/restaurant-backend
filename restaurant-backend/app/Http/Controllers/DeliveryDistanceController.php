<?php

namespace App\Http\Controllers;

use App\Models\DeliveryDistance;
use App\Models\RestaurantInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DeliveryDistanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DeliveryDistance::all();
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
            'distance' => 'required',
            'price' => 'required'
        ]);
        return DeliveryDistance::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return DeliveryDistance::find($id);
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
        $deliveryDistance = DeliveryDistance::find($id);
        if ($deliveryDistance != null) {
            $deliveryDistance->update($request->all());
            return $deliveryDistance;
        } else return response(array(
            'message' => ' deliveryDistanenotfound',
        ), 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DeliveryDistance::destroy($id);
    }

    public function attachRestaurant($idrestau, $idDeliveryDistance)
    {
        return DB::update("UPDATE `delivery_distances` SET `restaurant_info_id` = ? WHERE `delivery_distances`.`id` = ?;", [$idrestau, $idDeliveryDistance]);
    }

    public function detachRestaurant($idrestau, $idDeliveryDistance)
    {
        return DB::update("UPDATE `delivery_distances` SET `restaurant_info_id` = null WHERE `delivery_distances`.`id` = ?;", [$idDeliveryDistance]);

    }

}
