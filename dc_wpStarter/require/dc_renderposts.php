<?php 


function dc_render_markup($markup) {
    
    e(dc_get_render_markup($markup));
    
}


// only works within the loop!
function dc_get_render_markup($markup) {
         
    if($markup){

        $shortcodes = array(
        	array('the_post_thumbnail',		'dc_get_the_post_thumbnail'),
        	array('request_uri',			'dc_get_request_uri'),
        	array('get_search_form',		'dc_get_search_form'),
            array('the_author',				'get_the_author'),
            array('the_author_link',		'get_the_author_link'),
            array('the_author_posts_link',	'dc_get_the_author_posts_link'),
            array('the_excerpt',			'get_the_excerpt'),
            array('the_ID',					'get_the_ID'),
            array('the_title',				'dc_get_the_title'),
            array('the_date',				'dc_get_the_date'),
            array('the_content',			'dc_get_the_content'),
            array('the_tags',				'dc_get_the_tags'),
            array('the_category',			'dc_get_the_category'),
            array('bloginfo',				'dc_get_bloginfo'),
            array('the_author_meta',		'dc_get_the_author_meta'),
            array('the_terms',				'dc_get_the_terms'),
            array('the_permalink',			'dc_get_the_permalink'),
            array('the_shortlink',			'dc_get_the_shortlink'),
            array('the_time',				'dc_get_the_time'),
            array('comments_template',		'dc_get_comments_template'),
            array('get_post_meta',			'dc_get_post_meta'),
            array('get_post_class',			'dc_get_post_class'),
            array('dc_sidebar',				'dc_get_sidebar')
        );

        foreach ($shortcodes as $shortcode){
            add_shortcode($shortcode[0],$shortcode[1]);
        }
        
        $markup = do_shortcode($markup);
         
    }
   
    return apply_filters(__FUNCTION__, htmlspecialchars_decode($markup));
}


function dc_get_the_post_thumbnail($args){
	if(get_the_post_thumbnail()!=''){
		if(isset($args['size'])) $size = $args['size']; else $size = 'dc_thumbnail';
		$x = get_the_post_thumbnail(get_the_ID(),$size);
		if(isset($args['link']) && $args['link']) $x = "<a href=\"".get_permalink()."\">$x</a>";
		return apply_filters(__FUNCTION__,$x);
	} else return false;
}

function dc_get_request_uri($args){
	$x = get_bloginfo('wpurl').$_SERVER['REQUEST_URI'];
	return apply_filters(__FUNCTION__,$x);
}

function dc_get_search_form($args){
	$x = get_search_form(false);
	return apply_filters(__FUNCTION__,$x);
}

function dc_get_the_author_posts_link($args){
    $x = '<a class="author the-author-posts-link" href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.the_author_meta( 'display_name' ).'</a>';
    return apply_filters(__FUNCTION__,$x);
}

function dc_get_the_title($args){
	$x = get_the_title();
	if(isset($args['link']) && $args['link']) $x = "<a href=\"".get_permalink()."\">$x</a>";
	return apply_filters(__FUNCTION__,$x);
}

function dc_get_the_date($args){
    if(!$d = $args['d']) $d = get_option('date_format','d F, Y');
    $x = get_the_date($d);
    return apply_filters(__FUNCTION__,$x);
}

function dc_get_the_content($args){
    if(!isset($args['link']) || !$more_link_text = s) $more_link_text = 'more...';
    if(!isset($args['stripteaser']) || !$stripteaser = $args['stripteaser']) $stripteaser = false;
    $x = get_the_content($more_link_text,$stripteaser);
	$x = apply_filters('the_content', $x);
    
    return apply_filters(__FUNCTION__,$x);
}

function dc_get_the_tags($args){
    $x = get_the_tags();
    return apply_filters(__FUNCTION__,$x);
}

function dc_get_the_category($args){
    $x = get_the_category();
    return apply_filters(__FUNCTION__,$x);
}

function dc_get_bloginfo($args){
	if(isset($args['show'])){
		$x = get_bloginfo($args['show']);
		return apply_filters(__FUNCTION__,$x);
    } else {
    	return false;
    }
}

function dc_get_the_author_meta($args){
    $x = get_the_author_meta();
    return apply_filters(__FUNCTION__,$x);
}

function dc_get_the_terms($args){
    $x = get_the_terms();
    return apply_filters(__FUNCTION__,$x);
}

function dc_get_the_permalink($args){
    $x = get_the_permalink();
    return apply_filters(__FUNCTION__,$x);
}

function dc_get_the_shortlink($args){
    $x = get_the_shortlink();
    return apply_filters(__FUNCTION__,$x);
}

function dc_get_the_time($args){
    $x = get_the_time();
    return apply_filters(__FUNCTION__,$x);
}

function dc_get_comments_template($args){
    ob_start();
    comments_template();
    $x = ob_get_clean();
    return apply_filters(__FUNCTION__,$x);
}

function dc_get_post_meta($args){
    if(!$key=$args['key']) $key = null;
    if(!$single=$args['single']) $single = null;
    $x = get_post_meta(get_the_ID(),$key,$single);
    return apply_filters(__FUNCTION__,$x);
}
       

function dc_get_post_class($args=array()){
    if(!isset($args['class'])) $class = '';
    else {
        $class = trim(explode(',',$args['class']));
    }
    
    $fullWidth = get_post_meta($post->ID, 'fullWidth'); 
    if($fullWidth[0]=='true') $class[]='fullWidth';
    
    $x = implode(' ',get_post_class($class));
    return apply_filters(__FUNCTION__,$x);
}

       
function dc_sidebar($args){
	e(dc_get_sidebar($args));
}
       
       
function dc_get_sidebar($args){
	
	if(isset($args['handle'])) $handle = $args['handle'];
	else if (is_string($args)) $handle = $args;
	else $handle = '[no sidebar handle provided]';
    c("dc_get_sidebar($handle)",1);
    
    $x = c("dc_is_active_sidebar($handle)? ...",1,1);

	if (dc_is_active_sidebar($handle)) {
        $x .= c("... Yes.",1,1);
		$x .= c("Begin sidebar dc_get_sidebar('$handle')",2,1);
		$x .= '<div id="'.$handle.'" class="dc_get_sidebar clearfix">'."\n";
		$x .= dc_get_dynamic_sidebar($handle);
		$x .= "\n".'</div><!--/#'.$handle.'-->'."\n";
        $x .= c("/#$handle",1,1);
		$x .= c("End sidebar '$handle'",3,1);
	} else { $x .= c("... No.",1,1); }

    return apply_filters(__FUNCTION__,$x);
}


function dc_postNav() {
	c('dc_postNav() (functions.php)',1);
	echo '<div class="navigation clearfix">';
	echo '<div class="next-posts">';
	next_posts_link('&laquo; Older Entries',0);
	echo '</div>';
	echo '<div class="prev-posts">';
	previous_posts_link('Newer Entries &raquo;',0);
	echo '</div>';
	echo '</div>';
	c('/dc_postNav()',1);
}

function dc_authorBio() { 
	if (o('dc_displayBio')){
		if ( get_the_author_meta('description') ) {
			$authorBio = get_the_author_meta('description');
			c('Begin dc_authorBio() (dc_renderposts.php)',1);
			echo '<div class="authorBio clearfix '.get_the_author_meta('user_login').'"><div class="avatar">'.get_avatar( get_the_author_meta('ID'), 96 ).'</div><div class="text"><h3>About '.get_the_author_link().'</h3>'.$authorBio.'</div></div>'.c('/.authorBio',0,true);
			c('End dc_authorBio()',1);	
		}
	}
}

?>