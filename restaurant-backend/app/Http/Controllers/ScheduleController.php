<?php

namespace App\Http\Controllers;

use App\Models\schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return schedule::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*DB::insert('insert into schedules (day,  start, end, restaurant_info_id) values (?,?,?,?)',
            [$request->day, $request->start, $request->end, $request->restaurant_info_id]);*/
        $id = schedule::insertGetId([
            'day' => $request->day,
            'start'=> $request->start,
            'end'=> $request->end,
            'restaurant_info_id' => $request->restaurant_info_id
        ]);
        return schedule::find($id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return schedule::find($id);
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
        $time = schedule::find($request->id);
        return $time->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return schedule::destroy($id);
    }


}
