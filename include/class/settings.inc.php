<?php
	/*
		UserSettings - A class containing functions to update user settings
		(mainly used by settings.php)
	*/

	require_once ($php_root.'/include/class/datafilter.inc.php');
	
	class Settings{
		// Update bio based on input text
		function update_bio($conn, $bio_text){
			$bio_text = DataFilter::clean_string($conn, $bio_text);
			
			// Check if bio is empty
			if(empty($bio_text)){
				$_SESSION['errormsg'] = "Error: Bio is empty!";
				header("Location: /settings#");
				exit();
			}

			// Carete prepared statment
			$sql = "UPDATE users SET user_bio=? WHERE user_id=?;";
			$sql_stmt = mysqli_stmt_init($conn);

			// check statement valid
			if(!mysqli_stmt_prepare($sql_stmt, $sql)){
				$_SESSION['errormsg'] = "SQL statement failed.";
				header("Location: /settings");
				exit();
			}

			// bind parameters to prepared statement, execute
			mysqli_stmt_bind_param($sql_stmt, "ss", $bio_text, $_SESSION["userId"]);
			mysqli_stmt_execute($sql_stmt);

			$_SESSION['errormsg'] = "Bio updated.";
			header("Location: /settings");
			exit();
		}

		function update_colors($conn, $color_bg, $color_a, $color_b, $color_c, $color_d, $color_text){
			$color_bg = DataFilter::clean_string($conn, $color_bg);
			$color_a = DataFilter::clean_string($conn, $color_a);
			$color_b = DataFilter::clean_string($conn, $color_b);
			$color_c = DataFilter::clean_string($conn, $color_c);
			$color_d = DataFilter::clean_string($conn, $color_d);
			$color_text = DataFilter::clean_string($conn, $color_text);
		
			if(empty($color_bg)){
				$_SESSION['errormsg'] = "Error: Color is empty!";
				header("Location: /settings#color");
				exit();
			}
			if(empty($color_a)){
				$_SESSION['errormsg'] = "Error: Color is empty!";
				header("Location: /settings#color");
				exit();
			}
			if(empty($color_b)){
				$_SESSION['errormsg'] = "Error: Color is empty!";
				header("Location: /settings#color");
				exit();
			}
			if(empty($color_c)){
				$_SESSION['errormsg'] = "Error: Color is empty!";
				header("Location: /settings#color");
				exit();
			}
			if(empty($color_d)){
				$_SESSION['errormsg'] = "Error: Color is empty!";
				header("Location: /settings#color");
				exit();
			}
			if(empty($color_text)){
				$_SESSION['errormsg'] = "Error: Color is empty!";
				header("Location: /settings#color");
				exit();
			}

			$sql = "UPDATE users SET color_bg=?, color_a=?, color_b=?, color_c=?, color_d=?, color_text=? WHERE user_uid=?";
			$sql_stmt = mysqli_stmt_init($conn);
			mysqli_stmt_bind_param($sql_stmt, "sssssss", $color_bg, $color_a, $color_b, $color_c, $color_d, $color_text, $_SESSION["userUid"]);
			mysqli_stmt_execute($sql_stmt);

			$_SESSION['errormsg'] = "Colors updated.";
			header("Location: /settings");
			exit();
		}
	}
?>
