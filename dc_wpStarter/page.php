<?php 

get_header();

c('Begin page.php',2);

dc_auxSidebar('Banner_All');

c('Begin The Loop (page.php)',2);
if (have_posts()) { 
	while (have_posts()) { 
		the_post();
		$postCSS = get_post_meta($post->ID, 'postCSS');
        $hideTitle = get_post_meta($post->ID, 'hideTitle');
        $hideWpautop = get_post_meta($post->ID, 'wpautop');
        
        if ($hideWpautop) remove_filter('the_content', 'wpautop');
		
		e('<article '.get_post_class('clearfix').' id="post-'.get_the_ID().'">');
		
        if(o('pageTitles') && !$hideTitle) { e('<h1 class="entry-title">'.get_the_title().'</h1>'); }
        
        br();
		
		e('<div class="entry-content">');
        
		if($postCSS[0]) { e('<style type="text/css">'."\n".$postCSS[0]."\n".'</style>'); }
		
        the_content();
        
        c('/.entry-content');
		e('</div>'); 
		
		e('</article>');
	}
} 

c('End The Loop (page.php)',3);

c('End page.php',3);
	
get_footer(); 

?>