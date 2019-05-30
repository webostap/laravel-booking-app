<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\library\LocalTiming;

class RestTable extends Model
{

    public function type()
    {
        return $this->belongsTo('App\TableType', 'size', 'size');
    }

	public function reserves()
    {
        return $this->hasMany('App\Reserve', 'table_id');
    }

	public static function checkFree(array $arParams)
    {
    	if ( LocalTiming::checkDateStamps($arParams['date'], $arParams['stamp_beg'], $arParams['stamp_end'])) {

	        $tables = SELF::where('size', $arParams['table_size'])->count();

	    	$matches = [
	            ['confrimed', 1],
	            ['date', $arParams['date']],
	            ['table_size', $arParams['table_size']],
	            ['stamp_end', '>', $arParams['stamp_beg']],
	            ['stamp_beg', '<', $arParams['stamp_end']]
	        ];

	        $reserves = \App\Reserve::where($matches)->count();

	        return $tables > $reserves;

	    } else return false;
    }
	public static function getFree(array $arParams)
    {
    	if ( LocalTiming::checkDateStamps($arParams['date'], $arParams['stamp_beg'], $arParams['stamp_end'])) {

	        $tables = SELF::where('size', $arParams['table_size'])->get(['id'])->pluck('id');

	    	$matches = [
	            ['confrimed', 1],
	            ['date', $arParams['date']],
	            ['table_size', $arParams['table_size']],
	            ['stamp_end', '>', $arParams['stamp_beg']],
	            ['stamp_beg', '<', $arParams['stamp_end']]
	        ];

	        $reserves = \App\Reserve::where($matches)->
	        	get(['table_id'])->pluck('table_id');

	        $freeTables = $tables->diff($reserves)->flatten();

	        return $freeTables->count() ? $freeTables->toArray() : false;


	    } else return false;
    }
}
