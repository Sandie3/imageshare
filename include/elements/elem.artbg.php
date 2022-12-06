<link rel="stylesheet" href="/style/elements/elem.artbg.css">

<div id="artbg">
	<?php
		// Classes
		require_once ($php_root.'/include/conf.php');
		require_once ($php_root.'/include/class/feed.inc.php');

		// Default empty search
		$feed = new ImageFeed($sqllink->conn, Tags::format_tag_multi(""), "", 8);
		$feed->print_results("artbg-img");
	?>
</div>
