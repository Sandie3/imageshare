<?php
	if(isset($_POST['post-btn'])){
		if(empty($_POST['blip'])){
			$message = "Enter a post!";
			return 0;
		}

		$ID = mysqli_real_escape_string($conn, $_SESSION['userId']);
		$UID = mysqli_real_escape_string($conn, $_SESSION['userUid']);
		$post = mysqli_real_escape_string($conn, $_POST['blip']);

		$sql = "INSERT INTO posts (user_id, user_uid, post) VALUES ('".$ID."', '".$UID."', '".$post."')";
		$result = mysqli_query($conn, $sql);

		$message = "posted successfully";
		header("location: /profile/".$_GET['id']);
		exit;
	}
?>
