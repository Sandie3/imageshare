<?php
	require_once ($php_root.'/include/class/database.inc.php');
	
	if(!isset($sqllink)){
		$sqllink = new SQLLink("root", "password");
	}
?>
