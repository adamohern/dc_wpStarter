<?php 

get_header();

c('Begin archive.php',2);

dc_auxSidebar('Banner_All');

if(is_home()) {dc_auxSidebar('Before_Home');} else if(is_archive()) {dc_auxSidebar('Before_Archive');}

	$post = $posts[0]; // Hack. Set $post so that the_date() works.
	
	/* If this is a category archive */ 
	if (is_category()) {
	echo '<h3>Category: '; single_cat_title(); echo '</h3>';
	
	/* If this is a tag archive */ 
	} elseif( is_tag() ) {
	echo '<h3>Tag: '; single_tag_title(); echo '</h3>';
	
	/* If this is a daily archive */ 
	} elseif (is_day()) {
	echo '<h3>Posts from '; echo the_time('F jS, Y'); echo '</h3>';
	
	/* If this is a monthly archive */ 
	} elseif (is_month()) { 
	echo '<h3>Posts from '; echo the_time('F, Y'); echo '</h3>';
	
	/* If this is a yearly archive */ 
	} elseif (is_year()) { 
	echo '<h3 class="pagetitle">Posts from '; the_time('Y'); echo '</h3>';
	
	/* If this is an author archive */ 
	} elseif (is_author()) { 
	$author = get_userdata( get_query_var('author') );
	echo '<h3 class="pagetitle">Posts by '.$author->display_name.'</h3>';
	
	/* If this is a paged archive */ 
	} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
	echo '<h3 class="pagetitle">Archives</h3>';
	
	br();
}

dc_postNav();
dc_archiveLoop();
dc_postNav();

dc_auxSidebar('After_Archive');

c('End archive.php',3);
get_footer(); 

?>