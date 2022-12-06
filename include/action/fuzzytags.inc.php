<?php
	header('Content-Type: application/json');

	$php_root = $_SERVER['DOCUMENT_ROOT'];
	include ($php_root.'/include/conf.php');
	require_once ($php_root.'/include/class/tags.inc.php');

	$results = array();
	$tags = $_POST['fuzzytags'];

	if(!isset($tags) or empty($tags) or $tags == "") {
		$results['results'] = array();
		
		echo json_encode($results);
		exit();
	}

	// Get array of searched tags
	$tags = Tags::format_tag_multi($tags);
	$tag = end($tags);
	$results['results'] = Tags::find_name_similar($sqllink->conn, $tag);
	
	echo json_encode($results);
	exit();
?>
