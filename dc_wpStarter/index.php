<?php 

get_header();

c('Begin index.php',2);

dc_auxSidebar('Banner_All');

if(is_home()) {
    
    dc_auxSidebar('Before_Home');
    
} else if(is_archive()) {
    
    dc_auxSidebar('Before_Archive');

}

c('Begin The Loop',2); 

if (have_posts()) {
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	e("<div class='articles page-$paged'>");
	
	while (have_posts()) { 
		the_post(); 
		dc_renderMarkup(o('postFormatIndex'));
	}
	
	e('</div>'.c('/.articles',0,1));
} else {
	c('query produced no results');
	echo o('contentMissing');
}

c('End The Loop',3);

dc_postNav();

dc_auxSidebar('After_Archive');
    
c('End index.php',3);

get_footer(); 

?>