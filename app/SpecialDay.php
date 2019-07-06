<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\library\LocalTiming;

class SpecialDay extends Model
{
    public $timestamps = false;

    public function save (array $options = Array()) {

        $this->time_beg = LocalTiming::stampToStr($this->stamp_beg);
        $this->time_end = LocalTiming::stampToStr($this->stamp_end);

        return parent::save($options);

    }
}
