<?php

namespace App\Http\Controllers\Show;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use \App\library\LocalTiming;
class ShowIndex extends Controller
{
    public function __invoke()
    {

        $tableTypes = \App\TableType::all();
        $weekDays = \App\WeekDay::all();
        $specialDaysQ = \App\SpecialDay::whereDate('date', '>', date("Y-m-d"))->get();

        $freeDates = $specialDaysQ->where('day_off', 1)->pluck('date');
        $freeDays = $weekDays->where('day_off', 1)->pluck('id');
        $specialDays = $specialDaysQ->pluck('date');
        
        $formDate = [
            'tableTypes' => $tableTypes,
            'freeDays' => $freeDays,
            'freeDates' => $freeDates,
            'specialDays' => $specialDays
        ];

        return view('form', compact('formDate'));
    }
}
