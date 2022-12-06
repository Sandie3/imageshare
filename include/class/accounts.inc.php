<?php
	/* accounts.inc.php
	 * Functions for handling login / logout / signup */

	require_once ($php_root.'/include/class/datafilter.inc.php');
	require_once($php_root."/lib/securimage/securimage.php");
	
	class Accounts{
		// Check login credentials, log failed attempts or sign user in
		static function login($conn, $user, $pass, $captcha="", $enable_captcha = false, $enable_lockout = false){
			// Immediate exit of locked out
			if($enable_lockout == true){
				$_SESSION['errormsg'] = 'Error: Locked Out!';
				header("Location: /login");
				exit();
			}

			if(!isset($captcha)){
				$captcha = "";
			}
			
			// Clean user input
			$user = DataFilter::clean_string($conn, $user);
			$pass = DataFilter::clean_string($conn, $pass);
			$captcha = DataFilter::clean_string($conn, $captcha);

			// Check empty fields
			if(empty($user) || empty($pass) || (empty($captcha) && $enable_captcha)){
				$_SESSION['errormsg'] = 'Error: Empty fields';
				header("Location: /login");
				exit();
			}

			// Check captcha
			$securimage = new Securimage();
			
			if($enable_captcha && $securimage->check($captcha) == false){
				$_SESSION['errormsg'] = 'Error: Incorrect Captcha';
				header("Location: /login");
				exit();
			}

			// Find user
			$sql = "SELECT * FROM users WHERE user_uid=?;";
			$stmt = mysqli_stmt_init($conn);

			if(!mysqli_stmt_prepare($stmt, $sql)){
				$_SESSION['errormsg'] = 'Error: SQL Error';
				header("Location: /login");
				exit();
			}

			mysqli_stmt_bind_param($stmt, "s", $user);
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
			$pwdChech = password_verify($pass, $row['user_pwd']);

			if($pwdChech == false){
				$_SESSION['errormsg'] = 'Error: Username or password incorrect';
				header("Location: /login");
				exit();
			}

			// Initialize login!!
			$_SESSION['userId'] = $row['user_id'];
			$_SESSION['userUid'] = $row['user_uid'];

			// Login success!
			header("Location: /index.php");
			exit();
		}

		// Quit out of a current login session
		static function logout($conn){
			if(!isset($_SESSION))
				session_start();
			
			// Destroy session
			session_unset();
			session_destroy();
			header("Location: /index.php");
			exit();
		}

		// Signup a new user in the SQL database
		static function signup($conn, $uid, $pwd, $confirmPwd, $email, $dob, $captcha_code, $enable_lockout = false){
			// Filter user data
			$uid = DataFilter::clean_string($conn, $uid);
			$uid = strtolower($uid);
			$pwd = DataFilter::clean_string($conn, $pwd);
			$confirmPwd = DataFilter::clean_string($conn, $confirmPwd);
			$email = DataFilter::clean_string($conn, $email);
			$dob = DataFilter::clean_string($conn, $dob);
			$captcha_code = DataFilter::clean_string($conn, $captcha_code);

			// Empty fields
			if(empty($uid) || empty($email) || empty($pwd) || empty($confirmPwd) || empty($dob) || empty($captcha_code)){
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
			$sql = "SELECT user_uid FROM users WHERE user_uid=?";
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
			$sql = "INSERT INTO users (user_uid, user_email, user_pwd, user_dob) VALUES (?, ?, ?, ?)";
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

			$_SESSION['errormsg'] = 'Signup successful! Account confirmation link has been emailed to you.';
			header("Location: /frontpage");
			exit();
		}
	}
?>
