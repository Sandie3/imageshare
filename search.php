<?php
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	include ($php_root.'/include/head.php');
	include ($php_root.'/include/conf.php');
	include ($php_root.'/include/nav.php');
	
	// Classes
	require_once ($php_root.'/include/class/feed.inc.php');

	// Default empty search
	$feed = new ImageFeed($sqllink->conn, "");

	// Check url for search query, start a new search
	if(isset($_GET['q'])){
		$feed->new_search($_GET['q']);
	}
	
	// Navbar submit
	if(isset($_POST['fuzzysearch'])){
		search_redirect($sqllink->conn, $_POST['fuzzysearch']);
	}
?>

<!DOCTYPE html>
<html lang="en">
	<link rel="stylesheet" href="/style/search.css">
	<link rel="stylesheet" href="/style/feed.css">
	
	<body>
		<div id="content" class="margin-box">
			<div class="txt-med txt-fancy" style="margin-bottom:5px;">Search</div>
			
			<div id ="searchbox">
				<form method="post">
					<input id="fuzzysearch2" name="fuzzysearch" type="text" placeholder="Search" value="<?php echo $feed->search; ?>">
					<input type="submit" name="search-submit" value="Search"/>
				</form>
			</div>
			
			<div id="feed-thumbnails" style="margin-top: 10px;">
				<?php
					$feed->print_results();
				?>
			</div>
		</div>
	</body>
</html>
