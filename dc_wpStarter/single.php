<?php get_header(); 

c('Begin single.php',2);

dc_sidebar('Banner_All');

c('Begin The Loop (single.php)',2);

if (have_posts()) { 
	while (have_posts()) {
        
		the_post();
        
        $hideWpautop = get_post_meta($post->ID, 'wpautop');
        if ($hideWpautop) remove_filter('the_content', 'wpautop');
        
        $postCSS = get_post_meta($post->ID, 'postCSS');
                
        if($postCSS[0]) {
            e('<style type="text/css">'."\n".$postCSS[0]."\n".'</style>');
        }
        
        c('dc_render_markup(o(\'postFormatSingle\'))',1);
        dc_render_markup( apply_filters('postFormatSingle',o('postFormatSingle')) );
	}
}

c('End The Loop (single.php)',3);

c('End single.php',3);
	
get_footer(); 

?>