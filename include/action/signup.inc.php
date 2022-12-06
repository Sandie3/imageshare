<?php
	// Require SQL conf
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	require ($php_root."/include/conf.php");
	
	// On signup post
	if(isset($_POST['signup-submit'])){
		// Grab user data
		$uid = mysqli_real_escape_string($conn, strtolower($_POST['uid']));
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
		$confirmPwd = mysqli_real_escape_string($conn, $_POST['pwd-repeat']);
		$dob = mysqli_real_escape_string($conn, $_POST['dob']);

		// Empty fields
		if(empty($uid) || empty($email) || empty($pwd) || empty($confirmPwd) || empty($dob)){
			$_SESSION['errormsg'] = 'Error: Empty fields';
			header("Location: /frontpage");
			exit();
		}
		
		// Check invalid email
		if(!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $uid)){
			$_SESSION['errormsg'] = 'Error: Invalid email';
			header("Location: /frontpage");
			exit();
		}
		
		// Check invalid email (2)
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$_SESSION['errormsg'] = 'Error: Invalid email';
			header("Location: /frontpage");
			exit();
		}
		
		// Check invalid userid
		if(!preg_match("/^[a-zA-Z0-9]*$/", $uid)){
			$_SESSION['errormsg'] = 'Error: Invalid username';
			header("Location: /frontpage");
			exit();
		}
		
		// Password mismatch
		if($pwd !== $confirmPwd){
			$_SESSION['errormsg'] = 'Error: Passwords do not match';
			header("Location: /frontpage");
			exit();
		}
		
		// Check if username taken
		// Prepare statement
		$sql = "SELECT user_uid FROM users2 WHERE user_uid=?";
		$stmt = mysqli_stmt_init($conn);
		
		if(!mysqli_stmt_prepare($stmt, $sql)){
			$_SESSION['errormsg'] = 'Error: SQL Error';
			header("Location: /frontpage");
			exit();
		}
		
		// Execute statement
		mysqli_stmt_bind_param($stmt, "s", $uid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		$resultCheck = mysqli_stmt_num_rows($stmt);
		
		// Check username taken (result)
		if($resultCheck > 0){
			$_SESSION['errormsg'] = 'Error: Username '.$uid.' taken';
			header("Location: /frontpage");
			exit();
		}
		
		// create user prepared statement
		$sql = "INSERT INTO users2 (user_uid, user_email, user_pwd, user_dob) VALUES (?, ?, ?, ?)";
		$stmt = mysqli_stmt_init($conn);
		
		if(!mysqli_stmt_prepare($stmt, $sql)){
			$_SESSION['errormsg'] = 'Error: SQL Error';
			header("Location: /frontpage");
			exit();
		}
		
		// Create hashed password
		$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

		// Execute statement
		mysqli_stmt_bind_param($stmt, "ssss", $uid, $email, $hashedPwd, $dob);
		mysqli_stmt_execute($stmt);

		// Setup default profile image
		$sql = "INSERT INTO profileimg (user_uid) VALUES ('$uid')";
		mysqli_query($conn, $sql);

		header("Location: /login");
		exit();
	}

	header("Location: /frontpage");
	exit();

?>

