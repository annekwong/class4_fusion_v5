<?php
class OrderResponse extends OrderAppModel {
	var $useTable = false;

	function datetime_from_string($dateString) {
		if (empty($dateString)) {
			return false;
		}
		if (is_int($dateString) || is_numeric($dateString)) {
			$date = intval($dateString);
		} else {
			$date = strtotime($dateString);
		}
		return $date;
	}

}