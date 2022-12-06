<?php
	/* userdata.inc.php
	 * A class that manages a given user's data. 
	 * Prints stats, settings, and stores important urls. */

	require_once ($php_root.'/include/class/datafilter.inc.php');

	class Userdata {
		// User ID (username) and user valid state
		var $id, $valid;
		
		// Array of SQL data corresponding to user (user_uid, user_bio, etc...)
		var $sqldata;

		// URLs
		var $icon_url, $banner_url;
		var $profile_url, $likes_url, $following_url, $followers_url;
		
		// SQL connection
		var $conn;

		// Constructor
		function __construct($conn, $id) {
			// Link SQL connection
			$this->conn = $conn;
			
			// Filter and grab ID
			$this->id = DataFilter::clean_string($this->conn, $id);

			// Grab SQL data associated with ID
			$this->sqldata = $this->get_sqldata();
			$this->valid = $this->is_valid();

			// Grab URL data from SQL
			$this->get_urls();
		}

		// Free memory on destroy
		function __destruct() {
			unset($this->id);
			unset($this->sqldata);
			unset($this->valid);
			unset($this->conn);
		}

		// Evaluate the current ID, and redirect based on session variables.
		function profile_redirect(){
			// Blank ID
			if(!isset($this->id) or empty($this->id)){
				// Logged in & blank ID
				if(isset($_SESSION['userId'])){
					header("Location: /profile/".$_SESSION['userUid']);
					exit();
				}else{
					// If all else fails, go to index
					header("Location: /index.php");
					exit();
				}
			}

			// Non-blank invalid ID
			if(!$this->valid){
				header("Location: /404");
				exit();
			}
		}

		// Search SQL for a given user's data
		function get_sqldata(){
			// Create prepared statment
			$sql = "SELECT * FROM users WHERE user_uid=?;";
			$stmt = mysqli_stmt_init($this->conn);

			// check statement valid
			if(!mysqli_stmt_prepare($stmt, $sql)){
				return null;
			}

			// bind parameters to prepared statement, execute
			mysqli_stmt_bind_param($stmt, "s", $this->id);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);

			// return SQL row data
			return mysqli_fetch_assoc($result);
		}

		// Returns value of specific column for selected user
		function get_column($column){
			// Check if key exists in array
			if(($this->sqldata == null) or !array_key_exists($column, $this->sqldata)){
				return "";
			}
			
			return $this->sqldata[$column];
		}

		// Get URL variables for profile page
		function get_urls(){
			$this->icon_url = $this->get_column("url_icon");
			$this->banner_url = $this->get_column("url_banner");
			
			// Set icon URL (If empty, use default)
			if(!empty($this->icon_url)){
				$this->icon_url = "/database/icons/".$this->icon_url;
			}else{
				$this->icon_url = "/database/icons/default.jpg";
			}
			
			// Set banner URL (If empty, use default)
			if(!empty($this->banner_url)){
				$this->banner_url = "/database/banners/".$this->banner_url;
			}else{
				$this->banner_url = "/database/banners/default.jpg";
			}

			// Set profile URL
			$this->profile_url = "/profile/".$this->id;
			$this->likes_url = "/likes/".$this->id;
			$this->following_url = "/following/".$this->id;
			$this->followers_url = "/followers/".$this->id;
		}

		// Check if the user ID exists
		public function is_valid(){
			return ($this->get_column("user_uid") != "" and $this->id != "");
		}
		
		// Check if the $_SESSION user matches this Userdata object
		function is_login_user(){
			return ($_SESSION['userUid'] == $this->id);
		}

		// Getters: User counts
		function get_post_count(){
			$query = $this->conn->query("SELECT COUNT(*) FROM posts WHERE user_uid='".$this->id."'");
			$row = $query->fetch_row();
			return $row[0];
		}
		
		function get_like_count(){
			$query = $this->conn->query("SELECT COUNT(*) FROM likes WHERE user_uid='".$this->id."'");
			$row = $query->fetch_row();
			return $row[0];
		}
		
		function get_follower_count(){
			$query = $this->conn->query("SELECT COUNT(*) FROM follow WHERE receiver='".$this->id."'");
			$row = $query->fetch_row();
			return $row[0];
		}
		
		function get_following_count(){
			$query = $this->conn->query("SELECT COUNT(*) FROM follow WHERE sender='".$this->id."'");
			$row = $query->fetch_row();
			return $row[0];
		}

		// Getters: User Data
		function get_username(){
			return $this->id;
		}
		
		function get_bio(){
			return $this->get_column("user_bio");
		}
		
		function get_sex(){
			return $this->get_column("user_sex");
		}
		
		function get_country(){
			return $this->get_column("user_country");
		}
		
		function get_city(){
			return $this->get_column("user_city");
		}
		
		function is_verified(){
			return $this->get_column("verified");
		}
		
		function is_admin(){
			return $this->get_column("user_mod");
		}

		// Getters: Colors
		function color_bg(){
			return $this->get_column("color_bg");
		}
		function color_a(){
			return $this->get_column("color_a");
		}
		function color_b(){
			return $this->get_column("color_b");
		}
		function color_c(){
			return $this->get_column("color_c");
		}
		function color_d(){
			return $this->get_column("color_d");
		}
		function color_text(){
			return $this->get_column("color_text");
		}

		// Getters: URLs
		function get_icon_url(){
			return $this->icon_url;
		}

		function get_banner_url(){
			return $this->banner_url;
		}

		function get_profile_url(){
			return $this->profile_url;
		}
		
		function get_likes_url(){
			return $this->likes_url;
		}
		
		function get_following_url(){
			return $this->following_url;
		}
		
		function get_followers_url(){
			return $this->followers_url;
		}
	}
?>
