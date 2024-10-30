<?php
/*
Plugin Name: Milwaukee Beer Society Beer List

Description: A beer list that pulls data from Untappd.
Version:     1.3.1
Author:      Steve Walter
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/


register_activation_hook(__FILE__, 'bl_set_default_settings');
// Define default option settings
function bl_set_default_settings() {
    $arr = array("ut_clientid"=>"", "ut_clientsecret" => "", "bl_numberofbeers" => 8);
    update_option('bl_settings', $arr);
}

function create_menu(){
	//wp_localize_script('main','blah',array('meh'));
	add_options_page('MBS Beer List', 'MBS Beer List', 'manage_options', 'item-list-settings', 'create_options');
}
function create_bl_event_menu(){
	add_submenu_page('edit.php?post_type=tribe_events','Add New Item Tasting', 'Add New Item Tasting', 'edit_posts', 'post_new.php?post_type=item_list', 'bl_create_item_list_page');
}
function create_options(){
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Milwaukee Beer Society Beer List Settings</h2>
		Specify the API Keys given to you by Untappd.
		<form action="options.php" method="post">
		<?php settings_fields('bl_settings'); ?>
		<?php do_settings_sections(__FILE__); ?>
		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
		</p>
		</form>
	</div>

<?php
	
	//echo '<div class="bl_settings_form">';
	//echo '<p>Here is where the form would go if I actually had options.</p>';
	//echo '</div>';
	//wp_enqueue_script('settings', plugins_url( 'settings.js', __FILE__), array('jquery'));
	//wp_localize_script('settings','wp_data',array( plugins_url( 'settings.html', __FILE__)));
}

function bl_create_item_list_page(){
	if ( !current_user_can( 'edit_posts' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Item List with Untappd Event</h2>
		List the items you would like included
		<form action="" method="post">
		<?php
		for ($x = 0; $x < 8; $x++){
			echo '<input type="text" class="item autocomplete"></input>' .
				 '<input type="text" class="item_code"></input>' .
				 '<br/>';
		}
		add_action( 'admin_footer', 'bl_create_item_list_page_init' ); 
		?>
		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php 
			esc_attr_e('Post'); 
			?>" />
		</p>
		</form>
	</div>

<?php
}

function bl_post_event($button_text){
	echo 'aaaaaaaaaaaaaaaaaaaaaaaaaaaa';
}

function bl_settings_init(){
	register_setting('bl_settings', 'bl_settings', 'bl_plugin_validate' );
	add_settings_section('untappd_api', 'Untappd API', 'bl_section_callback', __FILE__);
	add_settings_field('ut_clientid', 'Untappd Client ID', 'bl_utclientid_callback', __FILE__, 'untappd_api');
	add_settings_field('ut_clientsecret', 'Untappd Client Secret', 'bl_utclientsecret_callback', __FILE__, 'untappd_api');
	add_settings_field('bl_numberofbeers', 'Default Number of Beers', 'bl_numberofbeers_callback', __FILE__, 'untappd_api');
	//add_settings_field('plugin_text_pass', 'Password Text Input', 'setting_pass_fn', __FILE__, 'main_section');
	//add_settings_field('plugin_textarea_string', 'Large Textbox!', 'setting_textarea_fn', __FILE__, 'main_section');
	//add_settings_field('plugin_chk2', 'A Checkbox', 'setting_chk2_fn', __FILE__, 'main_section');
	//add_settings_field('radio_buttons', 'Select Shape', 'setting_radio_fn', __FILE__, 'main_section');
	//add_settings_field('drop_down1', 'Select Color', 'setting_dropdown_fn', __FILE__, 'main_section');
	//add_settings_field('plugin_chk1', 'Restore Defaults Upon Reactivation?', 'setting_chk1_fn', __FILE__, 'main_section');
	
	//add_settings_field('bl_apikey', 'Untappd API Key', 'create_setting_apikey', 'general', 'default', array('bl_apikey') );
	//register_setting('default','bl_apikey');
}

function bl_plugin_validate($input) {
	// Check our textbox option field contains no HTML tags - if so strip them out
	$input['ut_clientid'] =  wp_filter_nohtml_kses($input['ut_clientid']);	
	$input['ut_clientsecret'] =  wp_filter_nohtml_kses($input['ut_clientsecret']);	
	$input['bl_numberofbeers'] =  wp_filter_nohtml_kses($input['bl_numberofbeers']);	
	return $input; // return validated input
}

function bl_section_callback(){
	
}

function bl_utclientid_callback(){
	$options = get_option('bl_settings');
	echo "<input id='ut_clientid' name='bl_settings[ut_clientid]' size='48' type='text' value='{$options['ut_clientid']}' />";
}

function bl_utclientsecret_callback(){
	$options = get_option('bl_settings');
	echo "<input id='ut_clientsecret' name='bl_settings[ut_clientsecret]' size='48' type='text' value='{$options['ut_clientsecret']}' />";
}

function bl_numberofbeers_callback(){
	$options = get_option('bl_settings');
	echo "<input id='bl_numberofbeers' name='bl_settings[bl_numberofbeers]' size='2' type='text' value='{$options['bl_numberofbeers']}' />";
}

add_action('admin_menu','create_menu');
//add_action('admin_menu','create_bl_event_menu');
add_action('admin_init','bl_settings_init');

add_action('admin_footer', 'bl_create_item_list_page_init');
add_action('wp_footer', 'bl_event_post_init');

function bl_create_item_list_page_init() {
	$options = get_option('bl_settings');
	wp_enqueue_style('bl_create_page', plugins_url( 'bl_create_page.css', __FILE__));
	wp_enqueue_script('set_utautocomplete', plugins_url( 'set_utautocomplete.js', __FILE__), array('jquery-ui-autocomplete', 'jquery-ui-widget'));
	wp_localize_script('set_utautocomplete','bl_settings',array('options' => $options, 'beerDefaultImage' => plugins_url('beer_default.png',__FILE__)));

	?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {

		var data = {
			'action': 'my_action',
			'whatever': 1234
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		$.post(ajaxurl, data, function(response) {
			//alert('Got this from the server: ' + response);
		});
	});
	</script> <?php
}

function bl_event_post_init(){
	$options = get_option('bl_settings');
	wp_enqueue_script('post_load', plugins_url( 'post_load.js', __FILE__));
}

add_action( 'wp_ajax_my_action', 'my_action_callback' );

function my_action_callback() {
	global $wpdb; // this is how you get access to the database

	$whatever = intval( $_POST['whatever'] );

	$whatever += 10;

        echo $whatever;

	wp_die(); // this is required to terminate immediately and return a proper response
}

function bl_beer_css() {
	wp_register_style( 'bl_beer_item', plugins_url('bl_beer_item.css', __FILE__) );
	wp_enqueue_style( 'bl_beer_item', get_stylesheet_uri() );
}

add_action( 'wp_enqueue_scripts', 'bl_beer_css', 15 );

function list_hooked_functions($tag=false){
 global $wp_filter;
 if ($tag) {
  $hook[$tag]=$wp_filter[$tag];
  if (!is_array($hook[$tag])) {
  trigger_error("Nothing found for '$tag' hook", E_USER_WARNING);
  return;
  }
 }
 else {
  $hook=$wp_filter;
  ksort($hook);
 }
 echo '<pre>';
 foreach($hook as $tag => $priority){
  echo "<br /><strong>$tag</strong><br />";
  ksort($priority);
  foreach($priority as $priority => $function){
  echo $priority;
  foreach($function as $name => $properties) echo "t$name<br />";
  }
 }
 echo '</pre>';
 return;
}

?>