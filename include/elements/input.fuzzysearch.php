<link rel="stylesheet" href="/lib/awesomeplete/awesomplete.css">
<script src="/lib/awesomeplete/awesomplete.js"></script>

<input id="fuzzysearch" name="fuzzysearch" type="text" placeholder="Search">

<script>
	var timer;
	var last_value = "";
	var awesomeplete = new Awesomplete(
		$("#fuzzysearch")[0], {
		minChars: 0,
		maxItems: 15,
		autoFirst: true,
		filter: function(text, input) {
			return Awesomplete.FILTER_CONTAINS(text, input.match(/[^,]*$/)[0]);
		},

		item: function(text, input) {
			return Awesomplete.ITEM(text, input.match(/[^,]*$/)[0]);
		},

		replace: function(text) {
			var before = this.input.value.match(/^.+,\s*|/)[0];
			this.input.value = before + text + ", ";
		}
	});

	// Delayed update function
	$(document).on('input', '#fuzzysearch', function() {
		if(last_value == $('#fuzzysearch').val().trim()){
			return;
		}
		
		clearTimeout(timer);
		timer = setTimeout(fuzzy_grab, 300);
		
		last_value = $('#fuzzysearch').val().trim();
	});

	// Grab suggested tags
	function fuzzy_grab(){
		var search = $("#fuzzysearch").val();

		if(search.trim().slice(-1) == "," || search == ""){
			fuzzy_update();
			return;
		}
		
		jQuery.ajax({
			type: "POST",
			url: '/include/action/fuzzytags.inc.php',
			dataType: 'json',
			data: {fuzzytags: search},

			// Returned JSON suggestions
			success: function (obj) {
				if( ('results' in obj)) {
					fuzzy_update(obj['results']);
				}
			}
		});
	}

	// Update the tag list
	function fuzzy_update(results = []){
		console.log(results);
		awesomeplete.list = results;
	}
</script>
