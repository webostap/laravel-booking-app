<?

namespace App\library;

class LocalTiming
{	
	const ZEROTIME = 6;

    public static function stamp ($strTime) {
		$stamp = intval($strTime) - self::ZEROTIME;
		if ($stamp < 0) $stamp+= 24;
		$stamp*=2;
        $minutes = intval(substr($strTime,-2));
        if ($minutes > 30) $stamp++;
        if ($minutes) $stamp++;
		return $stamp;
	}

    public static function stampToStr ($stamp) {
    	$stamp = (int)$stamp;
    	$minunes = $stamp%2 ? '30' : '00';
    	$hour = intdiv ( $stamp , 2 ) + self::ZEROTIME;
		if ($hour >= 24) $hour-= 24;

		return sprintf("%'.02d:%'.02d", $hour, $minunes);
	}

	public static function getDateStamps($date)
	{
		date_default_timezone_set('Asia/Vladivostok');
        $today = false;
        $curr_time = date('H:i');
        $null_time = date('H:i', strtotime(SELF::stampToStr(0)));

        if ($curr_time >= $null_time && date('Y-m-d') == $date) {
            $curr_stamp = SELF::stamp($curr_time);
            $today = true;
        }

		$ret = [];

		$specialDay = \App\SpecialDay::where('date', $date)->first();
        if ($specialDay) {

            if ($specialDay->day_off)
                return false;

            else {
            	$ret[0] = $specialDay->stamp_beg;
            	$ret[1] = $specialDay->stamp_end;
            }
        }
        else {
            $weekDay = \App\WeekDay::getDayByDate($date);

            if ($weekDay->day_off)
                return false;

            else {
            	$ret[0] = $weekDay->stamp_beg;
            	$ret[1] = $weekDay->stamp_end;
            }
        }
        if ($today) {
            $ret[0] = $curr_stamp > $ret[0] ? $curr_stamp : ret[0];
        }
        
        return $ret;
	}

	public static function checkDateStamps($date, $stamp_beg, $stamp_end)
	{	
		$dateStamps = SELF::getDateStamps($date);
		if (!$dateStamps) return false;
		return $stamp_beg >= $dateStamps[0] && $stamp_end <= $dateStamps[1];
	}

	public static function getDateClosedStamps($date, $table_size)
	{
        $tableType = \App\TableType::where('size', $table_size)->firstOrFail();

        $tables = $tableType->tables()->get(['id'])->pluck('id');

        $reserves = $tableType->reserves()
            ->where('confrimed', 1)
            ->whereDate('date', $date)
            ->get(['table_id', 'stamp_beg', 'stamp_end'])
            ->toArray();

        $reservedStamps = [];
        $crossedStamps = [];
        $closedStamps = [];

        if ($tables->count() == 1) {
        	foreach ($reserves as $tableReserve) {
	            for ($stamp = $tableReserve['stamp_beg']; $stamp < $tableReserve['stamp_end']; $stamp++) {
	                $reservedStamps[] = $stamp;
	            }
	        }
	        return $reservedStamps;
        }


        foreach ($reserves as $tableReserve) {
            for ($stamp = $tableReserve['stamp_beg']; $stamp < $tableReserve['stamp_end']; $stamp++) {

                if ( in_array($stamp, $reservedStamps) ) {
                    if ( isset($crossedStamps[$stamp]) ) {
                        $crossedStamps[$stamp]++;
                    }
                    else $crossedStamps[$stamp] = 2;
                }
                else $reservedStamps[] = $stamp;

            }
        }

        foreach ($crossedStamps as $stamp => $count) {
            if ($count == $tables->count()) {
                $closedStamps[] = $stamp;
            }
        }

        return $closedStamps;
	}
	public static function getDateOpenStamps($table_size, $duration, $date)
	{	

        $dateStamps = SELF::getDateStamps($date);
        if(!$dateStamps) return [];
        $closedStamps = SELF::getDateClosedStamps($date, $table_size);
        $openStamps = [];


        $duration = intval($duration);

        for ($stamp = $dateStamps[0], $i; $stamp + $duration <= $dateStamps[1]; $stamp++) 
        {
        	for ($i=$stamp; $i < $stamp+$duration; $i++)
        		if (in_array(($i), $closedStamps)) break;

        	if ($i == $stamp+$duration) $openStamps[] = $stamp;
        }


        return $openStamps;

	}

	public static function getNearDateOpenStamps($table_size, $duration, $start_date = '')
	{	
		date_default_timezone_set('Asia/Vladivostok');
		$date = date('Y-m-d');

		if ($start_date) $date = $start_date > $date ? $start_date : $date;

        $openStamps = [];

		for ($i=1; $i < 180; $i++) { 
			$openStamps = SELF::getDateOpenStamps($table_size, $duration, $date);
			if (!empty($openStamps)) return [
	        	'date'=> $date,
	        	'stamps' => $openStamps
	        ];

	        if ($i==1)
	        {
	        	if ($start_date) {
	        		$date = date('Y-m-d');
	        		continue;
	        	}
	        }

        	$date = date('Y-m-d', strtotime($date. ' + 1 days'));
		}

        return false; //Тут может быть предупреждение, что в ближайшие пол года мест нет

	}


}

