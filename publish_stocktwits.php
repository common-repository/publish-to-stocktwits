<?php
/*
Plugin Name: Publish to StockTwits 
Plugin URI: http://stocktwits.com
Description: Submit posts or excerpts to StockTwits automatically.
Version: 1.1
Author: stocktwits
Author URI: http://stocktwits.com
*/


include_once('st_post.php');
include_once ('st_settings.php');
//----- plugin function  --------

// install function creates options 
function publish_to_stocktwits_install() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'usermeta';
	$wpdb->query('DELETE FROM ' . $table_name . ' WHERE meta_key ="st_username" OR meta_key = "st_user_cliant_id";');
  add_option('stocktwits_auth_token', '');
  add_option('stocktwits_checked', 'true');
}
// adds options link in admin 
function publish_to_stocktwits_admin_menu() {
  add_options_page('Publish to StockTwits Options', 'Publish to StockTwits', 10, __FILE__, 'publish_to_stocktwits_options_page');
}

//adds text box to post page
function stocktwits_box(){

	add_meta_box('St_share', 'StockTwits Message', 'stocktwits_message_box', 'post', 'side', 'high', $post);
}


// publish message to StockTwits 
function st_publish_message($post_ID) { 
	if(get_post_status($post_ID) == 'private'){
		return;
	}
	 if($_POST['sendPost']){
		
		if($_POST['sendPost'] <= 140){
			$message = $_POST['twitbox'];
			$st_usernames = $_POST['userChecked'];
			global $wpdb;
			$table_name = $wpdb->prefix . 'usermeta';
			if(!($st_usernames)){return;}
			if(get_post_meta($post_ID, 'st_tweeted') == false){
				 foreach($st_usernames as $st_user){
					$my_row = $wpdb->get_row("SELECT * FROM ". $table_name ." WHERE meta_key = 'st_username' AND meta_value LIKE '%" . $st_user . "%';", ARRAY_A);
					$results = unserialize($my_row['meta_value']);
				
										$url = 'https://api.stocktwits.com/api/2/messages/create.json';
					
					$myvars = array('access_token' => $results['st_user_cliant_id'],
				 			'body' => $message);

					
					

					$ch = curl_init( $url );
						curl_setopt( $ch, CURLOPT_POST, 1);
						curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
						curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
						curl_setopt( $ch, CURLOPT_HEADER, 0);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						$response = curl_exec( $ch );
						$errors = curl_error($ch);

						curl_close($ch); 
				} 
			update_post_meta($post_ID, 'st_tweeted', 'true');
			}
			
		}
	} 
}


// updates site auth token and send back new token
function st_auth_connect(){  
	check_ajax_referer('test', 'security');
	global $wpdb;
	if(isset($_POST['set_stocktwits_auth'])){
		$stocktwits_auth = mysql_real_escape_string($_POST['set_stocktwits_auth']);
		if ($stocktwits_auth != ''){
		update_option('stocktwits_auth_token', $stocktwits_auth);
		}
	if(isset($_POST['stocktwits_checked'])){
		$stocktwits_checked = $_POST['stocktwits_checked'];
		update_option('stocktwits_checked', $stocktwits_checked);
	}
		echo get_option('stocktwits_auth_token');
		die();
	}
	if(isset($_POST['get_stocktwits_auth'])){
		if($_POST['get_stocktwits_auth'] == 'auth'){
		echo get_option('stocktwits_auth_token');
		die();
		}
	}

}
 // updates user token and returns new token json encoded
 
function st_user_connect(){
	check_ajax_referer('test', 'security');
	if(isset($_POST['st_username']) && isset($_POST['st_user_cliant_id'])){
		global $wpdb;
		$st_username = mysql_real_escape_string($_POST['st_username']);
		$st_user_cliant_id = mysql_real_escape_string($_POST['st_user_cliant_id']);
		$user_id = $_POST['user_id'];
		$user_data = array('username'=> $st_username, 'st_user_cliant_id' => $st_user_cliant_id);

			add_user_meta($user_id, 'st_username', $user_data);
			echo 'true';
		

		
	}
	die();
}


// removes stocktwits user from site, does not remove from StockTwits

function st_remove_selected_user(){ 
	check_ajax_referer('test', 'security');
	if(isset($_POST['wp_id']) && isset($_POST['st_username'])){
		$user_id = $_POST['wp_id'];
		$st_username = mysql_real_escape_string($_POST['st_username']);
		global $wpdb;
		$table_name = $wpdb->prefix . 'usermeta';
		$delete = $wpdb->query('DELETE FROM ' . $table_name . ' WHERE meta_key ="st_username" AND meta_value LIKE "%' . $st_username . '%" limit 1;');
		echo 'deleted ' . $st_username;

	}
	die();
}

// addes Javascript to tinymce text editor 

function wpse24113_tiny_mce_before_init( $initArray )
{
    $initArray['setup'] = <<<JS
[function(ed) {
    ed.onInit.add(function(ed, evt) {
        tinymce.dom.Event.bind(ed.getDoc(), 'blur', function(e) {
            searchSymbols();
        });

});

}][0]
JS;
    return $initArray;
}

// wordpress hooks 

register_activation_hook(__FILE__, 'publish_to_stocktwits_install');
add_action('admin_menu', 'publish_to_stocktwits_admin_menu');
add_action('simple_edit_form', 'auto_tweet_form', 1);
add_action('publish_post', 'st_publish_message');
add_action('add_meta_boxes', 'stocktwits_box');

// ajax hooks 

add_action('wp_ajax_st_user', 'st_user_connect');
add_action('wp_ajax_st_auth', 'st_auth_connect');
add_action('wp_ajax_st_remove_user', 'st_remove_selected_user');

add_filter( 'tiny_mce_before_init', 'wpse24113_tiny_mce_before_init' );


?>