<?php

/**
 * Helper Class
 */
class Helper {

	function __construct() {
		# code...
	}

	function customEach(&$arr) {
		//echo "<pre>";print_r($arr);echo "</pre>";die;
		$result = [];
		if (is_array($arr)) {
			$key = key($arr);
			$result = ($key === null) ? false : [$key, current($arr), 'key' => $key, 'value' => current($arr)];
			next($arr);
		}
		return $result;
	}

}

?>