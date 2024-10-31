<?php
function stocktwits_message_box($post){ 
	echo '<link type="text/css" href="' . plugins_url( 'styles.css', __FILE__ ) . '" rel="stylesheet"></link>';
	echo '<script src="' . plugins_url( 'post.js', __FILE__ ) . '"></script>';
	$security = wp_create_nonce( 'test' );
	echo '<script> var nonce = "' . $security . '";' . "\n";
	echo 'var shorturl = "' . wp_get_shortlink() .'";' . "\n";
	echo 'var blogurl = "' . get_site_url() . '"; </script>' . "\n";
	$security = wp_create_nonce( 'post' );
	 if(get_option('stocktwits_auth_token') == ''){ ?>
	 
	 <div class="updated">
        <p>Before you can use Publish to StockTwits, you must register your Wordpress blog  with <a href="https://stocktwits.com/developers/apps/new" target="_new">StockTwits</a>.</p>
    </div>
<?php }
	if(get_post_meta(get_the_ID() , 'st_tweeted') == true){ ?>
	 <div class="updated">
        <p>This post has already be published to StockTwits</p>
    </div>
    <?php  } ?>
 	<div id="holder">
		<form>
			<textarea id="twitbox" rows="4" name='twitbox' ><?php echo wp_get_shortlink(); ?></textarea><br>
			
		</form>
	
		<div class="symbolArrow"></div>
			<div id='symbolSelector' class='inactive'>
				<div id='symbolBox'></div>
				<p id='dismiss'>cancel</p>
				<input class="button" id="addTicker" type="button" value="Add"></input>
		</div>
		<p id="counter" class="counterDisplay">140</p>
		<p class="counterDisplay">Characters</p>
		<p id="error"></p>
	</div>
	<div id="stUsers">
	<p>Choose an Account</p>
		<?php 
		global $wpdb;
		echo "<div><p>";

		global $wpdb;
		$table_name = $wpdb->prefix . 'usermeta';
		$st_users = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE meta_key ='st_username'");
		foreach($st_users as $st_username){
			echo "<input class='userChecked' name='userChecked[]' value='". $st_username->meta_value . "'type='checkbox'";
			if(get_option('stocktwits_checked') == 'true' && $st_username->user_id == get_current_user_id() ){
			echo "checked='checked'";
			}
			$temp = unserialize($st_username->meta_value);
			echo ">" . $temp['username'] . "<br/>";
				
			}

		
			echo "</p><p><a href='" . get_site_url() . "/wp-admin/options-general.php?page=publish-to-stocktwits/publish_stocktwits.php'>Add StockTwits User</a>";
			echo "</p></div>";
		?>
		<input type="hidden" name="sendPost" id="sendPost" value="140" />
	</div>
<?php }
