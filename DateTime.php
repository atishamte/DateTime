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


	public static function getDateTime($type = '', $format = '')
	{
		$type = strtoupper($type);
		if ($type == '') {
			$str = 'select NOW() as result';
			if ($format == '') $format = 'jS-M-Y g:iA';
		} else {
			if ($format == '') {
				if ($type == 'DATE') $format = 'jS-M-Y';
				if ($type == 'TIME') $format = 'g:iA';
			}
			$str = 'select CUR' . $type . '() as result';
		}
		$ci =& get_instance();
		$query = $ci->db->query($str)->row_array();
		return date($format, strtotime($query['result']));
	}


	public static function format_datetime($datetime, $type = 'DT')
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
	 * @param string $return like year, month or day
	 * @param string $date1  should be in format of 'dd-mm-yyyy'
	 * @param string $date2  should be in format of 'dd-mm-yyyy' and '' as default if blank
	 *
	 * @return mixed
	 */
	public static function get_date_diff($return, $date1, $date2 = '')
	{
		$datetime1 = new DateTime($date1);
		$datetime2 = new DateTime($date2);

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
		}
		if ($datetime1 > $datetime2) {
			$diff = $diff * (-1);
		}

		return $diff;
	}

	/**
	 * @param datetime $distant_timestamp
	 * @param int      $max_units
	 *
	 * @return string
	 */
	public static function get_time_ago($distant_timestamp, $max_units = 1)
	{
		$i = 0;
		$time = time() - strtotime($distant_timestamp); // to get the time since that moment
		$tokens = [
			31536000 => 'year',
			2592000  => 'month',
			604800   => 'week',
			86400    => 'day',
			3600     => 'hour',
			60       => 'minute',
			1        => 'second'
		];

		$responses = [];
		while ($i < $max_units) {
			foreach ($tokens as $unit => $text) {
				if ($time < $unit) {
					continue;
				}
				$i++;
				$numberOfUnits = floor($time / $unit);

				$responses[] = $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
				$time -= ($unit * $numberOfUnits);
				break;
			}
		}

		if (!empty($responses)) {
			return implode(', ', $responses) . ' ago';
		}

		return 'Just now';
	}
}