<link rel="stylesheet" href="/style/elements/error.css">

<?php
	// If there's a session error message, grab and clear
	if (isset($_SESSION['errormsg'])) {
		$message = $_SESSION['errormsg'];
		unset($_SESSION['errormsg']);
	}
?>

<script>
	// Delayed hide function, runs every 1 second
	function delayhide() {
		$("#errormsg").fadeOut(250);
	}

	setTimeout(delayhide, 7000);
</script>

<?php
	if(isset($message)){
		echo ("<div id='errormsg'>".$message."</div>");
	}
?>
