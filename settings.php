<?php
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	include ($php_root.'/include/head.php');
	include ($php_root.'/include/conf.php');
	include ($php_root.'/include/nav.php');

	// Classes
	require_once ($php_root.'/include/class/userdata.inc.php');
	require_once ($php_root.'/include/class/settings.inc.php');
	require_once ($php_root.'/include/class/upload.inc.php');

	// Get user data
	$userdata = new Userdata($sqllink->conn, $_SESSION['userUid']);

	// Redirect to index if user is not logged in
	if(!$sqllink->is_logged_in()){
		header("Location: /index");
	}
	
	// Submit actions
	// User bio
	if(isset($_POST['bio-submit'])){
		Settings::update_bio($sqllink->conn, $_POST['user_bio']);
	}

	if(isset($_POST['submit-colors'])){
		Settings::update_colors($sqllink->conn, $_POST['color_bg'], $_POST['color_a'], $_POST['color_b'], $_POST['color_c'], $_POST['color_d'], $_POST['color_text']);
	}
	
	// User icon
	if(isset($_POST['submit-profile-image'])){
		Upload::upload_icon($sqllink->conn, $_FILES['prof-img'], $_SESSION['userUid']);
	}
	
	if(isset($_POST['submit-banner-image'])){
		Upload::upload_banner($sqllink->conn, $_FILES['banner-img'], $_SESSION['userUid']);
	}
?>

<!DOCTYPE html>
<html lang="en">
	<link rel="stylesheet" href="/style/settings.css">

	<body>
		<div id="content">
			<div>
				<div id="settings-navigation">
					<span class="txt-fancy settings-margin2">Navigation</span>
					<a href="/settings#info">User Info</a>
					<a href="/settings#color">Color Scheme</a>
					<a href="/settings#image">User Images</a>
					<a href="/settings#switches">Content</a>
					<a href="/settings#blacklist">Blacklist</a>
				</div>
			</div>
			
			<div id="user-settings-column">
				<div class="txt-med txt-fancy settings-margin">User Settings</div>
				
				<?php include ($php_root.'/include/action/errors.inc.php'); ?>

				<div id="info" class="user-settings-section">
					<div id="user-setting-area">
						<span class="txt-fancy txt-med">Bio</span>
						<form id="form-bio" method="post">
							<?php echo $userdata->get_bio(); ?>
							<textarea type="text" name="user_bio" class="settings-textarea" placeholder="<?php echo $userdata->get_bio(); ?>"></textarea>
							<input class="info-input" type="submit" name="bio-submit" value="Update">
						</form>
					</div>

					<div id="user-setting-area">
						<span class="txt-fancy txt-med">User Info (disabled)</span>
						<form id="settings-form" enctype="multipart/form-data" method="post">
							<strong>Gender</strong><br>
							<input class="info-input" type="text" placeholder="<?php echo $userdata->get_sex(); ?>" name="user_sex"/>
							<strong>Country</strong><br>
							<input class="info-input" type="text" placeholder="<?php echo $userdata->get_country(); ?>" name="user_country"/>
							<strong>City</strong><br>
							<input class="info-input" type="text" placeholder="<?php echo $userdata->get_city() ?>" name="user_city"/>
							<input class="info-input" type="submit" value="Update" name="submit-settings">
						</form>
					</div>
				</div>

				<div id="color" class="user-settings-section">
					<div id="user-setting-area">
					<form id="settings-form" method="post">
						<span id="info-header"  class="txt-med txt-fancy">Color scheme (disabled)</span><br>
						<strong>Background color</strong>
						<input class="info-input" type="text" value="<?php echo $userdata->color_bg(); ?>" name="color_bg"/>
						<strong>A</strong>
						<input class="info-input" type="text" value="<?php echo $userdata->color_a(); ?>" name="color_a"/>
						<strong>B</strong>
						<input class="info-input" type="text" value="<?php echo $userdata->color_b(); ?>" name="color_b"/>
						<strong>C</strong>
						<input class="info-input" type="text" value="<?php echo $userdata->color_c(); ?>" name="color_c"/>
						<strong>D</strong>
						<input class="info-input" type="text" value="<?php echo $userdata->color_d(); ?>" name="color_d"/>
						<strong>Text color</strong>
						<input class="info-input" type="text" value="<?php echo $userdata->color_text(); ?>" name="color_text"/>
						<input class="info-input" type="submit" value="Update Colors" name="submit-colors">
					</form>
					</div>
				</div>

				<div id="image" class="user-settings-section">
					<div id="user-setting-area">
						<span id="info-header"  class="txt-med txt-fancy">Profile Image</span>
						<form id="profile-image-form" enctype="multipart/form-data" method="post">
							<input class="" type="file" name="prof-img">
							<input class="info-input" type="submit" name="submit-profile-image" value="Update">
						</form>
					</div>
					
					<div id="user-setting-area">
						<span id="info-header" class="txt-med txt-fancy">Banner Image</span>
						<form id="banner-image-form" enctype="multipart/form-data" method="post">
							<input class="" type="file" name="banner-img">
							<input class="info-input" type="submit" name="submit-banner-image" value="Update">
						</form>
					</div>
				</div>

				<div id="switches" class="user-settings-section">
					<div id="user-setting-area">
						<span id="info-header" class="txt-med txt-fancy">Displayed Content (disabled)</span>
						<form id="enables-form" method="">
							<div>
								<input type="checkbox" name="enableNSFW" value="">
								<div class="enables-label">NSFW</div>
							</div>
							<input class="info-input" type="submit" value="Update" name="submit-enables">
						</form>
					</div>
				</div>
			
			<div></div>
		</div>
	</body>
</html>
