<?php

// remove the default theme editor from the admin menu
// http://www.tcbarrett.com/2011/09/remove-and-disable-wordpress-theme-and-plugin-editors/
function tcb_remove_editor_menu() {
  remove_action('admin_menu', '_add_themes_utility_last', 101);
}
add_action('admin_menu', 'tcb_remove_editor_menu', 1);




// clean up the <head>
function removeHeadLinks() {
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
}
add_action('init', 'removeHeadLinks');
remove_action('wp_head', 'wp_generator');




// when displaying posts, it's nice to let CSS know if the post has a thumbnail
function dc_post_class($content) {
	if(get_the_post_thumbnail() != '') $content[] = "dc-has-thumb";
    $content[] = "clearfix";
	return $content;
}
add_filter('post_class','dc_post_class');





// includes thumbnail (featured image) in rss
function insertThumbnailRSS( $content ) {
	global $post;
	if ( has_post_thumbnail( $post->ID ) ) $content = '' . get_the_post_thumbnail( $post->ID, 'rss-thumb' ) . '' . $content;
	return $content;
}
add_filter( 'the_excerpt_rss', 'insertThumbnailRSS' );
add_filter( 'the_content_feed', 'insertThumbnailRSS' );
add_image_size ( 'rss-thumb', 645, 330, true );




// <title> tag
function dc_archiveTitle() {
	$title = '';
	global $page, $paged;
    $customTitle = get_post_meta(get_the_ID(), 'customTitle', true);
    
    if(!empty($customTitle)) {
        $title .= $customTitle;
        
    } else {
        $title .= get_bloginfo( 'name' );
    	
    	// Add the blog description for the home/front page.
    	$site_description = get_bloginfo( 'description', 'display' );
    	if ( $site_description && is_front_page() ) $title .= " - $site_description";
        else $title .= wp_title( '-', false );
    
    	// Add a page number if necessary:
    	if ( $paged >= 2 || $page >= 2 )
    		$title .= ' - ' . sprintf( 'Page %s', max( $paged, $page ) );
    }

    return $title;
}

?>