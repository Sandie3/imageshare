<?php
	if(!isset($php_root)) $php_root = $_SERVER['DOCUMENT_ROOT'];

	// Classes
	require_once ($php_root.'/include/class/database.inc.php');
	require_once($php_root.'/include/class/upload.inc.php');

	// Connect to SQL
	if(!isset($sqllink)){
		$sqllink = new SQLLink();
	}
	
	// If user submits an image on the post form, run upload function
	if(isset($_POST['img-btn'])){
		upload_image($sqllink->conn, $_FILES['file'], $_GET['id'], $_POST['filetitle'], $_POST['filedesc'], $_POST['filetags'], $_POST['nsfw']);
	}
?>

<link rel="stylesheet" href="/style/elements/profile.postbtn.css">

<button id="modal-toggle">Post</button>

<div id="post-form" class="modal" style="display:none;">
	<div class="modal-content">
		<div id="closebtn">
			<div class="txt-fancy">New Post</div>
			<div></div>
			<a href="#" onclick="closeModal()">X</a>
		</div>
		<div id="post-choice" class="flex-horiz">
			<input id="choice-text" type="submit" value="Text">
			<input id="choice-art" type="submit" value="Art" style="margin-left:10px;">
		</div>
		<form id="text-form" class="modal-inputs" method="post"  class="modal-inputs">
			<!--input name="title" type="text" placeholder="Title"-->
			<textarea class="modal-input" name="blip" type="text" placeholder="Something new?"></textarea>
			<input type="submit" name="post-btn" value="Post">
		</form>
		
		<form id="img-form" enctype="multipart/form-data" method="post" class="modal-inputs">
			<input class="modal-input" id="img-upload-input" type='file' name=file onchange="update_preview()"><br>
			<div id="previewimg-container" class="center-box modal-input">
				<img class="previewimg border-fancy2" id="img-upload-preview" alt="Image preview...">
			</div>
			<input class="modal-input" name="filetitle" type="text" placeholder="Title">
			<textarea class="modal-input" name="filedesc" type="text" placeholder="Description"></textarea>
			<input class="modal-input" name="filetags" type="text" placeholder="Tags">
			<div class="flex-horiz" style="margin-bottom: 5px;">
				<input type="checkbox" name="nsfw" value="1">
				NSFW?
			</div>
			<input type="submit" name="img-btn" value="Post">
		</form>
	</div>
</div>

<script>
	// Update the image preview
	function update_preview(data){
		if (data != null && data.files && data.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$("#img-upload-preview").attr('src', e.target.result);
				$("#previewimg-container").hide();
				$("#previewimg-container").fadeIn(300);
			}

			reader.readAsDataURL(data.files[0]);
		}
	}

	// Update preview on image upload
	$("#img-upload-input").change(function(){
		update_preview(this);
	});
	
	$(document).ready(function(){
		$('#post-form').hide();
		resetModal();
	});

	// Reset modal state
	function resetModal(){
		$('#post-choice').show();
		$('#text-form').hide();
		$('#img-form').hide();

		// Reset forms
		$('#img-form').trigger("reset");
		$('#text-form').trigger("reset");

		//Reset preview image
		$("#img-upload-preview").attr('src', "");
		$("#previewimg-container").hide();
	}

	function closeModal(){
		$('#post-form').css({ display: "none" });
		resetModal();
	}

	// Choices
	$('#choice-text').click(function() {
		$('#post-choice').hide();
		$('#text-form').show();
	});

	$('#choice-art').click(function() {
		$('#post-choice').hide();
		$('#img-form').show();
	});
	
	// Click post button
	$('#modal-toggle').click(function() {
		$("#post-form").css({ display: "flex" });
	});

	// Click outside of modal
	window.onclick = function(event) {
		if (event.target == $('#post-form').get(0)) {
			closeModal();
		}
	}
</script>
