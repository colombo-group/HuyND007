<?php
/**
*
* @category input 
*/
	class Input {
		/**
		*
		* check input method
		* @param string $type GET or POST method
		* @return boolean
		*/
		public static function exists($type = 'post') {
			switch ($type) {
				case 'post':
					return (!empty($_POST)) ? true : false;
					break;
				case 'get':
					return (!empty($_GET)) ? true : false;
					break;
				default:
					return false;
					break;
			}
		}

		/**
		* 
		* get value of input tag
		* @param string $item
		* @return string value of input tag
		*/
		public static function get($item) {
			if (isset($_POST[$item])) {
				return $_POST[$item];
			} else if (isset($_GET[$item])) {
				return $_GET[$item];
			}
			return '';
		}
	}
?>