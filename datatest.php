<?php
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	include ($php_root.'/include/head.php');
	include ($php_root.'/include/nav.php');
	
	// Includes
	require_once ($php_root.'/include/class/tags.inc.php');
?>

<!DOCTYPE html>
<html lang="en">
	<body>
		<?php
			$results = Tags::find_uploads($sqllink->conn, ["cool"]);
			
			// SELECT JSON_EXTRACT(@s, "$.tags");
			// SELECT JSON_CONTAINS(@tags, @search, '$.tags');
			/*$json = Tags::encode_tag_multi($sqllink->conn, ["mars","smokey","tag3"]);
			print_r($json);*/
			/*$j1 = "SET @j = '{1, 2, 3}';";
			$sql = "SELECT JSON_CONTAINS(@j, @j2, '$.a')";*/
			/*Tags::add_tag($sqllink->conn, "tag1");
			Tags::add_tag($sqllink->conn, "tag2");
			Tags::add_tag($sqllink->conn, "tag3");
			$json = Tags::encode_tag_multi($sqllink->conn, ["tag1","tag2","tag3"]);
			print_r($json);*/
		?>
	</body>
</html>
