<?php
	/* datafilter.inc.php
	 * A collection of functions that filter user inputs */
	
	class DataFilter {
		// Sanatize a string from XSS and SQL injection
		public static function clean_string($conn, $input){
			// Filter XSS injection
			$input = filter_var($input, FILTER_SANITIZE_STRING);
			//$input = htmlspecialchars($input);

			// Filter SQL injection
			$input = mysqli_real_escape_string($conn, $input);

			// return result
			return $input;
		}

		public static function clean_xss($input){
			// Filter XSS injection
			return filter_var($input, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		}
	}
?>
