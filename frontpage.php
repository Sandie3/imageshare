<?php
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	include ($php_root.'/include/head.php');
	include ($php_root.'/include/conf.php');
	include ($php_root.'/include/nav.php');
	
	// Redirect to home if user is logged in
	if($sqllink->is_logged_in()){
		header("Location: /home");
		exit();
	}
?>

<!DOCTYPE html>
<html lang="en">
	<body>
		<link rel="stylesheet" href="/style/frontpage.css">

		<div id="contents" class="center-box">
			<div><?php include($php_root.'/include/elements/elem.artbg.php'); ?></div>

			<div id="welcome-message" class="center-box" style="display:none;">
				<div class="txt-large txt-fancy">Hiraeth Art</div>
				<div class="txt-small">A digital home for your artwork</div>
				<div class="flex-horiz">
					<form action="/login">
						<input type="submit" id="choice-login" class="btn-large" value="Log In">
					</form>
					<form id="choice-signup-form" action="/frontpage#signup">
						<input type="submit" id="choice-signup" class="btn-large" value="Sign Up">
					</form>
				</div>
			</div>
			<div><?php include ($php_root.'/include/elements/box.signup.php'); ?></div>
		</div>
	</body>
	
	<script>
		function show_boxes(force_signup=false, animate=false){
			var hash = window.location.hash.substr(1);
			if(hash == "signup" || force_signup == true){
				$('#welcome-message').hide();
				if(animate == true){
					$('#signup-box').fadeIn(500);
					$('#signup-title').fadeIn(500);
				}else{
					$('#signup-box').show();
					$('#signup-title').show();
				}
			}else{
				$('#welcome-message').show();
				$('#signup-box').hide();
				$('#signup-title').hide();
			}
		}

		$('#choice-signup-form').on('submit', function () {
			show_boxes(true, true);
		});

		$(document).ready(function(){
			show_boxes();
		});
	</script>
</html>
