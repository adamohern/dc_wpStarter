<?php

/*
// clean up the <head>
*/
function removeHeadLinks() {
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
}
add_action('init', 'removeHeadLinks');
remove_action('wp_head', 'wp_generator');





/*
// includes thumbnail (featured image) in rss
*/
function insertThumbnailRSS( $content ) {
	global $post;
	if ( has_post_thumbnail( $post->ID ) ) $content = '' . get_the_post_thumbnail( $post->ID, 'rss-thumb' ) . '' . $content;
	return $content;
}
add_filter( 'the_excerpt_rss', 'insertThumbnailRSS' );
add_filter( 'the_content_feed', 'insertThumbnailRSS' );
add_image_size ( 'rss-thumb', 645, 330, true );





/*
// stop WP adding junk (i.e. <br /> and <p></p>) to shortcodes
*/
remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop' , 10);





/*
// <title> tag
*/
function evd_archiveTitle() {
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