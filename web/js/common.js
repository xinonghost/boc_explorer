$(document).ready(function() {

	//Preloader
	$('#preloader').fadeOut('slow',function(){$(this).remove();});

	// Img
	$("img, a").on("dragstart", function(event) { event.preventDefault(); });

	// MatchHeight
	$('').matchHeight();

	// Popup
	$('.call-popup').magnificPopup({
		type:"inline",
        mainClass: 'mfp-fade',
        showCloseBtn: true,
        closeBtnInside: false,
        removalDelay: 300
	});

	// Close Button
    $('.close-button').click(function() {
		$.magnificPopup.close();
	});

    // Search 
	$("#searchInput").focus(function() {
		$(this).parent('.input-group').find('.search-button').addClass('on');
	});

	$("#searchInput").focusout(function() {
		$(this).parent('.input-group').find('.search-button').removeClass('on');
	});

});

