<?php 


function dc_renderMarkup($markup) {
    
    c('dc_renderMarkup()',1);
    e(dc_get_renderMarkup($markup));
    
}


// only works within the loop!
function dc_get_renderMarkup($markup) {
     
    c('dc_get_renderMarkup()',1);
    
    if($markup){

        $shortcodes = array(
        	array('handle'=>'the_post_thumbnail','function'=>'dc_get_the_post_thumbnail'),
        	array('handle'=>'request_uri','function'=>'dc_get_request_uri'),
        	array('handle'=>'searchform','function'=>'dc_get_searchform'),
            array('handle'=>'the_author','function'=>'get_the_author'),
            array('handle'=>'the_author_link','function'=>'get_the_author_link'),
            array('handle'=>'the_author_posts_link','function'=>'dc_get_the_author_posts_link'),
            array('handle'=>'the_excerpt','function'=>'get_the_excerpt'),
            array('handle'=>'the_ID','function'=>'get_the_ID'),
            array('handle'=>'the_title','function'=>'dc_get_the_title'),
            array('handle'=>'the_date','function'=>'dc_get_the_date'),
            array('handle'=>'the_content','function'=>'dc_get_the_content'),
            array('handle'=>'the_tags','function'=>'dc_get_the_tags'),
            array('handle'=>'the_category','function'=>'dc_get_the_category'),
            array('handle'=>'bloginfo','function'=>'dc_get_bloginfo'),
            array('handle'=>'the_author_meta','function'=>'dc_get_the_author_meta'),
            array('handle'=>'the_terms','function'=>'dc_get_the_terms'),
            array('handle'=>'the_permalink','function'=>'dc_get_the_permalink'),
            array('handle'=>'the_shortlink','function'=>'dc_get_the_shortlink'),
            array('handle'=>'the_time','function'=>'dc_get_the_time'),
            array('handle'=>'comments_template','function'=>'dc_get_comments_template'),
            array('handle'=>'get_post_meta','function'=>'dc_get_post_meta'),
            array('handle'=>'get_post_class','function'=>'dc_get_post_class'),
            array('handle'=>'dc_sidebar','function'=>'dc_sidebar')
        );

        foreach ($shortcodes as $shortcode){
            add_shortcode($shortcode['handle'],$shortcode['function']);
        }
        
        $markup = do_shortcode($markup);
        
        foreach ($shortcodes as $shortcode){
            remove_shortcode($shortcode['handle']);
        }
         
    }
   
    return htmlspecialchars_decode($markup);
}


function dc_get_the_post_thumbnail($args){
	if(get_the_post_thumbnail()!=''){
		if(isset($args['size'])) $size = $args['size']; else $size = 'dc_thumbnail';
		$x = get_the_post_thumbnail(get_the_ID(),$size);
		if(isset($args['link']) && $args['link']) $x = "<a href=\"".get_permalink()."\">$x</a>";
		return $x;
	} else return false;
}

function dc_get_request_uri($args){
	return get_bloginfo('wpurl').$_SERVER['REQUEST_URI'];
}

function dc_get_searchform($args){
	return get_searchform(false);
}

function dc_get_the_author_posts_link($args){
    $x = '<a class="author the-author-posts-link" href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.the_author_meta( 'display_name' ).'</a>';
    return $x;
}

function dc_get_the_title($args){
	$x = get_the_title();
	if(isset($args['link']) && $args['link']) $x = "<a href=\"".get_permalink()."\">$x</a>";
	return $x;
}

function dc_get_the_date($args){
    if(!$d = $args['d']) $d = get_option('date_format','d F, Y');
    $x = get_the_date($d);
    return $x;
}

function dc_get_the_content($args){
    if(!isset($args['link']) || !$more_link_text = s) $more_link_text = 'more...';
    if(!isset($args['stripteaser']) || !$stripteaser = $args['stripteaser']) $stripteaser = false;
    $x = get_the_content($more_link_text,$stripteaser);
	$x = apply_filters('the_content', $x);
    
    return $x;
}

function dc_get_the_tags($args){
    $x = get_the_tags();
    return $x;
}

function dc_get_the_category($args){
    $x = get_the_category();
    return $x;
}

function dc_get_the_bloginfo($args){
    $x = get_the_bloginfo();
    return $x;
}

function dc_get_the_author_meta($args){
    $x = get_the_author_meta();
    return $x;
}

function dc_get_the_terms($args){
    $x = get_the_terms();
    return $x;
}

function dc_get_the_permalink($args){
    $x = get_the_permalink();
    return $x;
}

function dc_get_the_shortlink($args){
    $x = get_the_shortlink();
    return $x;
}

function dc_get_the_time($args){
    $x = get_the_time();
    return $x;
}

function dc_get_comments_template($args){
    ob_start();
    comments_template();
    return ob_get_clean();
}

function dc_get_post_meta($args){
    if(!$key=$args['key']) $key = null;
    if(!$single=$args['single']) $single = null;
    $x = get_post_meta(get_the_ID(),$key,$single);
    return $x;
}
       

function dc_get_post_class($args=array()){
    if(!isset($args['class'])) $class = '';
    else {
        $class = trim(explode(',',$args['class']));
    }
    
    $fullWidth = get_post_meta($post->ID, 'fullWidth'); 
    if($fullWidth[0]=='true') $class[]='fullWidth';
    
    $x = implode(' ',get_post_class($class));
    return $x;
}

       
function dc_sidebar($args){
    $handle = $args['handle'];
    $x = '';
    
    $x .= c("dc_is_active_sidebar($handle)? ...",1,true);

	if (dc_is_active_sidebar($handle)) {
        $x .= c("... Yes.",1,true);
		$x .= c("Begin sidebar dc_sidebar('$handle')",2,true);
		$x .= '<div id="'.$handle.'" class="dc_sidebar clearfix">'."\n";
		$x .= dc_get_dynamic_sidebar($handle);
		$x .= "\n".'</div><!--/#'.$handle.'-->'."\n";
        $x .= c("/#$handle",1,true);
		$x .= c("End sidebar '$handle'",3,true);
	} else { c("... No.",1,true); }

    return $x;
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