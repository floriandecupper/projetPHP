$(document).ready(function(){

	// Facebook DEBUT
	function checkInscription() {
			FB.api('/me', function(response){
			console.log(response.email);
	        console.log(response.first_name);
	        console.log(response.last_name);
			var form = $('<form action="' + site_url + 'connexion/facebook' + '" method="post">' +
			  '<input type="hidden" name="fb_mail" value="' + response.email + '" /><input type="hidden" name="fb_id" value="' + response.id + '" /><input type="hidden" name="fb_prenom" value="' + response.first_name + '" /><input type="hidden" name="fb_nom" value="' + response.last_name + '" />' +
			  '</form>');
			$('body').append(form);
			$(form).submit();
		});
	}

function showLoginButton() {

    var button = '<fb:login-button perms="email" />';
    document.getElementById('fb-login-button').innerHTML = button;
    FB.XFBML.parse(document.getElementById('fb-login-button'));
}

function onStatus(response) {

    console.info('onStatus', response);
    if (response.status == 'connected') {
        console.info('User logged in');
        if (response.perms) {
            console.info('User granted permissions');
        }else{
            console.info('User has not granted permissions');
        }
        //document.getElementById('fb-login-button').innerHTML='';
        checkInscription();
    } else {
        console.info('User is logged out');
        showLoginButton();
    }
}
	function handleFacebook() {
 
    FB.init({appId: '159447630878612', xfbml: true, cookie: true});
    //console.info('FB.init done');
    FB.getLoginStatus(function(response) {
    	    console.info('FB.getLoginStatus');
            onStatus(response);
            FB.Event.subscribe('auth.statusChange', onStatus);
    });
}
 var sPath = window.location.pathname;
if(((typeof(id_user) == 'undefined') || (id_fb==0)) && sPath.search('connexion')==-1) {
	handleFacebook();
}



// Facebook FIN


	$('input[name="tags"], input[name="recherche"]').tagsInput({
   autocomplete_url: site_url+'tags',
   height:'19px',
   interactive:true,

   defaultText:'Ajouter un tag',
   removeWithBackspace : true,
   placeholderColor : '#666666'
});
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
				url:  site_url+'api',
				data: 'nbrN='+id_user,
				success: function(data)
				{
					console.log('success');
					// if(data!=0) {
					// 	$('.get_notifications').css('color','red');
					// }
					if(!isNaN(data)) {
						if(data!=0){
							$('.get_notifications:after').css('background-color','#D92323').css('color','white');
						}else{
							$('.get_notifications:after').css('background-color','#3EB75E').css('color','black');
						}
						$('.get_notifications').attr('data-notification',data);

					}
					
				}
			});
			$.ajax(
			{
				type: 'POST',
				url:  site_url+'api',
				data: 'nbrM='+id_user,
				success: function(data)
				{
					console.log('success');
					// if(data!=0) {
					// 	$('.messages').css('color','red');
					// }
					if(!isNaN(data)) {
						if(data!=0){
							$('.messages:after').css('background-color','#D92323').css('color','white');
						}else{
							$('.messages:after').css('background-color','#3EB75E').css('color','black');
						}
						$('.messages').attr('data-notification',data);
					}
					
				}
			});
		},1000);

		$('.notifications').click(function() {
			$.ajax(
			{
				type: 'POST',
				url:  site_url+'api',
				data: 'id='+id_user,
				success: function(data)
				{
					$('.notifications_content').html(data);
					// $('.messages').css('color','black');
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
		// $('.message').next().attr('data-hauteur',$(this).height());
		$('.message').click(function() {
			console.log($(this).next().css('height'));
			console.log('aiiight1');
			if($(this).next().css('height')!='0px') {
				console.log($(this).next().css('height'));
				$(this).next().animate({height:'0px'},500);
				console.log($(this).next().css('height'));
			}else{
				console.log($(this).next().css('height'));
				$(this).next().animate({height:'100%'},500);
				console.log($(this).next().css('height'));
			}
		});
});