<?php
	/* tags.inc.php
	 * A class for managing the tags table.
	 * Insert, grab, remove tags based on text and ID */

	require_once ($php_root.'/include/class/datafilter.inc.php');

	class Tags{
		// Format a single tag and clean xss
		// $tag - string
		static function format_tag($tag){
			$tag = DataFilter::clean_xss($tag);
			$tag = trim($tag);
			$tag = strtolower($tag);
			return $tag;
		}

		// Returns a formatted list of tags given a query string
		// Example : The string "1, 2, 3" returns ["1", "2", "3"]
		// $query - string (comma seperated)
		static function format_tag_multi($query){
			// Get tags split by commas
			$tags = explode(',', $query);

			// strip trailing characters
			foreach ($tags as $index => $tag) {
				$tags[$index] = Tags::format_tag($tag);
			}

			// remove empty tags
			$tags = array_unique($tags);
			$tags = array_filter($tags);
			return $tags;
		}

		// Database functions
		
		// Search SQL database, find tag ID based on text $tag value. Return -1 if it doesn't exist
		// $tag - string
		static function find_id($conn, $tag){
			//Filter input
			$tag = Tags::format_tag($tag);
			
			$sql = "SELECT t_id FROM tags WHERE t_tags = ?";
			$stmt = mysqli_stmt_init($conn);

			// check statement valid
			if(!mysqli_stmt_prepare($stmt, $sql)){
				return -1;
			}

			// bind parameters to prepared statement, execute
			mysqli_stmt_bind_param($stmt, "s", $tag);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);

			// return SQL row data
			$result = mysqli_fetch_assoc($result);

			if(empty($result) or !isset($result) or $result == null){
				return -1;
			}

			return $result['t_id'];
		}

		// Returns a list of tag ID's based on array of tag strings
		// $tags - array of strings
		static function find_id_multi($conn, $tags){
			if(empty($tags)){
				return [];
			}
			
			foreach ($tags as $index => $tag) {
				$tags[$index] = Tags::find_id($conn, $tag);
			}
			return $tags;
		}

		// Search SQL database, find tag ID based on text $tag value. Return -1 if it doesn't exist
		// $id = int
		static function find_name($conn, $id){
			// Check if ID is a number
			if(!is_numeric($id)){
				return "";
			}
			
			$sql = "SELECT t_tags FROM tags WHERE t_id = ?";
			$stmt = mysqli_stmt_init($conn);

			// check statement valid
			if(!mysqli_stmt_prepare($stmt, $sql)){
				return "";
			}

			// bind parameters to prepared statement, execute
			mysqli_stmt_bind_param($stmt, "s", $id);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);

			// return SQL row data
			$result = mysqli_fetch_assoc($result);

			if(empty($result) or !isset($result) or $result == null){
				return "";
			}

			return $result['t_tags'];
		}

		// Returns a list of tag values based on an array of tag ID's
		// $ids = array of ints
		static function find_name_multi($conn, $ids){
			foreach ($ids as $index => $id) {
				$ids[$index] = Tags::find_name($conn, $id);
			}
			return $ids;
		}

		// Search the tag database for similar names
		// $tag - string
		static function find_name_similar($conn, $tag){
			// Clean tag
			$tag = Tags::format_tag($tag);
			
			$sql = "SELECT t_tags FROM tags WHERE t_tags LIKE CONCAT('%',?,'%') LIMIT 5";
			$stmt = mysqli_stmt_init($conn);

			// check statement valid
			if(!mysqli_stmt_prepare($stmt, $sql)){
				return [];
			}

			// bind parameters to prepared statement, execute
			mysqli_stmt_bind_param($stmt, "s", $tag);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);

			$result_tags = [];
			while($row = mysqli_fetch_assoc($result)) {
				array_push($result_tags, $row['t_tags']);
			}

			return $result_tags;
		}

		// Add a new tag to the tags table
		// $tag - string
		static function add_tag($conn, $tag){
			// Clean tag
			$tag = Tags::format_tag($tag);
			
			// Check if tag exists
			if(Tags::find_id($conn, $tag) != -1){
				//echo "Tag ".$tag." exists<br>";
				return 0;
			}
			
			$sql = "INSERT INTO tags (t_tags) VALUES (?)";
			$stmt = mysqli_stmt_init($conn);
			
			if(!mysqli_stmt_prepare($stmt, $sql)){
				return -1;
			}

			// Execute statement
			mysqli_stmt_bind_param($stmt, "s", $tag);
			$result = mysqli_stmt_execute($stmt);

			if(!$result){
				return -1;
			}
			
			//echo "Tag ".$tag." created<br>";

			return 0;
		}

		// Iterate an array of tags and add each to the database
		// $tags - array of strings
		static function add_tag_multi($conn, $tags){
			foreach ($tags as $index => $tag) {
				if(Tags::add_tag($conn, $tag) != 0){
					echo "Something went wrong.";
					return -1;
				}
			}
			return 0;
		}

		// Convert a string of tags into a json encoded array of ID's
		// $tags - array of strings
		static function encode_tag_multi($conn, $tags){
			$ids = Tags::find_id_multi($conn, $tags);

			// Filter out -1 ID's (nonexistant tags)
			$json = array('tags' => $ids); // 'tags' => $tags);
			return json_encode($json);
		}

		// Sort a an array of upload results by ID
		static function sort_uploads($array){
			return usort($array, function($a, $b) {
				return $a['id'] <=> $b['id'];
			});
		}

		// Given search string, find array of matching posts
		// $tags - search string
		static function find_uploads($conn, $tags, $filter_user, $max){
			// Get IDs
			// $tags = Tags::format_tag_multi($tags);
			$tags = Tags::find_id_multi($conn, $tags);

			if(in_array(-1, $tags)){
				return null;
			}
			
			$json = json_encode($tags);

			// SQL
			$sql = "SELECT id, img_name, img_thumb FROM uploads WHERE (JSON_CONTAINS(img_tags, ?, '$.tags') AND (user_uid=?";
			$stmt = mysqli_stmt_init($conn);

			// Skip filter_user if blank...
			if($filter_user == ""){
				$sql = $sql." OR true ";
			}

			$sql = $sql.")) ORDER BY id DESC LIMIT ?;";

			// check statement valid
			if(!mysqli_stmt_prepare($stmt, $sql)){
				return null;
			}

			// bind parameters to prepared statement, execute
			mysqli_stmt_bind_param($stmt, "sss", $json, $filter_user, $max);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);

			$array = array();
			while ($row = mysqli_fetch_assoc($result)) {
				array_push($array, $row);
			}

			// return SQL row data
			return $array; //mysqli_fetch_assoc($result)['id'];
		}
	}
?>
