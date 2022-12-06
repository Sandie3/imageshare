<?php
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	include ($php_root.'/include/head.php');
	include ($php_root.'/include/conf.php');
	include ($php_root.'/include/nav.php');
	
	// Includes
	require_once ($php_root.'/include/class/feed.inc.php');
	
	// Generate home feed
	$feed = new ImageFeed($sqllink->conn, "");
?>

<!DOCTYPE html>
<html lang="en">
	<link rel="stylesheet" href="/style/home.css">
	<link rel="stylesheet" href="/style/feed.css">
	
	<body>
		<div id="contents" class="margin-box">
			<div>
				<?php
					$feed->print_data();
				?>
			</div>
		</div>
	</body>
</html>
