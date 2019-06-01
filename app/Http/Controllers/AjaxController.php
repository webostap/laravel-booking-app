<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use \App\library\LocalTiming;

class AjaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function date (Request $request)
    {   
        if ($request->has('table_size') && $request->has('duration')) {
            return LocalTiming::getNearDateOpenStamps(
                $request->table_size, 
                $request->duration,
                $request->date
            );
        }
        return 'null';
    }
    public function time (Request $request) 
    {
        if ($request->has('table_size') && $request->has('duration') && $request->has('date')) {
            $dateOpenStamps = LocalTiming::getDateOpenStamps(
                $request->table_size, 
                $request->duration,
                $request->date
            );
            return !empty($dateOpenStamps) ? $dateOpenStamps : 'null';
        };
    }
    public function submit (Request $request) 
    {   

         $v = Validator::make($request->all(), [
            'name'       => 'required|max:50',
            'phone'      => 'required|size:17',
            'date'       => 'required',
            'table_size' => 'required',
            'duration'   => 'required',
            'stamp_beg'  => 'required'
        ]);

        if ($v->fails())
            return 3;

        $arParams = [
            'name'       => $request->name,
            'phone'      => $request->phone,
            'date'       => $request->date,
            'table_size' => (int)$request->table_size, 
            'duration'   => (int)$request->duration, 
            'stamp_beg'  => (int)$request->stamp_beg,
            'stamp_end'  => $request->stamp_beg + $request->duration
        ];

        return \App\Reserve::Ask($arParams) ? 1 : 2;
    }
}
