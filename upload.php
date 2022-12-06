<?php
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	include ($php_root.'/include/head.php');
	include ($php_root.'/include/conf.php');
	include ($php_root.'/include/nav.php');

	// Class
	require_once ($php_root.'/include/class/upload.inc.php');

	
	if(!$sqllink->is_logged_in()){
		href("location: /index");
	}

	// If user submits an image on the post form, run upload function
	if(isset($_POST['file-submit'])){
		Upload::upload_image($sqllink->conn, $_FILES['dropzone-files'], $_POST['filetitle'], $_POST['filedesc'], $_POST['filetags']);
	}
?>

<!DOCTYPE html>
<html lang="en">
	<link rel="stylesheet" href="/style/upload.css">

	<body>
		<div id="contents" class="margin-box">
			<div class="txt-med txt-fancy" style="margin-bottom:10px;">Upload Artwork</div>
			<?php include ($php_root.'/include/action/errors.inc.php')?>

			<div id="upload-box">
				<form id="upload-form" enctype="multipart/form-data" method="post" class="modal-inputs">
					<?php include ($php_root.'/include/elements/input.dropzone.php'); ?>
					<input class="modal-input" name="filetitle" type="text" placeholder="Title">
					<textarea class="modal-input" name="filedesc" type="text" placeholder="Description"></textarea>
					<input class="modal-input" name="filetags" type="text" placeholder="Tags">
					<input class="" name="file-submit" type="submit" value="Post">
				</form>
			</div>
		</div>
	</body>
</html>
