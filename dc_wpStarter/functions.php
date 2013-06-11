<?php

// Enable manually as-needed. Only enable during active dev, as it's a security risk (not to mention ugly).
// ini_set('display_errors', 'On');

// require all .php files in the 'require' directory
foreach(glob(get_stylesheet_directory()."/require/*.php") as $file){ require $file; }


// loads options into a global array, making them accessible form the o() function
add_action('after_setup_theme','dc_load_options');


// turn on extra Wordpress goodies
add_theme_support( 'post-formats', array( 'video','status','quote','status','aside' ) );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );


// load up our external scripts
function dc_loadScripts() {
        
	if (!is_admin()) {  
        
        // start with the basics
        dc_enqueue_script( 'jquery', o('jquery_url') );
        dc_enqueue_script( 'jqueryui', o('jqueryui_url'), array('jquery') );
        dc_enqueue_script( 'modernizr', o('modernizr_url'), array('jquery') );
        dc_enqueue_script( 'dc_functions', get_bloginfo('template_url').'/js/dc_functions.js', array('jquery'), '0', true );
        
        if( o('jqueryui_theme') ) dc_enqueue_style('jqueryui_style', o('jqueryui_theme') );
        
        // load up any custom scripts from the theme options
        dc_enqueue_scripts( o('headerJS') );
        dc_enqueue_scripts( o('footerJS'),true);
        
	}
}
add_action('init', 'dc_loadScripts');


// only load Ace as needed
function enqueue_ace(){
    c('enqueue_ace()');
    dc_enqueue_script( 'ace', o('ace_url') );
}
add_action('admin_enqueue_scripts','enqueue_ace');


?>