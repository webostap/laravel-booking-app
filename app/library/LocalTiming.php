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
        $today = false;
        $curr_time = date('H:i');
        $null_time = self::stampToStr(0);
        $curr_stamp = 0;
        $result = [];

        if ($curr_time >= $null_time && date('Y-m-d') == $date) {
            $curr_stamp = self::stamp($curr_time);
            $today = true;
        }

        $day = \App\SpecialDay::whereDate('date', $date)->first();
        if (!$day) $day = \App\WeekDay::getDayByDate($date);

        if ($day->day_off) return false;

        $result = [$day->stamp_beg, $day->stamp_end];
        
        if ($today && $curr_stamp > $result[0])
            $result[0] = $curr_stamp;
        
        return $result;
	}

	public static function checkDateStamps($date, $stamp_beg, $stamp_end)
	{	
		$dateStamps = self::getDateStamps($date);
		if (!$dateStamps) return false;
		return $stamp_beg >= $dateStamps[0] && $stamp_end <= $dateStamps[1];
	}

	public static function getDateClosedStamps($date, $table_size)
	{
        $tableType = \App\TableType::where('size', $table_size)->firstOrFail();

        $tables = $tableType->tables()->get(['id'])->pluck('id');

        $reserves = $tableType->reserves()
            ->whereDate('date', $date)
            ->get(['table_id', 'stamp_beg', 'stamp_end'])
            ->toArray();

        $reservedStamps = [];
        $crossedStamps = [];
        $closedStamps = [];

        if ($tables->count() == 1) {
        	foreach ($reserves as $reserve)
                for ($stamp = $reserve['stamp_beg']; $stamp < $reserve['stamp_end']; $stamp++)
                    $reservedStamps[] = $stamp;

	        return $reservedStamps;
        }


        foreach ($reserves as $reserve) 
        {
            for ($stamp = $reserve['stamp_beg']; $stamp < $reserve['stamp_end']; $stamp++) {

                if ( !in_array($stamp, $reservedStamps) ) 
                    $reservedStamps[] = $stamp;

                else if ( !isset($crossedStamps[$stamp]) )
                    $crossedStamps[$stamp] = 2;

                else $crossedStamps[$stamp]++;

            }
        }

        foreach ($crossedStamps as $stamp => $count)
            if ($count == $tables->count())
                $closedStamps[] = $stamp;


        return $closedStamps;
	}
	public static function getDateOpenStamps($table_size, $duration, $date)
	{	

        $dateStamps = self::getDateStamps($date);
        if(!$dateStamps) return [];
        $closedStamps = self::getDateClosedStamps($date, $table_size);
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
		$date = date('Y-m-d');

		if ($start_date && $start_date > $date) $date = $start_date;

        $openStamps = [];

		for ($i=1; $i < 180; $i++) { 
			$openStamps = self::getDateOpenStamps($table_size, $duration, $date);
			if (!empty($openStamps)) return [
	        	'date'=> $date,
	        	'stamps' => $openStamps
	        ];

	        if ($i==1 && $start_date) {
        		$date = date('Y-m-d');
        		continue;
	        }

        	$date = date('Y-m-d', strtotime($date. ' + 1 days'));
		}

        return false; //Тут может быть предупреждение, что в ближайшие пол года мест нет

	}


}

