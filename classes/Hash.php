<?php
/**
*
* @category Hash
*/
	class Hash {
		/**
		* make new password 
		*
		* @param string $string new password
		* @param string|null $salt
		* @return string sha256 string
		*/
		public static function make($string, $salt = '') {
			return hash('sha256', $string.$salt);
		}

		/**
		* 
		* @param interger $length
		* @return string|false IV string
		*/
		public static function salt($length) {
			return mcrypt_create_iv($length);
		}

		/**
		* encryption string
		* 
		* @return string
		*/
		public static function unique() {
			return self::make(uniqid());
		}
	}
?>