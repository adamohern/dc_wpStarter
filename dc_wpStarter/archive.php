<?php 

c('Begin archive.php',2);

get_header();

$post = $posts[0]; // Hack. Set $post so that the_date() works.

echo '<h2>';

/* If this is a category archive */ 
if (is_category()) {
echo 'Category: '; single_cat_title();

/* If this is a tag archive */ 
} elseif( is_tag() ) {
echo 'Tag: '; single_tag_title();

/* If this is a daily archive */ 
} elseif (is_day()) {
echo 'Posts from '; echo the_time('F jS, Y'); 

/* If this is a monthly archive */ 
} elseif (is_month()) { 
echo 'Posts from '; echo the_time('F, Y'); 

/* If this is a yearly archive */ 
} elseif (is_year()) { 
echo 'Posts from '; the_time('Y'); 

/* If this is an author archive */ 
} elseif (is_author()) { 
$author = get_userdata( get_query_var('author') );
echo 'Posts by '.$author->display_name.;

/* If this is a paged archive */ 
} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
echo 'Archives';

}

echo '</h2>';

br();

dc_the_loop('post_format_archive');

get_footer(); 

c('End archive.php',3);

?>