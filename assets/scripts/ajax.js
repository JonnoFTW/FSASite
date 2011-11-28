$.ajaxSetup({
    cache: false
});
jQuery(document).ready(function() {
	jQuery.ajax({
		url: "ajax.php",
		type: "POST",
		data: {page : 'main'},
		success: function(data) {
			jQuery('#content').html(data).hide().fadeIn(1500);
			},
	});
	$(".link").live("click", function() {
		var title = $(this).attr("href");
		// Take the GET out of the href as well and pass it through
		var url = "ajax.php";
		var get = title.split("?")[1];
		jQuery.ajax({
			url: url+'?'+get,
			type: "POST",
			data: {page : title},
			success: function(data) {
				jQuery('#content').html(data).hide().fadeIn(1500);
				},
			});
		return false;
	});
});