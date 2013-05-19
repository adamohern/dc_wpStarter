<?php 

c('Begin index.php',2);

get_header();

dc_sidebar('Banner_All');
if(is_home()) {
    dc_sidebar('Before_Home');
} else if(is_archive()) {
    dc_sidebar('Before_Archive');
}

c('Begin The Loop',1); 

if (have_posts()) {
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	e("<div class='articles page-$paged'>");
	
	while (have_posts()) { 
		the_post(); 
		dc_render_markup(apply_filters('postFormatIndex',o('postFormatIndex')));
	}
	
	e('</div>'.c('/.articles',0,1));
} else {
	c('query produced no results');
	echo o('contentMissing');
}

c('End The Loop',1);

dc_postNav();

dc_sidebar('After_Archive');
    
get_footer(); 

c('End index.php',3);

?>