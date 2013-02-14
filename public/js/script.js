$(document).ready(function(){
	$('.signaler_popup').css('left',(($(window).width()/2)-($('.signaler_popup').width()/2))+'px');
	$('.signaler').click(function() {
		$('.signaler_popup').show().animate({'opacity':'1'});
	})
	$('.signaler_popup .croix').click(function() {
		$('.signer_popup').animate({'opacity':'0'}, function() {
			$(this).hide();
		});
	});
});