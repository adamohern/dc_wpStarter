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
		
		echo '<article '; post_class('clearfix'); echo ' id="post-'.get_the_ID().'">';
		if(dc_option('pageTitles') && !$hideTitle) { echo '<h1 class="entry-title">'; the_title(); echo '</h1>'; }
		
		echo '<div class="entry-content">';
		if($postCSS[0]) {echo '<style type="text/css">'.$postCSS[0].'</style>';}
		the_content();
		echo '</div>'.c('/.entry-content',0,true);
		
		echo '</article>';
	}
} 

c('End The Loop (page.php)',3);

c('End page.php',3);
	
get_footer(); 

?>