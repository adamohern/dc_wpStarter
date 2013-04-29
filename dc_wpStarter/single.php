<?php get_header(); 

c('Begin single.php',2);

dc_auxSidebar('Banner_All');

c('Begin The Loop (single.php)',2);

if (have_posts()) { 
	while (have_posts()) {
		the_post();
        echo '<article '; post_class(); echo ' id="post-'.get_the_ID().'">'."\n";

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
        echo '<div class="entry-content">'."\n";
		if($postCSS[0]) {echo '<style type="text/css">'.$postCSS[0].'</style>';}
        the_content();
        echo '</div>'.c('/.entry-content',0,true);
		dc_authorBio();
		dc_googleAuthorship();
        dc_auxSidebar('After_Single');
        
        echo '</article>'."\n";
        
        $dc_commentsDisabledMessage = dc_option('commentsDisabledMessage');
        
        if(!dc_option('disableComments') || current_user_can('manage_options')) { comments_template(); } 
        else { if ($dc_commentsDisabledMessage) echo $dc_commentsDisabledMessage;}
	}
}

c('End The Loop (single.php)',3);

c('End single.php',3);
	
get_footer(); 

?>