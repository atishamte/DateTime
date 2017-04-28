<?php

namespace atishamte;

/**
 * Class DateTime
 *
 * @package atishamte
 */
class DateTime
{
	/**
	 * @param string $start_date Expected Format: 21/12/2016 | 2016-12-21 | 21-12-2016
	 * @param string $end_date   Expected Format: 21/12/2016 | 2016-12-21 | 21-12-2016
	 * @param string $unit       Example Unit: day | month | year
	 *
	 * @return string Date difference in passed unit in argument
	 */
	public static function dateDiff($start_date, $end_date = 'NOW', $unit = 'day')
	{
		return '';
	}

	/**
	 * @param string $start_time Expected Format: 08:29 PM | 8:29 PM | 20:29
	 * @param string $end_time   Expected Format: 08:29 PM | 8:29 PM | 20:29
	 * @param string $time_type  Expected Format: 12(12 Hours) | 24(24Hours)
	 * @param string $unit       Expected Format: second | minute | hour
	 *
	 * @return string Time difference in passed unit in argument
	 */
	public static function timeDiff($start_time, $end_time = 'NOW', $time_type = '24', $unit = 'second')
	{
		return '';
	}

	/**
	 * @param string $start_datetime
	 * @param string $end_datetime
	 * @param string $unit Expected Format: second | minute | hour | day | month | year
	 *
	 * $start_datetime, $end_datetime expected formats are,
	 * combination of date like 21/12/2016 | 2016-12-21 | 21-12-2016 and time like 08:29 PM | 8:29 PM | 20:29
	 *
	 * @return string Date-Time difference in passed unit in argument
	 */
	public static function dateTimeDiff($start_datetime, $end_datetime = 'NOW', $unit = 'second')
	{
		return '';
	}

	/**
	 * @param string $format Pass expected date format
	 *
	 * @return string Current date
	 */
	public static function currentDate($format = 'Y-m-d')
	{
		return date($format, time());
	}

	/**
	 * @param string $format Pass expected time format
	 *
	 * @return string Current time
	 */
	public static function currentTime($format = 'H:i:s')
	{
		return date($format, time());
	}

	/**
	 * @param string $format Pass expected datetime format
	 *
	 * @return string Current datetime
	 */
	public static function currentDateTime($format = 'Y-m-d H:i:s')
	{
		return date($format, time());
	}

    /**
     * @param string $datetime Expected Format: 21/12/2016 | 2016-12-21 | 21-12-2016
     * @param string $type  DT:DateTime | D:Date | T:Time
     * @param string $format Desired Date or Time format.
     *                       Default Formats : Date -> Y-m-d,
     *                                         Time -> H:i:s
     *
     * @return bool|string
     */
    public static function formatDateTime($datetime, $type = 'DT', $format = '')
	{
		$datetime = str_replace('/', '-', $datetime);
		$type = strtoupper($type);
		if ($type == 'DT') {
			return date('jS-M-Y g:iA', strtotime($datetime));
		}
		if ($type == 'D') {
			return date('jS-M-Y', strtotime($datetime));
		}
		if ($type == 'T') {
			return date('g:iA', strtotime($datetime));
		}
		return false;
	}

	/**
	 * @param string $return Expected Format : year | month | day | hour | minute | second
	 * @param string $date1  Expected Format: 21/12/2016 | 2016-12-21 | 21-12-2016
	 * @param string $date2  Optional Field(If not passed, current datetime will be considered)
     *                       If Passed, Expected Format: 21/12/2016 | 2016-12-21 | 21-12-2016
	 *
	 * @return mixed
	 */
	public static function get_date_diff($return, $date1, $date2 = '')
	{
		$datetime1 = new \DateTime($date1);
		$datetime2 = new \DateTime($date2);

		$difference = $datetime1->diff($datetime2);

		$diff = FALSE;

		switch (strtolower($return)) {
			case 'year':
				$diff = $difference->y;
				break;

			case 'month':
				$diff = ($difference->y * 12) + $difference->m;
				break;

			case 'day':
				$diff = $difference->days;
				break;

            case 'hour':
                $diff = ($difference->days * 24) + $difference->h;
                break;

            case 'minute':
                $diff = ((($difference->days * 24) + $difference->h) * 60) + $difference->i;
                break;

            case 'second':
                $diff = ((((($difference->days * 24) + $difference->h) * 60) + $difference->i) * 60) + $difference->s;
                break;
		}
		if ($datetime1 > $datetime2) {
			$diff = $diff * (-1);
		}

		return $diff;
	}

    /**
     * @param string $distant_timestamp            expected formats are, combination of date like 21/12/2016 | 2016-12-21 | 21-12-2016 and time like 08:29 PM | 8:29 PM | 20:29
     * @param int    $max_units                    Expected Input : 1 -> year,
     *                                             2 -> month,
     *                                             3 -> day,
     *                                             4 -> hour,
     *                                             5 -> minute,
     *                                             6 -> second
     *
     * @return string
     */
	public static function get_time_ago($distant_timestamp, $max_units = 6)
	{
        $date = new \DateTime();
        $date->setTimestamp(strtotime($distant_timestamp));
        $date = $date->diff(new \DateTime());

        // build array
        $since = json_decode($date->format('{"year":%y,"month":%m,"day":%d,"hour":%h,"minute":%i,"second":%s}'), true);

        // output only the first x date values
        $since = array_slice($since, 0, $max_units);

        // build string
        $last_key = key(array_slice($since, -1, 1, true));

        $string = '';

        foreach ($since as $key => $val) {
            // separator
            if ($string) {
                $string .= $key != $last_key ? ', ' : ' ' . 'and' . ' ';
            }
            // set plural
            $key .= $val > 1 ? 's' : '';
            // add date value
            $string .= $val . ' ' . $key;
        }

        return $string;
	}

    /**
     * @param $formatted_datetime   DateTime string
     *
     * @return bool
     */
    private function validateFormat($formatted_datetime)
    {
        return false;

    }
}