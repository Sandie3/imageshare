<?php
	if(!isset($php_root)) $php_root = $_SERVER['DOCUMENT_ROOT'];
	include($php_root.'/include/action/follow.inc.php');
?>

<form id='form-follow' method='post'>
	<input id='follow-data' type='hidden' name='id' value="<?php echo $_GET['id'] ?>">

	<?php
		//Check if user (session[user_uid]) is following page owner (get[id]) here
		$q = "SELECT * FROM follow WHERE sender='".$_SESSION["userUid"]."' AND receiver='".$_GET["id"]."'";
		$re = $conn->query($q);
		$ro = $re->fetch_assoc();

		//$is_following = false;

		// Echo the button element
		if($ro['following'] == false){
			echo "<button id='btn-follow' type='submit' name='follow'>Follow</button>";
		}else{
			echo "<button id='btn-unfollow' type='submit' name='unfollow'>Unfollow</button>";
		}
	?>
</form>
