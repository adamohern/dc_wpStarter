<?php 

get_header();

c('Begin archive.php',2);

dc_sidebar('Banner_All');

if(is_home()) {dc_sidebar('Before_Home');} else if(is_archive()) {dc_sidebar('Before_Archive');}

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

c('Begin The Loop',2); 

if (have_posts()) {
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	e("<div class='articles page-$paged'>");
	
	while (have_posts()) { 
		the_post();
		c("dc_renderMarkup(o('postFormatArchive'))",1);
		dc_renderMarkup(o('postFormatArchive'));
	}
	
	e('</div>'.c('/.articles',0,1));
} else {
	c('query produced no results',1);
	c("dc_renderMarkup(o('contentMissing'))",1);
	dc_renderMarkup(o('contentMissing'));
}

c('End The Loop',3);

dc_postNav();

dc_sidebar('After_Archive');

c('End archive.php',3);
get_footer(); 

?>