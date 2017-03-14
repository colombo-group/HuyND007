<?php
/**
*
*@category Redirect
*/
	class Redirect {
		/**
		* redirect user to a site, if site doesn't exist return not found path
		** include file 404.php to notice if site doesn't exist
		*
		* @param string $location
		*/
		public static function to($location = null) {
			if ($location) {
				if (is_numeric($location)) {
					switch ($location) {
						case '404':
							header('HTTP/1.0 404 Not Found');
							include 'includes/errors/404.php';
							exit();
						break;
					}
				}
				header('Location: '.$location);
				exit();
			}
		}
	}
?>