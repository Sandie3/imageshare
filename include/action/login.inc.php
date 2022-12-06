<?php
	// Require SQL conf
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	require ($php_root."/include/conf.php");

	// On login post
	if(isset($_POST['login-submit'])){
		// Get user post data
		$mailuid = mysqli_real_escape_string($conn, strtolower($_POST['mailuid']));
		$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

		// Check empty fields
		if(empty($mailuid) || empty($pwd)){
			$_SESSION['errormsg'] = 'Error: Empty fields';
			header("Location: /login");
			exit();
		}

		// Find user
		$sql = "SELECT * FROM users2 WHERE user_uid=?;";
		$stmt = mysqli_stmt_init($conn);

		if(!mysqli_stmt_prepare($stmt, $sql)){
			$_SESSION['errormsg'] = 'Error: SQL Error';
			header("Location: /login");
			exit();
		}

		mysqli_stmt_bind_param($stmt, "s", $mailuid);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($result);

		// Check if user exists
		if(!$row){
			$_SESSION['errormsg'] = 'Error: Username or password incorrect';
			header("Location: /login");
			exit();
		}

		// Check password
		$pwdChech = password_verify($pwd, $row['user_pwd']);

		if($pwdChech == false){
			$_SESSION['errormsg'] = 'Error: Username or password incorrect';
			header("Location: /login");
			exit();
		}

		// Initialize login!!
		$_SESSION['userId'] = $row['user_id'];
		$_SESSION['userUid'] = $row['user_uid'];

		// Login success!
		header("Location: /home");
		exit();
	}

	// Redirect if not correct post
	header("Location: /login");
	exit();
?>
