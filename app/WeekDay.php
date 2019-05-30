<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeekDay extends Model
{
    public $timestamps = false; 

    public static function getDayByDate($str)
    {
    	$dayOfWeekId = date('w', strtotime($str));
        $dayOfWeekId = $dayOfWeekId ? $dayOfWeekId : 7;
        return SELF::where('id', $dayOfWeekId)->first();
    }
    
}
