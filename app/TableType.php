<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TableType extends Model
{
    public $timestamps = false;

    public function tables()
    {
        return $this->hasMany('App\RestTable', 'size', 'size');
    }
    public function reserves()
    {
        return $this->hasMany('App\Reserve', 'table_size', 'size');
    }
}
