<?php
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	include ($php_root.'/include/head.php');
	include ($php_root.'/include/conf.php');
	include ($php_root.'/include/nav.php');
	
	// Redirect to index if user is logged in
	if($sqllink->is_logged_in()){
		header("Location: /index.php");
		exit();
	}
?>

<!DOCTYPE html>
<html lang="en">
	<body>
		<div id="contents" class="center-box">
			<div><?php include ($php_root.'/include/elements/box.login.php'); ?></div>
		</div>
	</body>
</html>
