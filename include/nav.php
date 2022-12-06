<?php
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	require_once ($php_root.'/include/conf.php');
	require_once ($php_root.'/include/class/accounts.inc.php');
	require_once ($php_root.'/include/class/userdata.inc.php');

	// Get user data (if logged in). Else, get null user
	if($sqllink->is_logged_in()){
		$userdata = new Userdata($sqllink->conn, $_SESSION["userUid"]);
	}else{
		$userdata = new Userdata($sqllink->conn, "");
	}
	
	// Logout action
	if(isset($_POST['logout-btn'])){
		Accounts::logout($sqllink->conn);
	}
?>

<link rel="stylesheet" href="/style/nav.css">

<div id="navbar">
	<div id="nav-left" class="flex-horiz">
		<a href="/index">
			<span id="nav-logo" class="center-box" style="height: 100%;">H</span>
		</a>
		
		<form id='admin-search' class="flex-horiz" action="/search" method="post">
			<?php include ($php_root.'/include/elements/input.fuzzysearch.php'); ?>
			<input id="admin-search-btn" type="submit" name="search-submit" value="&#xf002;" />
		</form>
	</div>
	
	<div></div>

	<div id="nav-right">
		<?php
			// Add admin link
			if($userdata->is_admin()) {
				echo "<a id='login-txt' href='/include/dash/admin'>Admin</a>";
			}
			
			// Add menu link
			if($sqllink->is_logged_in()){
				include ($php_root.'/include/elements/nav.menu.php');
			}else{
				echo "<a id='login-txt' href='/login' style='margin-right:5px'>Login</a>";
			} 
		?>
	</div>
</div>
