<?php
namespace Toy\Util;

class DateUtil{
	
	public static function now($format = 'Y-m-d H:i:s'){
		return date($format);
	}
    
    public static function dbNow(){
        return date('Y-m-d H:i:s');
    }
	
	public static function getDayStartTime($date){
		$datetime = ctype_digit($date)? $date: strtotime($date);
		list($y, $m, $d) = explode('-', date('Y-m-d', $datetime));
		return date('Y-m-d H:i:s', mktime(0, 0, 0, $m, $d, $y));
	}
	
	public static function getDayEndTime($date){
		$datetime = ctype_digit($date)? $date: strtotime($date);
		list($y, $m, $d) = explode('-', date('Y-m-d', $datetime));
		return date('Y-m-d H:i:s', mktime(23, 59, 59, $m, $d, $y));
	}
	
	public static function getMonthStartTime($month = NULL, $format = 'l'){
		if(empty($month)){
			$month = date('n');
		}
		$time = mktime(0, 0, 0, $month, 1, date('y'));
		return Soul_Locale_Factory::getCulture()->formatDate($time, $format);
	}
	
	public static function getMonthEndTime($month = NULL, $format = 'l'){
		if(empty($month)){
			$month = date('n');
		}		
		$time = mktime(23, 59, 59, $month + 1, 0, date('y'));
		return Soul_Locale_Factory::getCulture()->formatDate($time, $format);		
	}
	
	public static function add($date, $diff, $dateFormat = 'l'){
		$datetime = ctype_digit($date)? $date: strtotime($date);
		list($y,$M,$d,$h,$m,$s) = explode('-', date('Y-m-d-H-i-s', $datetime));
		$format = substr($diff, -1);
		switch($format){
			case 's':
				$s = $s + substr($diff, -2);
				break;
			case 'm':
				$m = $m + substr($diff, -2);
				break;
			case 'h':
				$h = $h + substr($diff, -2);
				break;
			case 'd':
				$d = $d + substr($diff, -2);
				break;
			case 'M':
				$M = $M + substr($diff, -2);
			case 'y':
				$y = $y + substr($diff, -2);				
				break;
			case 'w':
				$m = $d + (substr($diff, -2) * 7);
				break;				
		}
		$result = mktime($h, $m, $s, $M, $d, $y);
		if($dateFormat === false){
			return $result;
		}
		return Soul_Locale_Factory::getCulture()->formatDate($result, $dateFormat);
	}
	
	public static function sub($date, $diff, $dateFormat = 'l'){
		$datetime = ctype_digit($date)? $date: strtotime($date);
		list($y,$M,$d,$h,$m,$s) = explode('-', date('Y-m-d-H-i-s', $datetime));
		$format = substr($diff, -1);
		switch($format){
			case 's':
				$s = $s - substr($diff, -2);
				break;
			case 'm':
				$m = $m - substr($diff, -2);
				break;
			case 'h':
				$h = $h - substr($diff, -2);
				break;
			case 'd':
				$d = $d - substr($diff, -2);
				break;
			case 'M':
				$M = $M - substr($diff, -2);
			case 'y':
				$y = $y - substr($diff, -2);
				break;
			case 'w':
				$m = $d - (substr($diff, -2) * 7);
				break;				
		}
		$result = mktime($h, $m, $s, $M, $d, $y);
		if($dateFormat === false){
			return $result;
		}
		return Soul_Locale_Factory::getCulture()->formatDate($result, $dateFormat);
	}	

	public static function diff($first, $second, $format = 's'){
		$a = ctype_digit($first)? $first: strtotime($first);
		$b = ctype_digit($second)? $second: strtotime($second);
		$di = $a - $b;
		return $di;
	}
}