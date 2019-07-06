<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use \App\library\LocalTiming;

class Reserve extends Model
{

    protected $fillable = ['name','phone','table_size','date','stamp_beg','stamp_end'];

    public function __construct ($attributes = array())
    {
        parent::__construct($attributes);
        
        $this->time_beg = LocalTiming::stampToStr($this->stamp_beg);
        $this->time_end = LocalTiming::stampToStr($this->stamp_end);

    }


    public function Ask () 
    {
        return $this->CheckFree() ? $this->save() : false;
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

        return self::where($matches);//->get();
    }

    public function CheckTime() {
        return LocalTiming::checkDateStamps($this->date, $this->stamp_beg, $this->stamp_end);
    }

    public function CheckFree() {

        if ( !$this->CheckTime() ) return false;

        return $this->tableType->tables()->count() > $this->Crosses()->count();

    }


    public function FreeTables() {

        if ( !$this->CheckTime() ) return [];

        $allTables = $this->tableType->tables()->get(['id'])->pluck('id');

        $reservedTables = $this->Crosses()->get(['table_id'])->pluck('table_id');

        return $allTables->diff($reservedTables)->toArray();

    }


    public static function Confrim ($reserveId) {

    	$reserve = Reserve::find($reserveId);
        
    	$freeTables = $reserve->FreeTables();
        if (empty($freeTables)) return false;

        $reserve->table_id = $freeTables[array_rand($freeTables)];
        $reserve->confrimed = 1;

        return $reserve->save();
    }

}
