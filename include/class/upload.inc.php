<?php
	/* upload.inc.php
	 * Functions for uploading content onto the site
	 * Filter images, text, and modify SQL to sync with database */
	 
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	require_once ($php_root.'/include/class/datafilter.inc.php');
	require_once ($php_root.'/include/class/imagefilter.inc.php');
	require_once ($php_root.'/include/class/tags.inc.php');

	class Upload{
		// Generate a unique id using the 'uniqueid' function
		public static function generate_id($length = 24){
			// $bytes = random_bytes(ceil($length / 2));
			$id = uniqid('', true);
			return preg_replace ("/[.]+/", '', $id);
		}

		// Check if file is a valid image upload. successful checks will return 0 
		public static function check_image($file){
			// Filter image data
			$name = $file['name'];
			$tmpname = $file['tmp_name'];
			$size = $file['size'];
			$error = $file['error'];
			$type = $file['type'];

			// Get image extension
			$extension = explode('.', $name);
			$extension = strtolower(end($extension));

			// Allowed filetypes
			$allowed_filetypes = array('jpg', 'jpeg', 'png', 'gif');
			
			// No file uploaded
			if(empty($name) or empty($tmpname)){
				$_SESSION['errormsg'] = 'Error: No file selected.';
				return -1;
			}
					
			// Check file upload error
			if($error != 0){
				$_SESSION['errormsg'] = 'Error: File error. '.$error;
				return -1;
			}

			// Check if file type allowed
			if(!in_array($extension, $allowed_filetypes)){
				$_SESSION['errormsg'] = "Error: Invalid file type. Must be .jpg, .jpeg or .png";
				return -1;
			}

			if(!exif_imagetype($tmpname)){
				$_SESSION['errormsg'] = "Error: Invalid image data detected";
				return -1;
			}

			// Max image size: 10mb
			if($size > 10 * 1048576){
				$_SESSION['errormsg'] = 'Error: Your file is too large!';
				return -1;
			}
			
			// Success
			return 0;
		}

		// Reorder an array given by $_FILES['']
		function re_array_files(&$file_post) {
			$file_ary = array();
			$file_count = count($file_post['name']);
			$file_keys = array_keys($file_post);

			for ($i=0; $i<$file_count; $i++) {
				foreach ($file_keys as $key) {
					$file_ary[$i][$key] = $file_post[$key][$i];
				}
			}

			return $file_ary;
		}

		// Upload a new image, insert into SQL
		public static function upload_image($conn, $files, $title, $desc, $tags, $redirect = "/upload"){
			// Start execution timer (benchmarking)
			$starttime = microtime(true);

			// Prevent non-logged in users
			if(!isset($_SESSION["userUid"])){
				$_SESSION['errormsg'] = "Error: not logged in.";
				header("Location: ".$redirect);
				exit();
			}

			// IMAGES
			// Reformat array
			$files = Upload::re_array_files($files);

			if(empty($files)){
				$_SESSION['errormsg'] = "Error: No files selected.";
				header("Location: ".$redirect);
				exit();
			}

			// Check each if each upload is a valid image
			foreach ($files as $file) {
				if(Upload::check_image($file) != 0){
					header("Location: ".$redirect);
					exit();
				}
			}
			
			// TEXT
			// Filter inputted text
			$title = DataFilter::clean_xss($title);
			$desc = DataFilter::clean_xss($desc);

			// Check empty
			if(empty($title) || empty($desc)){
				$_SESSION['errormsg'] = "Error: Empty fields";
				header("Location: ".$redirect);
				exit();
			}

			// TAGS
			// Create array of comma seperated tags
			$tags = Tags::format_tag_multi($tags);
			
			// Add tags to database
			if(Tags::add_tag_multi($conn, $tags) != 0){
				$_SESSION['errormsg'] = "Error adding tags to database";
				header("Location: ".$redirect);
				exit();
			}

			// Encode tag ID's as json
			$tags = Tags::encode_tag_multi($conn, $tags);

			// IMAGE UPLOAD
			// Get filename info
			$img_id = Upload::generate_id();
			
			// Array to store all generated filenames
			$filenames_arr = [];

			// Process each file
			foreach ($files as $key => $file){
				// Get current file extension
				$extension = explode('.', $file["name"]);
				$extension = ".".strtolower(end($extension));

				// Create name: "(random id)_(image num).(extension)"
				// Example: "5cf5ae50eb94a037371400_01.jpg"
				$img_num = $key;
				$outpath = 'database/uploads/'.$img_id."_".$img_num.$extension;

				// Generate image
				$imgdata = new ImageFilter($file['tmp_name']);
				$imgdata->writeout($outpath);
				unset($imgdata);
				
				// Setup filename for database
				array_push($filenames_arr, "/".$outpath);
			}
			
			// Create json containing all filenames
			$filenames_arr = array('files' => $filenames_arr);
			$filenames_json = json_encode($filenames_arr);
			
			// Generate thumbnail from first image
			$thumbpath = 'database/thumbnails/thumb_'.$img_id.".jpg";
			$thumbdata = new ImageFilter($files[0]['tmp_name']);
			$thumbdata->compress_thumbnail();
			$thumbdata->writeout($thumbpath);
			unset($thumbdata);
			
			// Setup filename for database
			$thumbpath = "/".$thumbpath;
			// SQL
			// Prepare SQL statement
			$sql = "INSERT INTO uploads (img_title, img_desc, img_name, user_uid, img_mature, img_tags, img_files, img_thumb) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_stmt_init($conn);

			if(!mysqli_stmt_prepare($stmt, $sql)){
				$_SESSION['errormsg'] = "Error: SQL Error";
				header("Location: ".$redirect);
				exit();
			}

			// Execute SQL statement
			$uid = $_SESSION['userUid'];
			$nsfw = "0";
			mysqli_stmt_bind_param($stmt, "ssssssss", $title, $desc, $img_id, $uid, $nsfw, $tags, $filenames_json, $thumbpath);
			mysqli_stmt_execute($stmt);
			
			$endtime = microtime(true);
			
			// Success message / exit
			$_SESSION['errormsg'] = "Success: Image uploaded. Execution time: ".($endtime - $starttime)." ms";
			header("Location: ".$redirect);
			exit();
		}
		 
		/// Upload a file and set as user's icon
		public static function upload_icon($conn, $file, $uid){
			// Start execution timer (benchmarking)
			$starttime = microtime(true);
			
			// check image and get status
			$file_status = Upload::check_image($file);
			
			if($file_status != 0){
				header("Location: /settings");
				exit();
			}
			
			//set upload destination
			$outname = $uid.'.jpg';
			$outpath = 'database/icons/'.$outname;
			
			// Generate user icon (using imgfilter.inc.php function)
			$imgdata = new ImageFilter($file['tmp_name']);
			$imgdata->compress_thumbnail();
			$imgdata->writeout($outpath);
			
			// Update SQL
			$sql = "UPDATE users SET url_icon='".$outname."' WHERE user_uid='".$uid."'";
			mysqli_query($conn, $sql);
			
			$endtime = microtime(true);
			
			// Success message / exit
			$_SESSION['errormsg'] = "Success: Profile image updated: ".$outname.", Execution time: ".($endtime - $starttime)." ms";
			header("Location: /settings");
			exit();
		}

		/// Upload a file and set as user's icon
		public static function upload_banner($conn, $file, $uid){
			// Start execution timer (benchmarking)
			$starttime = microtime(true);
			
			// check image and get status
			$file_status = Upload::check_image($file);
			
			if($file_status != 0){
				header("Location: /settings");
				exit();
			}
			
			//set upload destination
			$outname = $uid.'.jpg';
			$outpath = 'database/banners/'.$outname;
			
			// Generate user icon (using imgfilter.inc.php function)
			$imgdata = new ImageFilter($file['tmp_name']);
			$imgdata->jpegify();
			$imgdata->writeout($outpath);
			
			// Update SQL
			$sql = "UPDATE users SET url_banner='".$outname."' WHERE user_uid='".$uid."'";
			mysqli_query($conn, $sql);
			
			$endtime = microtime(true);
			
			// Success message / exit
			$_SESSION['errormsg'] = "Success: Banner image updated: ".$outname.", Execution time: ".($endtime - $starttime)." ms";
			header("Location: /settings");
			exit();
		}
	}
?>
