<?php
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	require_once ($php_root.'/include/conf.php');
	require_once ($php_root.'/include/class/accounts.inc.php');
	
	// Get user data (if logged in). Else, get null user
	if($sqllink->is_logged_in()){
		echo "logout..";
		Accounts::logout($sqllink->conn);
	}else{
		echo "logged out already..";
	}
	
	exit();
?>
