<?php
	/* feed.inc.php
	 * Create feeds of images based on search queries */
	
	require_once ($php_root.'/include/class/datafilter.inc.php');
	require_once ($php_root.'/include/class/tags.inc.php');

	class ImageFeed {
		// Raw search string
		var $search;

		// Arrays containing tag text and id's
		var $tags, $tag_ids;
		var $results;

		// Additional SQL filters
		var $filter_user;

		// Display settings
		var $image_max, $image_columns;
		
		// SQL connection
		var $conn;
		
		// Constructor
		function __construct($conn, $search="", $filter_user="", $image_max=99) {
			// Link SQL connection
			$this->conn = $conn;

			// defaults (empty search)
			$this->search = "";
			$this->tags = [];

			// default display settings
			$this->image_max = $image_max; // Max images per page
			$this->image_columns = 4; // Max images per column in feed

			// filters
			$this->filter_user = Datafilter::clean_string($this->conn, $filter_user);

			// Start an initial search
			$this->new_search($search);
		}

		function __destruct() {
			unset($this->search);
			unset($this->tags);
			unset($this->image_max);
			unset($this->image_columns);
			unset($this->conn);
		}

		// Start a new search, extract data
		function new_search($search){
			$this->search = Datafilter::clean_string($this->conn, $search);
			$this->tags = [];
			
			// Check for empty search
			if(!isset($this->search) or empty($this->search)){
				$this->search = "";
			}
			
			// Get formatted array of tags
			$this->tags = Tags::format_tag_multi($this->search);

			// Grab image results
			$this->find_results();
		}

		// Find n image results matching given tags
		// $n - int
		function find_results(){
			$this->results = Tags::find_uploads($this->conn, $this->tags, $this->filter_user, $this->image_max);
		}

		function print_results($class_style=""){
			if((!isset($this->results) or empty($this->results)) and $class_style == ""){
				echo "No results found";
				return;
			}

			if($class_style == ""){
				$class_style = "feed-thumbnail border-fancy border-hilight";
			}
			
			foreach ($this->results as $key => $img){
				$thumb_url = $img['img_thumb'];
				echo "<div class='".$class_style."'><a href='/post/".$img["img_name"]."'><img src='".$thumb_url."'></a></div>";
			}
		}

		// Print info about the current search
		function print_data(){
			// Display data
			echo "search = '".$this->search."'<br>";
			
			echo "tags = [";
			foreach ($this->tags as &$tag) {
				echo $tag.", ";
			}
			echo "]<br>";

			echo "images per page = ".$this->image_max."<br>";
			echo "images per column = ".$this->image_columns."<br>";
		}
	}
	
	class ImagePost{
		// Raw search string
		var $id;
		
		// SQL data for upload
		var $img_data;
		
		// SQL connection
		var $conn;
		
		// Constructor
		function __construct($conn, $imgname="") {
			$this->conn = $conn;
			$this->imgname = $imgname;
			$this->img_data = $this->find_imgdata($this->imgname);
		}
		
		// Search SQL for a given user's data
		function find_imgdata(){
			// Create prepared statment
			$sql = "SELECT * FROM uploads WHERE img_name=?;";
			$stmt = mysqli_stmt_init($this->conn);

			// check statement valid
			if(!mysqli_stmt_prepare($stmt, $sql)){
				return null;
			}

			// bind parameters to prepared statement, execute
			mysqli_stmt_bind_param($stmt, "s", $this->imgname);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);

			// return SQL row data
			return mysqli_fetch_assoc($result);
		}
		
		function get_img_files(){
			$json = $this->img_data['img_files'];
			$arr = json_decode($json, true);
			
			if(!isset($arr['files'])){
				return [];
			}
			
			return $arr["files"];
		}
		
		function get_img_tags(){
			$json = $this->img_data['img_tags'];
			$arr = json_decode($json, true);
			
			if(!isset($arr['tags'])){
				return [];
			}
			
			return $arr["tags"];
		}
	}

	// Redirect to the search page based on a query
	function search_redirect($conn, $search){
		$search = Datafilter::clean_xss($search);
		header("Location: search?q=".$search);
	}
?>
