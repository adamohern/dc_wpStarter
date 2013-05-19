<?php 

c('Begin search.php',2);

get_header();

dc_sidebar('Before_Archive');

$allsearch = &new WP_Query("s=$s&showposts=-1"); 
$key = wp_specialchars($s, 1); 
$count = $allsearch->post_count; 
wp_reset_query();

e('<h2 class="pagetitle">'.$count.' results for "<span class="search-terms">'.$key.'</span>"</h2>');

dc_postNav();

if (have_posts()) {
	c('Begin The Loop',1);

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	e("<div class='articles page-$paged'>");
	
	while (have_posts()) { 
		the_post(); 
		dc_render_markup(apply_filters('postFormatSearch',o('postFormatSearch')));
	}
	
	e('</div>'.c('/.articles',0,1));
	
	c('End The Loop',1);
} else {
	c('query produced no results');
	dc_render_markup(apply_filters('contentMissing',o('contentMissing')));
}

dc_postNav();

dc_sidebar('After_Archive');

get_footer(); 

c('End search.php',3);

?>