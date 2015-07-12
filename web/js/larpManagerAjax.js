$(document).ready(function() {
	$('a.ajax_action','#ajax_entity').on("click", function(event) {
		$('#ajax_result').fadeOut()
		$.ajax({
			url: $(this).attr("href"), 
			context: document.body
		}).done(function(data) {
			$('#ajax_result').html(data);
			$('#ajax_result').fadeIn()
		});
		return false;
	});
});
