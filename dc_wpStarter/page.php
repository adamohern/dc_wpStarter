<?php 

get_header();

c('Begin page.php',2);

dc_auxSidebar('Banner_All');

c('Begin The Loop (page.php)',2);

if (have_posts()) { 
	while (have_posts()) {
        
		the_post();
        
        $hideWpautop = get_post_meta($post->ID, 'wpautop');
        if ($hideWpautop) remove_filter('the_content', 'wpautop');
        
        $postCSS = get_post_meta($post->ID, 'postCSS');
                
        if($postCSS[0]) {
            e('<style type="text/css">'."\n".$postCSS[0]."\n".'</style>');
        }
        
        c('dc_render_markup(o(\'postFormatPage\'))',1);
        dc_renderMarkup( o('postFormatPage') );
	}
}

c('End The Loop (page.php)',3);

c('End page.php',3);
	
get_footer(); 

?>