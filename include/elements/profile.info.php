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

<link rel="stylesheet" href="/style/elements/profile.info.css">

<div id="profile-info">
	<div>
		<div class="txt-med txt-fancy">Bio</div>
		<?php echo $userdata->get_bio();?>
	</div>
	
	<div>
		<div class="txt-med txt-fancy">Info</div>
		<div>Gender: <?php echo $userdata->get_sex();?></div>
		<div>Country: <?php echo $userdata->get_country();?></div>
		<div>City: <?php echo $userdata->get_city();?></div>
	</div>
	
	<div id="profile-rank">
		<div id="rank-icon" class="center-box"> <img class="" src="/style/img/badge/b002.png" alt="rank icon"> </div>
		<div id="rank-data">
			<div class="txt-med txt-fancy">Rank 1</div>
			<div>Newbie</div>
			<div>
				<div id="rank-progress" class="progress-bar">
					<span class="progress-bar-inner"></span>
				</div>
			</div>
		</div>
	</div>
</div>
