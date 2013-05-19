<?php 

c('Begin page.php',2);

get_header();

dc_sidebar('Banner_All');

c('Begin The Loop',1);

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
        dc_renderMarkup( apply_filters('postFormatPage',o('postFormatPage')) );
	}
}

c('End The Loop',1);
	
get_footer(); 

c('End page.php',3);

?>