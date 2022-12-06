<?php
	session_start();

	//Check login status, redirect
	if(isset($_SESSION['userUid'])){
		header("Location: /home");
	}else {
		header("Location: /frontpage");
	}
?>
