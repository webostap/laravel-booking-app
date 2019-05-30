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
    	$SpecialDays = \App\SpecialDay::where('date', '>', date("Y-m-d"));

    	$freeDays = $weekDays->where('day_off', 1)->pluck('id');
    	$freeDates = $SpecialDays->where('day_off', 1)->pluck('date');

    	$timeInterval = [
    		min($weekDays->where('day_off', NULL)->min('stamp_beg'), $weekDays->where('day_off', NULL)->min('stamp_beg')),
    		max($weekDays->where('day_off', NULL)->max('stamp_end'), $weekDays->where('day_off', NULL)->max('stamp_end'))

    	];
    	
    	$formDate = [
    		'tableTypes' => $tableTypes,
    		'freeDays' => $freeDays,
    		'freeDates' => $freeDates,
    		'timeInterval' => $timeInterval
    	];

		return view('form', compact('formDate'));
    }
}
