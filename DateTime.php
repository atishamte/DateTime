<?php

namespace atishamte;

/**
 * Class DateTime
 *
 * @package atishamte
 */
abstract class DateTime
{
	static $_formats = [
		'DT' => [
			'Y-m-d H:i:s',
			'd-m-Y H:i:s',
			'm/d/Y H:i:s',
			'Y-m-d g:i A',
			'd-m-Y g:i A',
			'm/d/Y g:i A',
			'Y-m-d h:i A',
			'd-m-Y h:i A',
			'm/d/Y h:i A',
		],
		'D'  => [
			'Y-m-d',
			'd-m-Y',
			'm/d/Y',
		],
		'T'  => [
			'H:i:s',
			'H:i a',
			'h:i a',
		],
	];

	/**
	 * @param string $start_date Expected Format: 12/21/2016 | 2016-12-21 | 21-12-2016
	 * @param string $end_date   Expected Format: 12/21/2016 | 2016-12-21 | 21-12-2016
	 * @param string $unit       Example Unit: day | month | year
	 *
	 * @return string Date difference in passed unit in argument
	 */
	public static function dateDiff($start_date, $end_date = '', $unit = 'day')
	{
		if (self::validateDateTime($start_date, 'D') && self::validateDateTime($end_date, 'D')) {
			if (in_array($unit, ['day', 'month', 'year'])) {
				return self::diff($start_date, $end_date, $unit);
			} else {
				die('Invalid unit passed');
			}
		}

		return false;
	}

	/**
	 * @param string $start_time Expected Format: 04:29 AM | 4:29 AM | 04:29
	 * @param string $end_time   Expected Format: 04:29 AM | 4:29 AM | 04:29
	 * @param string $unit       Expected Format: second | minute | hour
	 *
	 * @return string Time difference in passed unit in argument
	 */
	public static function timeDiff($start_time, $end_time = '', $unit = 'second')
	{
		if (self::validateDateTime($start_time, 'T') && self::validateDateTime($end_time, 'T')) {
			if (in_array($unit, ['second', 'minute', 'hour'])) {
				return self::diff($start_time, $end_time, $unit);
			} else {
				die('Invalid unit passed');
			}
		}

		return false;
	}

	/**
	 * @param string $start_datetime
	 * @param string $end_datetime
	 * @param string $unit Expected Format: second | minute | hour | day | month | year
	 *
	 * $start_datetime, $end_datetime expected formats are,
	 * combination of date like 12/21/2016 | 2016-12-21 | 21-12-2016 and time like 04:29 AM | 4:29 AM | 04:29
	 *
	 * @return string Date-Time difference in passed unit in argument
	 */
	public static function dateTimeDiff($start_datetime, $end_datetime = '', $unit = 'second')
	{
		if (self::validateDateTime($start_datetime, 'DT') && self::validateDateTime($end_datetime, 'DT')) {
			if (in_array($unit, ['day', 'month', 'year', 'second', 'minute', 'hour'])) {
				return self::diff($start_datetime, $end_datetime, $unit);
			} else {
				die('Invalid unit passed');
			}
		}

		return false;
	}

	/**
	 * @param string $format Pass expected date format
	 *
	 * @return string Current date
	 */
	public static function currentDate($format = 'Y-m-d')
	{
		if (self::validateFormat($format, 'D')) {
			return self::getCurrent($format);
		}

		return false;
	}

	/**
	 * @param string $format Pass expected time format
	 *
	 * @return string Current time
	 */
	public static function currentTime($format = 'H:i:s')
	{
		if (self::validateFormat($format, 'T')) {
			return self::getCurrent($format);
		}

		return false;
	}

	/**
	 * @param string $format Pass expected datetime format
	 *
	 * @return string Current datetime
	 */
	public static function currentDateTime($format = 'Y-m-d H:i:s')
	{
		if (self::validateFormat($format, 'DT')) {
			return self::getCurrent($format);
		}

		return false;
	}

	/**
	 * @param string $datetime                 Expected Format: 12/21/2016 | 2016-12-21 | 21-12-2016
	 * @param string $type                     DT:DateTime | D:Date | T:Time
	 * @param string $format                   Desired Date or Time format.
	 *                                         Default Formats : Date -> Y-m-d,
	 *                                         Time -> H:i:s
	 *
	 * @return bool|string
	 */
	public static function formatDateTime($datetime, $type = 'DT', $format = '')
	{
		if (!self::validateDateTime($format, $type)) {
			switch (strtoupper($type)) {
				case 'DT':
					$format = 'Y-m-d H:i:s';
					break;
				case 'D':
					$format = 'Y-m-d';
					break;
				case 'T':
					$format = 'H:i:s';
					break;
			}
		}

		return date($format, strtotime($datetime));
	}

	/**
	 * @param string $distant_timestamp            expected formats are, combination of date like 12/21/2016 | 2016-12-21 | 21-12-2016 and time like 04:29 AM | 4:29 AM | 04:29
	 * @param int    $max_units                    Expected Input : 1 -> year,
	 *                                             2 -> month,
	 *                                             3 -> day,
	 *                                             4 -> hour,
	 *                                             5 -> minute,
	 *                                             6 -> second
	 *
	 * @return string
	 */
	public static function getTimeAgo($distant_timestamp, $max_units = 6)
	{
		if (self::validateDateTime($distant_timestamp)) {
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

		return false;
	}

	private function validateDateTime($formatted_datetime, $type = 'DT')
	{
		$validate = false;
		foreach (self::$_formats[$type] as $f) {
			if (!$validate) {
				$d = \DateTime::createFromFormat($f, $formatted_datetime);
				$validate = $d && $d->format($f) == $formatted_datetime;
			}
		}

		return $validate;
	}

	private function validateFormat($format, $type)
	{
		$validate = false;
		foreach (self::$_formats[$type] as $f) {
			if (!$validate) {
				$validate = $f == $format;
			}
		}

		return $validate;
	}

	private function getCurrent($format)
	{
		return date($format, time());
	}

	private function diff($start_datetime, $end_datetime, $unit)
	{
		$datetime1 = new \DateTime($start_datetime);
		$datetime2 = new \DateTime($end_datetime);
		$difference = $datetime1->diff($datetime2);
		$diff = false;
		switch (strtolower($unit)) {
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
}