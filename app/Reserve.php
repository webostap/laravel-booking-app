<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use \App\library\LocalTiming;

class Reserve extends Model
{

    public function table()
    {
        return $this->belongsTo('App\RestTable', 'table_id');
    }

    public function tableType()
    {
        return $this->belongsTo('App\TableType', 'table_size', 'size');
    }

    public static function Ask (array $arParams) {

        if (\App\RestTable::checkFree($arParams)) {

            $reserve = new Reserve;

            $reserve->name = $arParams['name'];
            $reserve->phone = $arParams['phone'];
            $reserve->table_size = intval($arParams['table_size']);
            $reserve->date = $arParams['date'];
            $reserve->stamp_beg = $arParams['stamp_beg'];
            $reserve->stamp_end = $arParams['stamp_end'];
            $reserve->time_beg = LocalTiming::stampToStr($arParams['stamp_beg']);
            $reserve->time_end = LocalTiming::stampToStr($arParams['stamp_end']);

            if ($reserve->save()) 
            	return $reserve->id;
        }
        return false;
    }
    public static function Confrim ($reserveId) {

    	$reserve = Reserve::find($reserveId);

    	$arParams = [
	        'table_size'=> $reserve->table_size,
	        'date'      => $reserve->date,
	        'stamp_beg' => $reserve->stamp_beg,
	        'stamp_end' => $reserve->stamp_end
        ];

    	$freeTables = \App\RestTable::getFree($arParams);

        if ($freeTables) {

            $reserve->table_id = $freeTables[array_rand($freeTables)];
            $reserve->confrimed = 1;

            return $reserve->save();
        }
        else return false;
    }
}
