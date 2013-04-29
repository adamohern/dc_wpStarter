<?php

/*
// require all .php files in the 'require' directory
*/
foreach(glob(get_stylesheet_directory()."/require/*.php") as $file){ require $file; }




add_theme_support( 'post-formats', array( 'video','status','quote','status','aside' ) );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );
add_image_size( 'dc_thumbnail', 256, 144, true ); // 256 x 144, hard crop mode
add_image_size( 'dc_large', 720, 405, true ); // 720 x 405, hard crop mode
add_image_size( 'dc_huge', 960, 540, true ); // 960 x 540, hard crop mode





global $dc_options;
$dc_options = array(
	'homeListContent'=>			dc_option('homeListContent'),
	'displayThumb_archive'=>    dc_option('displayThumb_archive'),
	'displayThumb_single'=>		dc_option('displayThumb_single'),
	'mainSidebar'=>				dc_option('mainSidebar'),
	'primaryColor'=>			dc_option('primaryColor'),
	'primaryColorFaded'=>		dc_option('primaryColorFaded'),
	'selectionColor'=>			dc_option('selectionColor'),
	'selectionTextColor'=>		dc_option('selectionTextColor'),
	'shortMeta'=>				dc_option('shortMeta'),
	'longMeta'=>			    dc_option('longMeta'),
	'cssOverrides'=>			dc_option('cssOverrides'),
	'jqueryui_theme'=>			dc_option('jqueryui_theme')
);





// Load up jquery from Google API (easier to keep updated), along with jqueryUI and pixedelic camera
function dc_loadScripts() {
	global $dc_options;
	
	if (!is_admin()) {  
		wp_deregister_script( 'jquery' );  
		wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js');  
		wp_enqueue_script('jquery');  
		wp_register_script('dc_functions', get_bloginfo('template_url').'/js/dc_functions.js', array('jquery'), '1.0', true );  
		wp_enqueue_script('dc_functions');
		wp_register_script('jqueryui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js');
		wp_enqueue_script('jqueryui');
		if($dc_options['jqueryui_theme']){
			wp_register_style('jqueryui_style',$dc_options['jqueryui_theme']);
			wp_enqueue_style('jqueryui_style');
		}
	}
}
add_action('init', 'dc_loadScripts');





function enqueue_ace() {
	wp_register_script( 'ace', 'http://d1n0x3qji82z53.cloudfront.net/src-min-noconflict/ace.js' );
	wp_enqueue_script( 'ace' );
}


?>
