<?php

namespace App\Http\Controllers\Show;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ShowEdit extends Controller
{
    public function __invoke()
    {
    	$weekDays = \App\WeekDay::all();
    	$specialDays = \App\SpecialDay::all();
    	$tables = \App\RestTable::all();
    	$tableTypes = \App\TableType::all();
    	return view('edit', compact(
    		'weekDays', 'specialDays', 'tables', 'tableTypes'
    	));

    }
}
