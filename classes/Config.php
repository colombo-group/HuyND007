<?php
/**
* @category config
* 
*/
	class Config {
		/**
		*
		* @param string $path Get path
		* @return string or boolean
		*/
		public static function get($path = null) {
			if ($path) {
				$config = $GLOBALS['config'];
				$path	= explode('/', $path);

				foreach ($path as $bit) {
					if (isset($config[$bit])) {
						$config = $config[$bit];
					}
				}

				return $config;
			}
			
			return false;
		}
	}
?>