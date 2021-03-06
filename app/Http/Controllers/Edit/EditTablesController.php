<?php

namespace App\Http\Controllers\Edit;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use \App\RestTable;

class EditTablesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function store(Request $request)
    {   
        if(!$request->size) return redirect()->back()->withErrors('Столу нужен размер, милорд');

        $table = new RestTable;
        $table->size = $request->size;

        $saved = $table->save();
        if($saved) return redirect()->back();
        else return redirect()->back()->withErrors('Запись не сохранена! Обратитесь к администратору');

    }

    public function destroy($id)
    {
        $table = RestTable::find($id);

        $nowStamp = \App\library\LocalTiming::stamp(date('H:i'));

        $reserved_count = $table->reserves()
            ->whereDate('date', '>', date('Y-m-d'))
            ->orWhere([['date', date('Y-m-d')], ['stamp_beg', '>', $nowStamp]])
            ->count();

        if ($reserved_count) return redirect()->back()->withErrors('Данный стол имеет предстоящие брони');

        else {
            $deleted = $table->delete();
            if ($deleted) return redirect()->back();
            else return redirect()->back()->withErrors('Стол не удалён! Обратитесь к администратору');
        }
    }
}
