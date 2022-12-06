<?php
	if(!isset($php_root)) $php_root = $_SERVER['DOCUMENT_ROOT'];

	// Classes
	require_once ($php_root.'/include/class/accounts.inc.php');
	require_once ($php_root.'/include/class/database.inc.php');
	require_once($php_root."/lib/securimage/securimage.php");

	// Connect to SQL
	if(!isset($sqllink)){
		$sqllink = new SQLLink();
	}

	// show / hide login fields
	$enable_lockout = false;
	$enable_captcha = false;

	// Disable html fields on lockout
	if($enable_lockout == true){
		$lockout_html = "disabled";
	}else{
		$lockout_html = "";
	}

	// Log user in
	if(isset($_POST['login-submit'])){
		// Send potential spammer to 404 page
		if($enable_lockout){
			header("location: /404.php");
			exit();
		}
		
		// Get the captcha
		$captchacode = "";
		if(isset($_POST["captcha_code"])){
			$captchacode = $_POST["captcha_code"];
		}
		
		Accounts::login($sqllink->conn, $_POST["mailuid"], $_POST["pwd"], $captchacode, $enable_captcha, $enable_lockout);
	}
?>

<link rel="stylesheet" href="/style/elements/box.login.css">

<div id="login-title" class="txt-med txt-fancy"><b>LOG-IN</b></div>

<?php
	if($enable_lockout){
		echo "<div id='login-disabled'>";
		echo "<div class='txt-small'>Max failed attempts were reached.</div>";
		echo "<div class='txt-small'>Login temporarily disabled.</div>";
		echo "</div>";
	}
?>

<?php include ($php_root.'/include/action/errors.inc.php')?>

<div id="login-box">
	
	<form id="login-form" method="post">
		<fieldset id="login-fields" <?php echo $lockout_html; ?>>
			<input type="text" name="mailuid" placeholder="Username"><br>
			<input type="password" name="pwd" placeholder="Password"><br>

			<?php
				if($enable_captcha && !$enable_lockout){
					echo "<div class='login-margin'>";
					echo "<div class='login-margin'>".Securimage::getCaptchaHtml()."</div>";
					echo "<input type='text' id='captcha' name='captcha_code' placeholder = 'Enter CAPTCHA Code'><br>";
					echo "</div>";
				}
			?>
			
			<input id="login-button" type="submit" name="login-submit" value="LOGIN">
		</fieldset>
	</form>
</div>
