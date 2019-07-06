<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\library\LocalTiming;

class WeekDay extends Model
{
    public $timestamps = false; 


    public function save (array $options = Array()) {

        $this->time_beg = LocalTiming::stampToStr($this->stamp_beg);
        $this->time_end = LocalTiming::stampToStr($this->stamp_end);

        return parent::save($options);

    }

    public static function getDayByDate($str)
    {
    	$dayOfWeekId = date('w', strtotime($str));
        $dayOfWeekId = $dayOfWeekId ? $dayOfWeekId : 7;
        return SELF::where('id', $dayOfWeekId)->first();
    }
    
}
