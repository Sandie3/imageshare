<link rel="stylesheet" href="/style/elements/input.dropzone.css">

<div id="dropzone" class="dragarea" onclick="location.href='#';" style="cursor: pointer;">
	<div id="dropzone-preview"></div>
	<div id="dropzone-placeholder">Drag Files Here</div>
</div>

<input id="dropzone-files" type="file" name="dropzone-files[]" multiple style="display:none;">

<script>
	$("#dropzone").on("dragover", function(event) {
		event.preventDefault();
		$(this).addClass('dragging');
	});

	$("#dropzone").on("dragleave", function(event) {
		event.preventDefault();
		$(this).removeClass('dragging');
	});

	$("#dropzone").on("drop", function(event) {
		event.preventDefault();
		$(this).removeClass('dragging');

		// Move the dragged files into the file input
		event.dataTransfer = event.originalEvent.dataTransfer;
		$("#dropzone-files")[0].files = event.dataTransfer.files;

		// Trigger update preview
		update_preview($("#dropzone-files")[0], $("#dropzone-preview")[0]);
	});

	$("#dropzone").click(function(){
		$("#dropzone-files").click();
	});

	$("#dropzone-files").change(function(){
		update_preview($("#dropzone-files")[0], $("#dropzone-preview")[0]);
	});

	 // Multiple images preview in browser
	function update_preview(input, previews) {
		console.log($("#dropzone-files")[0].files);
		if (input.files) {
			$("#dropzone-preview").empty();
			$("#dropzone-placeholder").hide();
			var filesAmount = input.files.length;

			for (i = 0; i < filesAmount; i++) {
				var reader = new FileReader();
				reader.onload = function(event) {
					var container = $.parseHTML("<div class='dropzone-preview-container'></div>");
					var previewimg = $.parseHTML("<img class='dropzone-previewimg' src='" + event.target.result + "'>");
					$(previewimg).appendTo(container);
					$(container).appendTo(previews);
					//$($.parseHTML().attr('src', ).appendTo(previews);
				}
				reader.readAsDataURL(input.files[i]);
			}
		}
	};
</script>
