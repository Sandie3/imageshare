<?php
	// If bio submit clicked
	if(isset($_POST['bio-submit']) && isset($_POST["user_bio"])){
		// Check if bio is empty
		if(empty($_POST["user_bio"])){
			$message = "Bio is empty!";
			return 1;
		}

		// filter bio text
		$bio = mysqli_real_escape_string($conn, $_POST['user_bio']);
		$bio = filter_var($bio, FILTER_SANITIZE_STRING);

		// Carete prepared statment
		$sql = "UPDATE users2 SET user_bio=? WHERE user_id=?;";
		$sql_stmt = mysqli_stmt_init($conn);

		// check statement valid
		if(!mysqli_stmt_prepare($sql_stmt, $sql)){
			$_SESSION['errormsg'] = "SQL statement failed.";
			header("Location: /settings");
			exit();
		}

		// bind parameters to prepared statement, execute
		mysqli_stmt_bind_param($sql_stmt, "ss", $bio, $_SESSION["userId"]);
		mysqli_stmt_execute($sql_stmt);

		$_SESSION['errormsg'] = "User bio updated.";
		header("Location: /settings");
		exit();
	}
?>
