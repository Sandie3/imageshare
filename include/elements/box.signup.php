<?php
	if(!isset($php_root)) $php_root = $_SERVER['DOCUMENT_ROOT'];

	// Classes
	require_once ($php_root.'/include/class/accounts.inc.php');
	require_once($php_root."/lib/securimage/securimage.php");

	// Connect to SQL
	if(!isset($sqllink)){
		$sqllink = new SQLLink();
	}

	// Lockout the signup page
	$enable_lockout = false;

	if($enable_lockout == true){
		$lockout_html = "disabled";
	}else{
		$lockout_html = "";
	}

	// If user clicks signup
	if(isset($_POST['signup-submit'])){
		// Send potential spammer to 404 page
		if($enable_lockout){
			header("location: /404.php");
			exit();
		}
		
		Accounts::signup($sqllink->conn, $_POST["uid"], $_POST["pwd"], $_POST["pwd-repeat"], $_POST["email"], $_POST["dob"], $_POST["captcha_code"], $enable_lockout);
	}
?>

<link rel="stylesheet" href="/style/elements/box.signup.css">

<div id="signup-title" class="txt-med txt-fancy" style="display:none;"><b>SIGN-UP</b></div>

<?php
	if($enable_lockout){
		echo "<div id='signup-disabled'>";
		echo "<div class='txt-small'>Max failed attempts were reached.</div>";
		echo "<div class='txt-small'>Signup temporarily disabled.</div>";
		echo "</div>";
	}
?>

<?php include ($php_root.'/include/action/errors.inc.php'); ?>

<div id="signup-box" style="display:none;">
	<form id="signup-form" autocomplete="off" method="post">
		<fieldset id="signup-fields" <?php echo $lockout_html; ?>>
			<input autocomplete="off" name="hidden" type="text" style="display:none">
			<input id="fname" onkeyup="lower()" type="text" name="uid" placeholder="Username"><br>
			<input type="password" name="pwd" placeholder="Password"><br>
			<input type="password" name="pwd-repeat" placeholder="Confirm Password"><br>
			<input type="email" name="email" placeholder="E-mail"><br>
			<input type="date" name="dob" placeholder="Date of Birth"><br>
			<div class='signup-margin'>
				<div class='signup-margin'><?php echo Securimage::getCaptchaHtml(); ?></div>
				<input type='text' id='captcha' name='captcha_code' placeholder = 'Enter CAPTCHA Code'><br>
			</div>
		</fieldset>
		<button id="signup-submit-btn" type="submit" name="signup-submit" <?php echo $lockout_html; ?>>SIGN-UP</button>
	</form>
</div>
