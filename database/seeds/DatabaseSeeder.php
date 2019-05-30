<?php

use Illuminate\Database\Seeder;
use \App\library\LocalTiming;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(ScheduleTableSeeder::class);
        $arrWeekDays = [
        	['пн', '06:00', '22:00'],
        	['вт', '06:30', '02:00'],
        	['ср', '10:00', '22:00'],
        	['чт', '07:30', '22:00'],
        	['пт', '10:00', '24:00'],
        	['сб', '10:00', '22:00'],
        	['вс', '08:00', '22:00']
        ];
        foreach ($arrWeekDays as $day) {
        	App\WeekDay::firstOrCreate([
        		'name' 	   => $day[0],
        		'time_beg' => $day[1],
        		'time_end' => $day[2],
                'stamp_beg'=> LocalTiming::stamp($day[1]),
                'stamp_end'=> LocalTiming::stamp($day[2])
        	]);
        }
        $arrSpecDays = [
        	['2019-05-30', '10:00', '24:00'],
        	['2019-06-02', '11:00', '02:00'],
        	['2019-06-05', '16:00', '22:00']
        ];
        $arrTableTypes = [
            [2, 'Маленький'],
            [4, 'Средний'],
            [8, 'Большой'],
            [16, 'Патитайм']
        ];
        foreach ($arrTableTypes as $type) {
            App\TableType::firstOrCreate([
                'size'  => $type[0],
                'name'  => $type[1]
            ]);
        }
        
    }
}
