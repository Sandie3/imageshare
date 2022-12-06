<?php
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	require ($php_root.'/include/conf.php');

	// Follow or unfollow button clicked
	if (isset($_POST['follow']) || isset($_POST['unfollow'])){
		$rid = $_POST['id'];
		$sid = $_SESSION['userUid'];

		// Check if logged in
		if(!isset($sid) || empty($sid)){
			$message = "Error, not logged in!";
			header("Location: /login");
			return 1;
		}

		// Check if RID is valid
		if(!isset($rid) || empty($rid)){
			$message = "Error, getting user ID";
			header("Location: /profile/".$rid);
			return 1;
		}

		// Execute SQL follow
		if(isset($_POST['follow'])){
			$message = $sid." -> ".$rid;
			mysqli_query($conn, "INSERT INTO follow (sender, receiver, following) VALUES ('".$sid."', '".$rid."', 'true')");
			header("Location: /profile/".$rid);
			return 0;
		}

		// Execute SQL unfollow
		if(isset($_POST['unfollow'])){
			$message = $sid." X ".$rid;
			mysqli_query($conn, "DELETE FROM follow WHERE sender='".$sid."' AND receiver='".$rid."'");
			header("Location: /profile/".$rid);
			return 0;
		}
	}
?>
