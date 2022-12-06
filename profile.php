<?php
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	include ($php_root.'/include/head.php');
	include ($php_root.'/include/conf.php');
	include ($php_root.'/include/nav.php');

	// Classes
	require_once ($php_root.'/include/class/userdata.inc.php');
	require_once ($php_root.'/include/class/feed.inc.php');

	// Create Userdata object, contains info about current profile.
	$pageid = "";
	if(isset($_GET['id'])){
		$pageid = $_GET['id'];
	}
	
	$userdata = new Userdata($sqllink->conn, $pageid);
	
	// Check if profile url is valid
	$userdata->profile_redirect();

	// Setup profile image feed (posts, reblogs)
	$feed = new ImageFeed($sqllink->conn, Tags::format_tag_multi(""), $_GET['id']);
?>

<!DOCTYPE html>
<html lang="en">
	<link rel="stylesheet" href="/style/profile.css">
	<link rel="stylesheet" href="/style/feed.css">

	<body>
		<div id="contents">
			<?php include ($php_root.'/include/elements/profile.header.php');?>

			<div id="profile-main" class="margin-box">
				<?php include ($php_root.'/include/action/errors.inc.php')?>
				<?php include ($php_root.'/include/elements/profile.info.php')?>

				<div id="uploads-area">
					<div class="txt-fancy txt-med" style="">Posts</div>
					<div id="feed-thumbnails">
					<?php
						//$feed->print_data();
						$feed->print_results();
					?>
					</div>
				</div>
				
				<?php include ($php_root.'/include/elements/elem.adbanner.php')?>
			</div>
		</div>
	</body>
</html>
