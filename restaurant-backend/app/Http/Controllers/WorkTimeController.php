<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\User;
use App\Models\WorkTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return WorkTime::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return WorkTime::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $workTime = WorkTime::find($id);
        return $workTime;
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
        $time = WorkTime::find($request->id);
        if ($time) {
            $time->update($request->all());
            return $time;
        } else return response('WorkTime not found', 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response(WorkTime::destroy($id), 200);
    }

    public function deleteRestaurantInfoFromWorkTime() {

    }
public function Verif_Time_Work(){
  $access=false;
    $temps = DB::select('select * from work_times where restaurant_info_id = ?', [1]);
    foreach ($temps as $t) {
        if (Carbon::now()->between($t->start, $t->end) && $t->deleted_at==null) {
   $access=true;
        }}      foreach ($temps as $t) {
            if(Carbon::now() == $t->holiday)
$access=false;
    }
    return $access;
}
}
