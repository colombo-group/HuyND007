<?php
/**
* 
* @category Token
*/
	class Token {
		/**
		* generate token 
		*
		* @return string 
		*/
		public static function generate() {
			return Session::put(Config::get('session/tokenName'), md5(uniqid()));
		}

		/**
		* check whether tolen exists or not
		* if true unset session token
		*
		* @param string $token
		* @return boolean
		*/
		public static function check($token) {
			$tokenName = Config::get('session/tokenName');

			if (Session::exists($tokenName) && $token === Session::get($tokenName)) {
				Session::delete($tokenName);
				return true;
			} else {
				return false;
			}
		}
	}
?>