<?php
	if(!isset($php_root)) $php_root = $_SERVER['DOCUMENT_ROOT'];
	
	// Classes
	require_once ($php_root.'/include/class/userdata.inc.php');
	require_once ($php_root.'/include/class/database.inc.php');

	// Connect to SQL
	if(!isset($sqllink)){
		$sqllink = new SQLLink();
	}

	// Get user data
	if(!isset($userdata)){
		$userdata = new Userdata($sqllink->conn, $_GET['id']);
	}
?>

<div id="header" style="background-image: url('<?php echo $userdata->get_banner_url(); ?>')">
	<link rel="stylesheet" href="/style/elements/profile.header.css">

	<div></div>

	<div id="header-bar">
		<div id="header-profileimg">
			<img src="<?php echo $userdata->get_icon_url(); ?>" alt="error">
		</div>

		<div id="header-bar-actions">
			<div id ="header-bar-section">
				<div id="header-text" class="header-bar-centered">
					<a href="<?php echo $userdata->get_profile_url(); ?>"> <?php echo $userdata->get_username(); ?> </a>
				</div>

				<?php
					// display profile buttons
					if($sqllink->is_logged_in()){
						if($userdata->is_login_user()){
							//include ($php_root.'/include/elements/profile.postbtn.php');
						}else{
							include ($php_root.'/include/elements/profile.followbtn.php');
						}
					}

					// display verified icon
					if($userdata->is_verified() == 'true'){
						echo"<div class='txt-success header-bar-centered'>";
						echo"<strong id='veri-user'>Verified</strong><i class='fas fa-check-circle'></i>";
						echo"</div>";
					}
				?>
			</div>

			<div></div>

			<div id = "header-bar-section">
				<div id="user-posts" class="header-bar-centered">
					<a href="<?php echo $userdata->get_profile_url(); ?>">Posts<br> <?php echo $userdata->get_post_count(); ?></a>
				</div>
				<div id="user-likes" class="header-bar-centered">
					<a href="<?php echo $userdata->get_likes_url(); ?>">Likes<br><?php echo $userdata->get_like_count(); ?></a>
				</div>
				<div id="user-following" class="header-bar-centered">
					<a href="<?php echo $userdata->get_following_url(); ?>">Following<br> <?php echo $userdata->get_following_count(); ?></a>
				</div>
				<div id="user-followers" class="header-bar-centered">
					<a href="<?php echo $userdata->get_followers_url(); ?>">Followers<br> <?php echo $userdata->get_follower_count(); ?></a>
				</div>
			</div>
		</div>
	</div>
</div>
