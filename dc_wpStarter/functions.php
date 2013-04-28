<?php
require_once 'functions/evd_adminPanel.php';
require_once 'functions/evd_utilities.php';
require_once 'functions/evd_wpCleanup.php';
require_once 'functions/evd_postmeta.php';
require_once 'functions/evd_shortcodes.php';
require_once 'functions/evd_sidebars.php';
require_once 'functions/evd_widgets.php';
require_once 'functions/evd_postsList.php';
require_once 'functions/evd_renderposts.php';
require_once 'functions/evd_googleAuthorship.php';

add_theme_support( 'post-formats', array( 'video','status','quote','status','aside' ) );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );
add_image_size( 'evd_thumbnail', 256, 144, true ); // 256 x 144, hard crop mode
add_image_size( 'evd_large', 720, 405, true ); // 720 x 405, hard crop mode
add_image_size( 'evd_huge', 960, 540, true ); // 960 x 540, hard crop mode

global $evd_options;
$evd_options = array(
	'evd_homeListContent'=>			evd_option('evd_homeListContent'),
	'evd_displayThumb_archive'=>	evd_option('evd_displayThumb_archive'),
	'evd_displayThumb_single'=>		evd_option('evd_displayThumb_single'),
	'evd_mainSidebar'=>				evd_option('evd_mainSidebar'),
	'evd_primaryColor'=>			evd_option('evd_primaryColor'),
	'evd_primaryColorFaded'=>		evd_option('evd_primaryColorFaded'),
	'evd_selectionColor'=>			evd_option('evd_selectionColor'),
	'evd_selectionTextColor'=>		evd_option('evd_selectionTextColor'),
	'evd_shortMeta'=>				evd_option('evd_shortMeta'),
	'evd_longMeta'=>				evd_option('evd_longMeta'),
	'evd_cssOverrides'=>			evd_option('evd_cssOverrides'),
	'evd_jqueryui_theme'=>			evd_option('evd_jqueryui_theme')
);

// Load up jquery from Google API (easier to keep updated), along with jqueryUI and pixedelic camera
function evd_loadScripts() {
	global $evd_options;
	
	if (!is_admin()) {  
		wp_deregister_script( 'jquery' );  
		wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js');  
		wp_enqueue_script('jquery');  
		wp_register_script('evd_functions', get_bloginfo('template_url').'/js/evd_functions_121004_01.js', array('jquery'), '1.0', true );  
		wp_enqueue_script('evd_functions');
		wp_register_script('jqueryui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js');
		wp_enqueue_script('jqueryui');
		if($evd_options['evd_jqueryui_theme']){
			wp_register_style('jqueryui_style',$evd_options['evd_jqueryui_theme']);
			wp_enqueue_style('jqueryui_style');
		}
		wp_register_script('pixedelic_camera', get_bloginfo('template_url').'/js/camera.min.js', array('jquery'), '1.0', true );  
		wp_enqueue_script('pixedelic_camera');
		wp_register_style('pixedelic_camera_style', get_bloginfo('template_url').'/js/camera.css');
		wp_enqueue_style('pixedelic_camera_style');
	}
}
add_action('init', 'evd_loadScripts');

function enqueue_ace() {
	wp_register_script( 'ace', 'http://d1n0x3qji82z53.cloudfront.net/src-min-noconflict/ace.js' );
	wp_enqueue_script( 'ace' );
}

// Now that we've loaded everything up, run whatever the user's defined in Theme Option > Custom PHP
eval (evd_option('evd_customPHP'));

?>
