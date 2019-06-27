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

}
