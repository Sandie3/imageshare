<?php
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	include ($php_root.'/include/head.php');
	include ($php_root.'/include/conf.php');
	include ($php_root.'/include/nav.php');
	
	// Includes
	require_once ($php_root.'/include/class/feed.inc.php');
	require_once ($php_root.'/include/class/tags.inc.php');
	
	$image = new ImagePost($sqllink->conn, $_GET['id']);
	
	// 404 on invalid image
	if($image->img_data == null){
		header("Location: /404");
		exit();
	}
	
	$files = $image->get_img_files();
	$tags = $image->get_img_tags();
?>

<!DOCTYPE html>
<html lang="en">
	<link rel="stylesheet" href="/style/viewer.css">

	<body>
		<div id="veiwer-box">
			<?php
				echo "<div id='viewer-images'>";
				foreach ($files as $index => $id) {
					echo "<div id='viewer-img'><img src='".$id."'></div>";
				}
				echo "</div>";
				
				echo "<div id='viewer-tags'>";
				foreach ($tags as $index => $t) {
					$tag_name = Tags::find_name($sqllink->conn, $t);
					echo "<a href='/search?q=".$tag_name."'>#".$tag_name."</a>";
				}
				echo "</div>";
			?>
		</div>
	</body>
</html>
