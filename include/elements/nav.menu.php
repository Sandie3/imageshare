<div id="navmenu">
	<div id="navmenu-toggle" onclick="" style="cursor: pointer;"><?php echo $userdata->get_username();?></div>
</div>

<script>
	// Dropdown menu handling
	$(document).ready(function(){
		$("#navmenu-items").hide();
	});
	
	$("#navmenu-toggle").click(function () {
		$("#navmenu-items").show();
	});
	
	$("#navmenu-toggle").get(0).onoutclick = function () {
		$("#navmenu-items").hide();
	}
</script>

<div id="navmenu-items" style="display:none;">
	<a id='login-txt' href="/profile">
		<div>Profile</div>
		<div></div>
		<i class="fa fa-user" style="font-size:14px"></i>
	</a>
	
	<a id='login-txt' href='/upload'>
		<div>Upload</div>
		<div></div>
		<i class="fa fa-upload" style="font-size:14px"></i>
	</a>
	<a id='login-txt' href='/settings'>
		<div>Settings</div>
		<div></div>
		<i class="fa fa-gear" style="font-size:14px"></i>
	</a>
	<a id='login-txt' href='/include/action/logout.inc.php'>
		<div>Logout</div>
		<div></div>
		<i class="fa fa-key" style="font-size:14px"></i>
	</a>
</div>
