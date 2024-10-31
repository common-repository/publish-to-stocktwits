<?php
//options page 
function publish_to_stocktwits_options_page() { 

	echo '<link type="text/css" href="' . plugins_url( 'styles.css', __FILE__ ) . '" rel="stylesheet"></link>';
	echo '<script src="' . plugins_url( 'settings.js', __FILE__ ) . '"></script>';
	$security = wp_create_nonce( 'test' );
	echo '<script> var nonce = "' . $security . '"; ' . "\n";
	echo 'var currentUser = ' . get_current_user_id() . '; ' . "\n";
	echo 'var blogurl = "' . get_site_url() . '"; </script>' . "\n";
	add_thickbox();
	$options = get_option("auto_tweet_options");
?>
<div id="feedbackMessages" class="inactive"></div>
<div class="wrap">
	
	<?php if(get_option('stocktwits_auth_token') == ''){ ?>
	 <div class="updated">
        <p>Before you can use Publish to StockTwits, you must register your Wordpress blog  with <a href="https://stocktwits.com/developers/apps/new" target="_new">StockTwits</a>.</p>
    </div>
<?php } ?>
	<!--  <div id="test">test</div>-->
	<h2>Publish to StockTwits</h2>
	<div id="boxLeft">
	<p>Publish to StockTwits will allow you to automatically publish your blog to StockTwits with every new post. Include a link, the title and even a custom message (total message must be 140 characters or less).</p>
	<div id="test">test</div>
	<p><a id="connect"><img src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/st-connect.png"/></a></p> 

	<h3>StockTwits Users</h3> 
	<?php
	
	echo '<ul id="userList">';
	global $wpdb;
	$table_name = $wpdb->prefix . 'usermeta';

	$st_users = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE meta_key ='st_username'");
	//$st_users = unserialize($st_user);


		
		foreach($st_users as $st_username){
		$temp = unserialize($st_username->meta_value);
			echo "<li class='stUser' id='" .  $st_username->user_id . "' ><strong>" .  $temp['username'] . "</strong><button type='button' value='" . $temp['username'] ."' id='". $st_username->user_id ."'>Remove</button></il>";
		}
	echo '</ul>';

 ?>
 	<p>Before you can use Publish to StockTwits, you must register your Wordpress blog  with <a href="https://stocktwits.com/developers/apps/new" target="_new">StockTwits</a>.
	</p> 
	<h3>Consumer Key:</h3>
	<p><input id="access_token_input" name="access_token_input" type="text" size="30" value="<?php echo get_option('stocktwits_auth_token'); ?>" style="font-family: 'Courier New', Courier, Mono; font-size: 1.25em;" /><br />
	<p></p>
    <p>Should the Publish to StockTiwts checkbox on the Write Post form be checked by default?</p>
    <p><input id="checkdefault" name="checkdefault" type="checkbox"<?php if (get_option('stocktwits_checked') == "true") echo ' checked="checked"'; ?> /> <label for="checkdefault">Yes, Send my posts to StockTwits.</label>
    </p>
    <p class="submit" style="text-align: left;"><input type="button" id="submit" name="submit" value="Update" />
	</p>
	</div>
	<div id="boxRight">
		<h3>How to register your Site with StockTwits</h3>
		<p>To register your app with StockTwits, visit <a href="https://stocktwits.com/developers/apps/new" target="_new">StockTwits Developer Portal</a>. Here you can register an manage your your apps. Follow the directions below to get started.
		<ol id="authHelp">
			<li><p>Let us know what to call your Blog. This is what users will see when you send your posts to StockTwits.</p><div id="screen1" style="display:none;">
     <p>
          <img src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/screen1.png" />
     </p>
</div>

<a href="#TB_inline?width=635&height=600&inlineId=screen1" class="thickbox"><img class="thumb" src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/thumb1.png"></a>	</li>
			<li><p>Let us know a little about your Blog</p><div id="screen2" style="display:none;">
     <p>
          <img src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/screen2.png" />
     </p>
</div>

<a href="#TB_inline?width=635&height=600&inlineId=screen2" class="thickbox"><img class="thumb" src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/thumb2.png"></a>	</li>
			<li><p>Enter in the domain name for your site. This is shown below:<br>
			your domain is: <strong><?php echo preg_replace('/http\:\/\/|https\:\/\//',' ', home_url()); ?></strong><br></p><div id="screen3" style="display:none;">
     <p>
          <img src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/screen3.png" />
     </p>
</div>

<a href="#TB_inline?width=635&height=600&inlineId=screen3" class="thickbox"><img class="thumb" src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/thumb3.png"></a>	</li>
			<li><p>Let us know the full domain of your site.<br>
			your full domain is: <strong><?php echo home_url(); ?></strong></p><div id="screen4" style="display:none;">
     <p>
          <img src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/screen4.png" />
     </p>
</div>

<a href="#TB_inline?width=635&height=600&inlineId=screen4" class="thickbox"><img class="thumb" src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/thumb4.png"></a>	</li>
			<li><p>Skip the DeAuthorization URL your blog will not use this.</p></li>
			<li><p>Upload an optional avatar for your site. </p><div id="screen5" style="display:none;">
     <p>
          <img src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/screen5.png" />
     </p>
</div>

<a href="#TB_inline?width=700&height=550&inlineId=screen5" class="thickbox"><img class="thumb" src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/thumb5.png"></a></li>
			<li><p>Agree to our Terms of Service and click Register your StockTwits Application.<div id="screen6" style="display:none;">
     <p>
          <img src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/screen6.png" />
     </p>
</div>

<a href="#TB_inline?width=700&height=550&inlineId=screen6" class="thickbox"><img class="thumb" src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/thumb6.png"></a></p></li>
			<li><p>On the next screen, click View key &amp; details. This will take you to where you can get your Consumer Key to activate your blog</p><div id="screen7" style="display:none;">
     <p>
          <img src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/screen7.png" />
     </p>
</div>

<a href="#TB_inline?width=700&height=550&inlineId=screen7" class="thickbox"><img class="thumb" src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/thumb7.png"></a></li>
			<li><p>Copy your consumer key by hi-lighting the text, then right click and selecting copy</p><div id="screen8" style="display:none;">
     <p>
          <img src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/screen8.png" />
     </p>
</div>

<a href="#TB_inline?width=700&height=550&inlineId=screen8" class="thickbox"><img class="thumb" src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/thumb8.png"></a></li>
			<li><p>Once you have your consumer key, paste it into the Consumer Key field on your Wordpress blog, right click and select paste. Click update. </p><div id="screen9" style="display:none;">
     <p>
          <img src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/screen9.png" />
     </p>
</div>

<a href="#TB_inline?width=700&height=550&inlineId=screen9" class="thickbox"><img class="thumb" src="http://plugins.svn.wordpress.org/publish-to-stocktwits/assets/thumb9.png"></a>	</li>
			<li><p>You have now successfully set up Publish to StockTwits</p></li>
		<ol>
		
	</div>


</div>
<?php }