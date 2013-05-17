<?php 

get_header();

c('Begin search.php',2);
dc_sidebar('Banner_All');
dc_sidebar('Before_Archive');

$allsearch = &new WP_Query("s=$s&showposts=-1"); 
$key = wp_specialchars($s, 1); 
$count = $allsearch->post_count; 

e('<h2 class="pagetitle">');

e($count.' results for "<span class="search-terms">'.$key.'</span>"'); 

wp_reset_query();

e('</h2>');

dc_postNav();

if (have_posts()) {
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	e("<div class='articles page-$paged'>");
	
	while (have_posts()) { 
		the_post(); 
		dc_renderMarkup(o('postFormatSearch'));
	}
	
	e('</div>'.c('/.articles',0,1));
} else {
	c('query produced no results');
	echo o('contentMissing');
}

c('End The Loop',3);

dc_postNav();

dc_sidebar('After_Archive');

c('End search.php',3);

get_footer(); 

?>