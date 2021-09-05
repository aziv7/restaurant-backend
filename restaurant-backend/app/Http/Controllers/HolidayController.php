<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\User;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Holiday::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Holiday::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $workTime = Holiday::find($id);
        return $workTime;
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
        $time = Holiday::find($request->id);
        if ($time) {
            $time->update($request->all());
            return $time;
        } else return response('Holiday not found', 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response(Holiday::destroy($id), 200);
    }

    public function deleteRestaurantInfoFromWorkTime()
    {

    }

    public static function Verif_Time_Work()
    {
        $access = false;
        $temps = DB::select('select * from schedules where restaurant_info_id = ?', [1]);
        $holidays = DB::select('select * from holidays where restaurant_info_id = ?', [1]);
        $now = Carbon::now();
        // var_dump((int)$now->format('H'));
        $jour = $now->format('D');
        $heure = $now->format('H');
        $minute = $now->format('m');
        $day = '';
        switch ($jour) {
            case 'Mon':
                $day = 'monday';
                break;
            case 'Tue':
                $day = 'tuesday';
                break;
            case 'Wed':
                $day = 'wednesday';
                break;
            case 'Thu':
                $day = 'thursday';
                break;
            case 'Fri':
                $day = 'friday';
                break;
            case 'Sat':
                $day = 'saturday';
                break;
            case 'Sun':
                $day = 'sunday';
                break;
        }
        foreach ($temps as $t) {
            $debutH = (int)substr($t->start, 0, 2);
            $finH = (int)substr($t->end, 0, 2);
            $debutM = (int)substr($t->start, 3, 2);
            $finM = (int)substr($t->end, 3, 2);
            if ($day == $t->day && $debutH <= $heure && $finH >= $heure && $debutM <= $minute && $finM >= $minute)
            {
                $access = true;
            }
        }
        foreach ($holidays as $h) {
            if (Carbon::now()->isoFormat('Y-MM-DD') == substr($h->holiday, 0, 10))
                $access = false;
        }
        return $access;
    }
}
