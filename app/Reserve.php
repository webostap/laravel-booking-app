<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use \App\library\LocalTiming;

class Reserve extends Model
{

    protected $fillable = ['name','phone','table_size','date','stamp_beg','stamp_end'];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->time_beg = LocalTiming::stampToStr($this->stamp_beg);
        $this->time_end = LocalTiming::stampToStr($this->stamp_end);
    }

    public function table()
    {
        return $this->belongsTo('App\RestTable', 'table_id');
    }

    public function tableType()
    {
        return $this->belongsTo('App\TableType', 'table_size', 'size');
    }


    public function Crosses() {
        $matches = [
            ['date', $this->date],
            ['table_size', $this->table_size],
            ['stamp_end', '>', $this->stamp_beg],
            ['stamp_beg', '<', $this->stamp_end]
        ];

        return self::where($matches)->get(['table_id'])->pluck('table_id');
    }

    public function AvailableTables() {
        if ( !LocalTiming::checkDateStamps($this->date, $this->stamp_beg, $this->stamp_end))
            return false;

        $tables = $this->tableType->tables()->get(['id'])->pluck('id');

        $freeTables = $tables->diff($this->Crosses())->flatten();

        return $freeTables->count() ? $freeTables->toArray() : false;
    }


    public static function Ask (array $arParams) {

        $reserve = new Reserve($arParams);

        if ($reserve->AvailableTables())
            if ($reserve->save()) 
                return $reserve->id;

        return false;
    }
    public static function Confrim ($reserveId) {

    	$reserve = Reserve::find($reserveId);
        
    	$freeTables = $reserve->AvailableTables();

        if ($freeTables) {

            $reserve->table_id = $freeTables[array_rand($freeTables)];
            $reserve->confrimed = 1;

            return $reserve->save();
        }
        else return false;
    }
}
