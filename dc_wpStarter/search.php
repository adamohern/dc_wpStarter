<?php 

c('Begin search.php',2);

get_header();

$allsearch = &new WP_Query("s=$s&showposts=-1"); 
$key = wp_specialchars($s, 1); 
$count = $allsearch->post_count; 
wp_reset_query();

e(apply_filters('dc_search_title','<h2 class="pagetitle">'.$count.' results for "<span class="search-terms">'.$key.'</span>"</h2>'));

dc_the_loop('post_format_search');

get_footer(); 

c('End search.php',3);

?>