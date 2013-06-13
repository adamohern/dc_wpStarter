<?php 

c('Begin archive.php',2);

get_header();

$post = $posts[0]; // Hack. Set $post so that the_date() works.

$x = '<h2>';

if (is_category()) {
	$x .= 'Category: '.single_cat_title('',0);

} elseif( is_tag() ) {
	$x .= 'Tag: '.single_tag_title('',0);

} elseif (is_day()) {
	$x .= 'Posts from '.the_time('F jS, Y'); 

} elseif (is_month()) { 
	$x .= 'Posts from '.the_time('F, Y'); 

} elseif (is_year()) { 
	$x .= 'Posts from '.the_time('Y'); 

} elseif (is_author()) { 
	$author = get_userdata( get_query_var('author') );
	$x .= 'Posts by '.$author->display_name;

} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
	$x .= 'Archives';
}

$x .= '</h2>';

e(apply_filters('dc_archive_title',$x));

br();

dc_the_loop('post_format_archive');

get_footer(); 

c('End archive.php',3);

?>