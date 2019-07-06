<?php

namespace App\Http\Controllers\Edit;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Validator;
use \App\SpecialDay;
use \App\library\LocalTiming;

class EditSpecialDayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {

        if (!$request->date)
            return redirect()->back()->withErrors('Поле дата обязательно!');
        if ($request->stamp_beg >= $request->stamp_end)
            return redirect()->back()->withErrors('Конец дня должен быть позднее его начала!');


        $specialDay = new SpecialDay;

        $specialDay->date = ($request->date);
        $specialDay->stamp_beg = intval($request->stamp_beg);
        $specialDay->stamp_end = intval($request->stamp_end);
        $specialDay->day_off = $request->day_off ? 1 : NULL;

        $saved = $specialDay->save();
        if($saved) return redirect()->back();
        else return redirect()->back()->withErrors('Запись не сохранена! Обратитесь к администратору');
    }

    public function update(Request $request, $id)
    {   
        $v = Validator::make($request->all(), [
            'date'       => 'required'
        ]);

        if ($v->fails()) return redirect()->back()->withErrors($v->errors());

        if ($request->stamp_beg >= $request->stamp_end)
            return redirect()->back()->withErrors('Конец дня должен быть позднее его начала!');


        $specialDay = SpecialDay::find($id);

        $specialDay->date = ($request->date);
        $specialDay->stamp_beg = intval($request->stamp_beg);
        $specialDay->stamp_end = intval($request->stamp_end);
        $specialDay->day_off = $request->day_off ? 1 : NULL;

        $saved = $specialDay->save();
        if($saved) return redirect()->back();
        else return redirect()->back()->withErrors('Запись не сохранена! Обратитесь к администратору');

    }

    public function destroy ($id) {
        $deleted = SpecialDay::destroy($id);
        if ($deleted) return redirect()->back();
        else return redirect()->back()->withErrors('Дата не удалена! Обратитесь к администратору');
    }
}
