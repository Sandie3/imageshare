<?php
	// Prepared statements v1
	$column = mysqli_real_escape_string($this->conn, $column);
	$column = filter_var($column, FILTER_SANITIZE_STRING);
		
	// Create prepared statment
	$sql = "SELECT * FROM users2 WHERE user_uid=?;";
	$stmt = mysqli_stmt_init($this->conn);

	// check statement valid
	if(!mysqli_stmt_prepare($stmt, $sql)){
		exit();
	}

	// bind parameters to prepared statement, execute
	mysqli_stmt_bind_param($stmt, "s", $this->id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$row = mysqli_fetch_assoc($result);
	
	return $row[$column];
?>

<?php
	// Prapared statements v2
	$sql = "SELECT * FROM users2 WHERE user_uid=:username";

	// Create prepared statement & bind values
	$stmt = $this->conn->prepare($sql);
	
	$stmt->bindValue(":username", $this->id, PDO::PARAM_STR);
	$result = $stmt->execute()->fetch();
	return $result[$column];
?>

<?php
	// Direct SQL
	$sql = "SELECT * FROM profileimg WHERE user_uid='".$this->id."'";
	$result = $this->conn->query($img);
	$row = $result->fetch_assoc();
?>

<?php

		function get_icon_url(){
			// Get profile image status & link
			$sql = "SELECT * FROM profileimg WHERE user_uid='".$this->id."'";
			$result = $this->conn->query($img);
			$row = $result->fetch_assoc();
			
			if(!empty($row['img'])){
				return "/database/icons/".$row['img'];
			}

			return "/database/icons/default.jpg";
		}

		

		// Redirect logic for profiles
	function profile_redirect($conn){
		// Filter id
		$tmpuser = new Userdata($conn, $_GET['id']);
		
		// If 'ID' is empty and user is logged in, default to user's own profile
		if(!isset($tmpuser->id) or empty($tmpuser->id)){
			if(isset($_SESSION['userId'])){
				header("Location: /profile/".$_SESSION['userUid']);
				exit();
			}else{
				// If all else fails, go to index
				header("Location: /index.php");
				exit();
			}
		}
		
		// User doesnt exist!
		if(!$tmpuser->is_valid()){
			header("Location: /404");
			exit();
		}

		// Clear memory
		unset($tmpuser);
	}
?>

<?php
	// search a tag for a special keyword, return all contents after the keyword
		// Example: get_filter_content("user:bob", "user:"); will return "bob".
		function extract_filter_data($tag, $keyword){
			// Not in string
			if (!strstr($tag, $keyword)) {
				return "";
			}
			
			// Get index of special keyword. Take substring from end of keyword to end of tag
			$index = strpos($tag, $keyword, -1) + strlen($keyword);
			$data = substr($tag, $index, strlen($tag));
			
			return trim($data);
		}

		// iterate tags, check for special filter
		function extract_filters(){
			$filter_keywords = ['user:', 'likes:', 'followfeed:'];
			
			// Iterate tags, check if special_keyword exists, append value
			foreach ($this->tags as $index => $tag) {
				// Iterate filters
				foreach ($filter_keywords as $keyword) {
					// Check if filter is in current tag
					if (strstr($tag, $keyword)) {
						array_push($this->filters, $tag);
						unset($this->tags[$index]);
					}
				}
			}
		}

		
	/*class UserSearch{
		// Raw search string
		var $search;

		// Result array of users
		var $results;

		// Limit of users to be shown
		var $user_max;

		// SQL connection
		var $conn;

		// Constructor
		function __construct($conn, $search="") {
			// Link SQL connection
			$this->conn = $conn;

			// defaults (empty search)
			$this->search = "";
			$this->results = [];

			// default display settings
			$this->user_max = 12;

			// Start an initial search
			$this->new_search($this->search);
		}
		
		// Search database for usernames similar to "$search", echo list of links
		function new_search($search){
			$this->search = Datafilter::clean_string($this->conn, $search);
			$sql = "SELECT * FROM users2 WHERE user_uid LIKE '%".$this->search."%' LIMIT ".$user_max;
			$result = mysqli_query($this->conn, $sql);
			$result = mysqli_num_rows($result);
			$this->results = mysqli_fetch_assoc($result);
		}

		// Print links to result user's pages
		function print_links(){
			// Check empty
			if($this->results < 0){
				echo "<a>No results found!</a>";
				return;
			}
			
			// Print user page links
			while($row = $this->results){
				echo "<a href='/profile/".$row['user_uid']."'>".$row['user_uid']."</a>";
			}
		}
	}*/
		
		/*function grab_images($page){
			$tmpimage = "/database/icons/default.jpg";
			//$tmpimage = "/style/img/bgweb2.png";
			$num = $this->image_max;

			// Create a thumbnail feed
			if($this->use_thumbnails){
				echo "<div id='feed-thumbnails' style='grid-template-columns: repeat(".$this->image_columns.", 1fr);'>";

				for ($n = 0; $n < $num; $n++){
					echo "<div class='feed-thumbnail'><a href='".$tmpimage."'><img class='border-hilight border-fancy' src='".$tmpimage."'></a></div>";
				}
				
				echo "</div>";
			}

			// Create a full image feed
			if(!$this->use_thumbnails){
				echo "<div id='feed-images'>";
				
				for ($n = 0; $n < $num; $n++){
					echo "<div class='feed-image'><a href='".$tmpimage."'><img class='' src='".$tmpimage."'></a></div>";
				}
				
				echo "</div>";
			}
		}*/
	/*/ Getter / setter
		// Set number of displayed images in feed
		function set_max($n){
			$this->image_max = $n;
		}

		// Set number of displayed columns in feed
		function set_columns($n){
			$this->image_columns = $n;
		}

		// Set thumbnail toggle (true/false)
		function set_thumbnails($choice){
			$this->use_thumbnails = $choice;
		}*/
?>

<input class="" id="file-input" type='file' name=file><br>
	<div class="" id="file-preview" style="">
	<img class="previewimg" src="/style/img/badge/sky.jpg">
	<img class="previewimg" src="/style/img/badge/sky.jpg">
	<img class="previewimg" src="/style/img/badge/sky.jpg">
	<img class="previewimg" src="/style/img/badge/sky.jpg">
	<img class="previewimg" src="/style/img/badge/sky.jpg">
	<img class="previewimg" src="/style/img/badge/sky.jpg">
	<img class="previewimg" src="/style/img/badge/sky.jpg">
</div>

<div class="filearea" class="modal-input" style="margin-bottom:10px;">
	<input id="file-input" type="file" name="files"/>
	<div>Drag Files Here</div>
</div>
