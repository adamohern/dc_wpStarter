<?php get_header(); 

c('Begin single.php',2);

dc_auxSidebar('Banner_All');

c('Begin The Loop (single.php)',2);

if (have_posts()) { 
	while (have_posts()) {
		the_post();
        e('<article '.get_post_class().' id="post-'.get_the_ID().'">');

		$fullWidth = get_post_meta($post->ID, 'fullWidth'); 
		if($fullWidth[0]=='true') $fullWidth=true; else $fullWidth=false;
        
        $hideWpautop = get_post_meta($post->ID, 'wpautop');
        if ($hideWpautop) remove_filter('the_content', 'wpautop');
		
		if(!$fullWidth){
			dc_articleThumb(false);
			dc_articleHeader(true,true);
		}
		
		$postCSS = get_post_meta($post->ID, 'postCSS');
        
        dc_auxSidebar('Before_Single');
        
        e('<div class="entry-content">');
		
        if($postCSS[0]) {
            e('<style type="text/css">'."\n".$postCSS[0]."\n".'</style>');
        }
        
        the_content();
        
        c('/.entry-content');
        e('</div>');
        
		dc_authorBio();
		
        dc_auxSidebar('After_Single');
        
        e('</article>');
        
        $dc_commentsDisabledMessage = dc_option('commentsDisabledMessage');
        
        if(!dc_option('disableComments') || current_user_can('manage_options')) { 
            comments_template(); 
        } else { 
            if ($dc_commentsDisabledMessage) e($dc_commentsDisabledMessage);
        }
	}
}

c('End The Loop (single.php)',3);

c('End single.php',3);
	
get_footer(); 

?>