<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-02-12
 * Time: 오후 5:32
 */

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('_number_format')) {
	/**
	 * 숫자를 베네픽쳐 숫자포맷으로 변경
	 *
	 * 백 단위까지는 그대로 로출
	 * - 천 단위부터는 k로 표시
	 * (예시) 392,692 => 392.6k, 12,591 => 12.5k. 1,928 => 1.9k
	 * - 백만 단위부터는 m으로 표시
	 * (예시) 1,192,692 => 1.1m, 43,212,591 => 43.2m
	 *
	 * @param $number integer 숫자
	 * @param $precision integer 정확도
	 * @return   string
	 */

	function _number_format($number, $precision = 1)
	{
		if (!is_numeric($number)) {
			return $number;
		}

		$CI =& get_instance();
		$CI->lang->load('number');

		if ($number >= 1000000000000) {
			$number = round(floor($number / 100000000000) / 10, $precision, PHP_ROUND_HALF_DOWN);
			$unit = $CI->lang->line('terabyte_abbr');
		} elseif ($number >= 1000000000) {
			$number = round(floor($number / 100000000) / 10, $precision, PHP_ROUND_HALF_DOWN);
			$unit = $CI->lang->line('gigabyte_abbr');
		} elseif ($number >= 1000000) {
			$number = round(floor($number / 100000) / 10, $precision, PHP_ROUND_HALF_DOWN);
			$unit = $CI->lang->line('megabyte_abbr');
		} elseif ($number >= 1000) {
			$number = round(floor($number / 100) / 10, $precision, PHP_ROUND_HALF_DOWN);
			$unit = $CI->lang->line('kilobyte_abbr');
		} else {
			$unit = $CI->lang->line('bytes');
			return number_format($number) . $unit;
		}

		return $number . $unit;
	}
}

if (!function_exists('_time_format')) {
	/**
	 * 베네픽쳐 날짜포맷으로 변경
	 *
	 * - 현재 시간 기준 1시간 내 작성된 컨텐츠는 ‘x 분 전’으로 표시(예시. 55분 전)
	 * - 현재 시간 기준 1일 내 작성된 것은 ‘x 시간 전’으로 표시(예시. 23시간 전)
	 * - 현재 시간 기준 7일 내 작성된 것은 ‘x 일 전’으로 표시(예시. 7일 전)
	 * - 현재 시간 기준 7일 이후 작성된 것은 ‘년/월/일’로 표시(예시. 19/2/10)
	 *
	 * @param $date string 일짜
	 * @return   string
	 */

	function _time_format($date)
	{
		if (empty($date)) {
			return '';
		}

		$diff = floor(abs(time() - strtotime($date)) / 60);

		$CI =& get_instance();
		$CI->lang->load('number');

		if ($diff < 60) {
			$number = $diff;
			$unit = $CI->lang->line('min_ago');
		} elseif ($diff < 60 * 24) {
			$number = floor($diff / 60);
			$unit = $CI->lang->line('hour_ago');
		} elseif ($diff < 60 * 24 * 2) {
			$number = '';/*floor($diff / 60 / 24);*/
			$unit = $CI->lang->line('day_ago');
		} elseif ($diff < 60 * 24 * 3) {
			$number = '';/*floor($diff / 60 / 24);*/
			$unit = $CI->lang->line('2_days_ago');
		} else {
			return date('m-d'/*'y/n/j'*/, strtotime($date));
		}

		return $number . $unit;
	}
}
