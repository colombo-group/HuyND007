<?php
/**
*
* @category session
*/
	class Session {
		/**
		*check whether session exists or not
		* 
		* @param string $name
		* @return boolean
		*/
		public static function exists($name) {
			return (isset($_SESSION[$name])) ? true : false;
		}

		/**
		* initialize value of session variable
		*
		* @param string $name name of session variable
		* @param string $value value of $_SESSION['name']
		* @return string
		*/
		public static function put($name, $value) {
			return $_SESSION[$name] = $value;
		}

		/**
		*
		* @param string $name
		* @return string get value of session variable
		*/
		public static function get($name) {
			return $_SESSION[$name];
		}

		/**
		* delete session variable
		* 
		* @param string $name name of session
		*/
		public static function delete($name) {
			if (self::exists($name)) {
				unset($_SESSION[$name]);
			}
		}

		/**
		* if session existed, unset it! else set it
		* 
		* @param string $name
		* @param string $string
		* @return string 
		*/
		public static function flash($name, $string = '') {
			if (self::exists($name)) {
				$session = self::get($name);
				self::delete($name);
				return $session;
			} else {
				self::put($name, $string);
			}
		}
	}	
?>