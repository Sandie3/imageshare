/* Color Defs */
:root {
	--color-success: lightgreen;
	--color-link: lightblue;
	--color-error: #B72C31;
	
	/*ShibsDefault
	--color-bg: #514846;
	--color-a: #D6844E;
	 --color-b: #837572;
	--color-c: #8c7d7a;
	--color-d: #665855;
	--color-text: #fff;*/
	
	/*ShibsNeutral
	--color-bg: #404040;
	--color-a: #58B8B4;
	--color-b: #668990;
	--color-c: #63787A;
	--color-d: #585858;
	--color-text: #fff;*/
	
	/* Forest
	--color-bg: #252625;
	--color-a: #5CB26C;
	--color-b: #617C67;
	--color-c: #4A6D5E;
	--color-d: #3C4947;
	--color-text: #FFFFFF;*/

<?php
if(!isset($php_root)) $php_root = $_SERVER['DOCUMENT_ROOT'];
include ($php_root.'/include/conf.php');
require_once ($php_root.'/include/class/userdata.inc.php');
$userdata = new Userdata($sqllink->conn, $_SESSION['userUid']);
?>

	/* User Scheme */
	--color-bg: <?php echo $userdata->color_bg(); ?>;
	--color-a: <?php echo $userdata->color_a(); ?>;
	--color-b: <?php echo $userdata->color_b(); ?>;
	--color-c: <?php echo $userdata->color_c(); ?>;
	--color-d: <?php echo $userdata->color_d(); ?>;
	--color-text: <?php echo $userdata->color_text(); ?>;
}
