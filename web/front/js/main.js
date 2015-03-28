$(document).ready(function() {
	$('.lightbox').magnificPopup({type:'image'});

	var height = $('footer').height();
	$('body').css({
	    "margin-bottom": height
	});
});