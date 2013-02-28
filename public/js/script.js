$(document).ready(function(){
	$('.signaler_popup').css('left',(($(window).width()/2)-($('.signaler_popup').width()/2))+'px');
	$('.signaler').click(function() {
		$('.signaler_popup').show().animate({'opacity':'1'});
	});
	$('.signaler_popup .croix').click(function() {
		$('.signaler_popup').animate({'opacity':'0'}, function() {
			$(this).hide();
		});
	});
	if(typeof(id_user) != 'undefined') {
		setInterval(function() {
			$.ajax(
			{
				type: 'POST',
				url:  site_url+'api.php',
				data: 'nbrN='+id_user,
				success: function(data)
				{
					console.log('success');
					if(data!=0) {
						$('.notifications').css('font-weight','bold').css('color','red');
					}
					if(!isNaN(data)) {
						$('.notifications').html(data+' notifications');
					}
					
				}
			});
			$.ajax(
			{
				type: 'POST',
				url:  site_url+'api.php',
				data: 'nbrM='+id_user,
				success: function(data)
				{
					console.log('success');
					if(data!=0) {
						$('.messages').css('font-weight','bold').css('color','red');
					}
					if(!isNaN(data)) {
						$('.messages').html(data+' messages');
					}
					
				}
			});
		},1000);

		$('.notifications').click(function() {
			$.ajax(
			{
				type: 'POST',
				url:  site_url+'api.php',
				data: 'id='+id_user,
				success: function(data)
				{
					$('.notifications_content').html(data);
					$('.notifications').css('font-weight','normal').css('color','black');
				}
			});
			$('.notifications_popup').show().animate({'opacity':'1'});
		});
		$('.notifications_popup .croix').click(function() {
			$('.notifications_popup').animate({'opacity':'0'}, function() {
				$(this).hide();
			});
		});
	}
});