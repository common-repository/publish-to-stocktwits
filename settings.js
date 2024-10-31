var ajaxurl = ajaxurl;
jQuery('#connect').live('click', function() {
    window.open(jQuery(this).attr('href'), 'stocktwits_oauth', 'width=500,height=550');
    return false;                                                                        
  });

jQuery(document).ready(function() { 
  	var stAccessToken = jQuery('#access_token_input').val();
  	
  	if(stAccessToken == "" || false){
  		getStAccessToken();  
  	}
  	jQuery('#connect').attr("href", 'https://api.stocktwits.com/api/2/oauth/authorize?client_id=' + stAccessToken + '&response_type=token&redirect_uri=' +blogurl+'/wp-admin/options-general.php?page=publish-to-stocktwits/publish_stocktwits.php&scope=read,watch_lists,publish_messages');
  	
	if (/access_token=(\w+)/.test(window.location.hash)) {
      	var accessToken = window.location.hash.match(/access_token=(\w+)/)[1]; 
      	jQuery.getJSON("https://api.stocktwits.com/api/2/account/verify.json?callback=?", { access_token: accessToken }, function(data) {                                      
          if (data.user) { 
          		sendUser = {'action': 'st_user',
          				'security' : nonce,
          				'user_id' : currentUser,
          				'st_username': data.user.username,
          				'st_user_cliant_id': accessToken 
          				};
					
          		jQuery.post(ajaxurl, sendUser, function(data){
					if(data){
						window.opener.location.reload(true);
						window.close();
						}
				});
					
          } 
      });  
    }

  	jQuery('#test').click(function(){ //test code remove 
		//console.log(jQuery('#checkdefault').prop('checked'));
		data = {'action': 'st_user',
          		'security' : nonce,
          		'user_id' : currentUser,
          		'st_username': 'DanPfeifer',
          		'st_user_cliant_id': 'magical number'
          		};
          			 		
    	jQuery.post(ajaxurl, data, function (data){
				console.log(data);
				}, 'json' ); 
	}); // end click

					
	jQuery(".stUser button").click(function(){
			removeUser(this);
			jQuery(this).parent().remove();
			
	});  //end click

	jQuery('#submit').click(function(){
		if( jQuery('#access_token_input').val() != 0){
			data = {'action': 'st_auth',
					'security': nonce,
					'set_stocktwits_auth': jQuery('#access_token_input').val(),
					'stocktwits_checked' : jQuery('#checkdefault').prop('checked')
					}
		jQuery.post(ajaxurl, data, function(data){
			
			stAccessToken = jQuery('#access_token_input').val();
			jQuery('#connect').attr("href", 'https://api.stocktwits.com/api/2/oauth/authorize?client_id=' + stAccessToken + '&response_type=token&redirect_uri=' +blogurl+'/wp-admin/options-general.php?page=publish-to-stocktwits/publish_stocktwits.php&scope=read,watch_lists,publish_messages');
			feedbackMessage('Your Consumer Key has been updated');
			});
		} else {
			feedbackMessage('Please enter a Consumer Key');
		}
	}); // end submit
	


	jQuery('.thumb').hover(function(){
		jQuery(this).addClass('hover');
	}, function(){
		jQuery(this).removeClass('hover');
	});
});   // end ready

function feedbackMessage(message){
		feedbackMessages = document.getElementById('feedbackMessages');
		feedbackMessages.setAttribute('class', 'active');
		content = document.createTextNode(message);
		feedbackMessages.appendChild(content);
		setTimeout(function(){
			feedbackMessages.setAttribute('class', 'inactive');
		}, 1450);
		setTimeout(function(){
			feedbackMessages.removeChild(feedbackMessages.firstChild);
		}, 1750);
	
}

function getStAccessToken(){
      		 data = {'action': 'st_auth',
					'security': nonce,
					'get_stocktwits_auth': 'auth',
					'set_stocktwits_auth': jQuery('#access_token_input').val()
					};
  	jQuery.post(ajaxurl, data, function(data){
  		var cliantId = jQuery('#access_token_input').val(data);
  		cliantId = jQuery(this).text().replace(/ /g, '');
  		stAccessToken = cliantId;
  		jQuery('#connect').attr("href", 'https://api.stocktwits.com/api/2/oauth/authorize?client_id=' + stAccessToken + '&response_type=token&redirect_uri=' +blogurl+'/wp-admin/options-general.php?page=publish-to-stocktwits/publish_stocktwits.php&scope=read,watch_lists,publish_messages');
  		});
} 



function removeUser(e){

	data = {'action': 'st_remove_user',
			'security' : nonce,
			'wp_id': e.id,
			'st_username' : e.value
			}
			
	jQuery.post(ajaxurl, data, function(data){
		
		});
}
