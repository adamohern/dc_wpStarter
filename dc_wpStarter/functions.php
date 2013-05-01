<?php

/*
// require all .php files in the 'require' directory
*/
foreach(glob(get_stylesheet_directory()."/require/*.php") as $file){ require $file; }



/*
// turn on extra Wordpress goodies
*/
add_theme_support( 'post-formats', array( 'video','status','quote','status','aside' ) );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );
add_image_size( 'dc_thumbnail', 256, 144, true ); // 256 x 144, hard crop mode
add_image_size( 'dc_large', 720, 405, true ); // 720 x 405, hard crop mode
add_image_size( 'dc_huge', 960, 540, true ); // 960 x 540, hard crop mode




/*
// pull up the options into an array rather than grabbing them piecemeal from mySQL improves performance
*/
global $dc_options;
$dc_options = get_option( 'dc_options' );




/*
// load up our external scripts
*/
function dc_loadScripts() {
	global $dc_options;
	
	if (!is_admin()) {  
        
        // start with the basics
        dc_enqueue_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js' );
        dc_enqueue_script( 'jqueryui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js', array('jquery') );
        dc_enqueue_script( 'dc_functions', get_bloginfo('template_url').'/js/dc_functions.js', array('jquery'), '0', true );
        
        if( $dc_options['jqueryui_theme'] ) dc_enqueue_style('jqueryui_style',$dc_options['jqueryui_theme']);
        
        // load up any custom scripts from the theme options
        dc_enqueue_scripts($dc_options['headerJS']);
        dc_enqueue_scripts($dc_options['footerJS'],true);
        
	}
}
add_action('init', 'dc_loadScripts');




/*
// only load Ace as needed
*/
function enqueue_ace(){
    dc_enqueue_script( 'ace', '//d1n0x3qji82z53.cloudfront.net/src-min-noconflict/ace.js' );
}

?>