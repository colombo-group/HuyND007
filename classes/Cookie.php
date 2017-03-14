<?php
/**
* @category Cookie
*
*/
	class Cookie {
		/**
		* check whether cookie exists or not
		* 
		* @param string $name
		* @return boolean
		*/
		public static function exists($name) {
			return (isset($_COOKIE[$name])) ? true : false;
		}
		/**
		* get name of cookie
		* 
		* @param string $name
		* @return string get value of cookie variable
		*/
		public static function get($name) {
			return $_COOKIE[$name];
		}
		/**
		* initialize cookie variable
		*
		* @param string $name name of cookie
		* @param mixed $value value of cookie variable
		* @param interger $expiry existing time of cookie
		* @return boolean 
		*/
		public static function put($name, $value, $expiry) {
			if (setcookie($name, $value, time()+$expiry, '/')) {
				return true;
			}
			return false;
		}
		/**
		* delete cookie varialbe
		* 
		*/
		public static function delete($name) {
			self::put($name, '', time()-1);
		}
	}
?>