<?php
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	include ($php_root.'/include/head.php');
	include ($php_root.'/include/conf.php');
	include ($php_root.'/include/nav.php');

	// Classes
	require_once ($php_root.'/include/class/userdata.inc.php');
	require_once ($php_root.'/include/class/feed.inc.php');

	// Create Userdata object, contains info about current profile.
	$userdata = new Userdata($sqllink->conn, $_GET['id']);
	
	// Check if profile url is valid
	$userdata->profile_redirect();

	// Setup profile image feed (posts, reblogs)
	$feed = new ImageFeed($sqllink->conn, "likes:".$_GET['id']);
?>

<!DOCTYPE html>
<html lang="en">
	<link rel="stylesheet" href="/style/profile.css">
	<link rel="stylesheet" href="/style/feed.css">

	<body>
		<div id="contents">
			<?php include ($php_root.'/include/elements/profile.header.php'); ?>

			<div id="profile-main" class="margin-box">
				<?php include ($php_root.'/include/action/errors.inc.php')?>
				
				<div id="feed-images">
					<div class="center-box">Likes</div>
				</div>
			</div>
		</div>
	</body>
</html>
