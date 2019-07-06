<?php

namespace App\Http\Controllers\Edit;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use \App\WeekDay;

class EditWeekDayController extends Controller
{
	public function __construct()
	{
	    $this->middleware('auth');
	}
	
    public function update (Request $request, $id) {

    	if ($request->stamp_beg >= $request->stamp_end)
    		return redirect()->back()->withErrors('Конец дня должен быть позднее его начала!');

        $weekDay = WeekDay::find($id);

		$weekDay->stamp_beg = intval($request->stamp_beg);
		$weekDay->stamp_end = intval($request->stamp_end);
		$weekDay->day_off = $request->day_off ? 1 : NULL;

		$saved = $weekDay->save();

		if($saved) return redirect()->back();
		else return redirect()->back()->withErrors('Запись не сохранена! Обратитесь к администратору');

    }
}
