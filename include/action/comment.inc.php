<?php
	session_start();
	require 'conf.php';

	if(empty($_POST['comm'])){
		$message = "Enter a comment!";
		header("Location: ../home");
		exit();
	}

	if(isset($_POST['comm'])){
		$q = "SELECT id FROM posts";
		$r = $conn->query($q);
		$r2 = $r->fetch_assoc();
		$postid = mysqli_real_escape_string($conn, $r2['id']);
		$UID = mysqli_real_escape_string($conn, $_SESSION['userUid']);
		$post = mysqli_real_escape_string($conn, $_POST['comm']);

		$sql = "INSERT INTO comments (user_uid, comment, post_id) VALUES ('".$UID."', '".$post."', '".$postid."')";
		$result = mysqli_query($conn, $sql);

	}else{
		header("Location: ../home");
		exit();
	}

	header("Location: ../home");
	exit();
?>

