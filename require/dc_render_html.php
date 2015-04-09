<?php 


function dc_render_markup($markup) {
    e(dc_get_render_markup($markup));
}


// Our way of allowing users to access Wordpress template tags
// without the danger of eval() statements.
function dc_get_render_markup($markup,$args=array()) {
         
    if($markup){

        $markup = htmlspecialchars_decode($markup);
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
            array('dc_sidebar',				'dc_get_sidebar'),
            array('dc_google_authorship',	'dc_get_google_authorship'),
            array('dc_author_bio',			'dc_get_author_bio'),
            array('the_modified_date',      'dc_get_the_modified_date')
        );

        foreach ($shortcodes as $shortcode){
            if($shortcode[0] == 'the_title'){
                if($args['hide_title']=='true') add_shortcode($shortcode[0],'dc_nothing');
                else add_shortcode($shortcode[0],$shortcode[1]);
            } else {
                add_shortcode($shortcode[0],$shortcode[1]);
            }
        }
        
        $markup = do_shortcode($markup);
         
    }
   
    return apply_filters(__FUNCTION__, $markup);
}

function dc_nothing($args){
    return '';
}

function dc_get_the_post_thumbnail($args){
	if(get_the_post_thumbnail()!=''){
        if(isset($args['cropbox']) && $args['cropbox']){
            $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
            $x = '<div class="cropbox" style="background-image:url('.$url.');"></div>';
        } else {
            if(isset($args['size'])) $size = $args['size']; else $size = 'medium';
            $x = get_the_post_thumbnail(get_the_ID(),$size);
        }

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
    if (is_array($args)){
        if (!$args['hide_title']){
        	$x = get_the_title();
            if($args['before']) $x = $args['before'].$x;
            if($args['after']) $x .= $args['after'];
        	if(isset($args['link']) && $args['link']) $x = "<a href=\"".get_permalink()."\">$x</a>";
        	return apply_filters(__FUNCTION__,$x);
        }
    }
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
    $defaults = array('sep'=>', ','before'=>'Tags: ','after'=>'');
    $args = shortcode_atts( $defaults, $args );
    $tagsArray = get_the_tags();
    if($tagsArray){
        foreach($tagsArray as $tag) $tagIDs[] = $tag->term_id;
        foreach($tagIDs as $tagID) $tagLinks[] = '<a href="'.get_tag_link($tagID).'" />'.get_term($tagID,'post_tag')->name.'</a>';
        $outputString = $args['before'].implode($args['sep'],$tagLinks).$args['after'];
    } else {
        $outputString = '<!--'.__FUNCTION__.': no tags found-->';
    }
    return apply_filters(__FUNCTION__,$outputString);
}

function dc_get_the_category($args){
    $defaults = array('sep'=>', ','parents'=>'single','post_id'=>'false');
    $args = shortcode_atts( $defaults, $args );
    $outputString .= c('getting categories',1,1);
    if($args['parents']!='single') $outputString .= c('"parents" attribute not supported at this time',1,1);
    if($args['post_id'] != 'false'){
        $outputString .= c('post_id supplied, getting explicit category link',1,1);
        $outputString .= get_category_link($args['post_id']);
    } else {
        $outputString .= c('no post_id supplied, getting list for current post in The Loop',1,1);
        $cats = get_the_category();
        $linksArray = array();
        foreach($cats as $cat){
            $linksArray[] = '<a href="'.get_category_link($cat->term_id).'">'.$cat->name.'</a>';
        }
        $outputString .= implode($args['sep'],$linksArray);
    }
    return apply_filters(__FUNCTION__,$outputString);
}

function dc_get_the_modified_date($args){
    $defaults = array('d'=>get_option('date_format'),'before'=>'','after'=>'','echo'=>'true');
    $args = shortcode_atts( $defaults, $args );
    $outputString = $args['before'].get_the_modified_date( $args['d'] ).$args['after'];
    return apply_filters(__FUNCTION__,$outputString);
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
    $defaults = array('key'=>null,'single'=>null,'before'=>'','after'=>'');
    $args = shortcode_atts( $defaults, $args );
    $x = get_post_meta(get_the_ID(),$args['key'],$args['single']);
    $y = array();
    foreach($x as $i) $y[] = $i;
    $x = implode(', ',$y);
    if($x) return apply_filters(__FUNCTION__,$args['before'].$x.$args['after']);
    else return apply_filters(__FUNCTION__,c('post meta tag "'.$args['key'].'" not found',1,1));
}
       

function dc_get_post_class($args=array()){
    if(!isset($args['class'])) $class = '';
    else {
        $class = trim(explode(',',$args['class']));
    }
    
    $fullWidth = get_post_meta($post->ID, 'fullWidth'); 
    if($fullWidth[0]=='true') $class[]='fullWidth';
    
    $class[]='clearfix';
    
    $x = implode(' ',get_post_class($class));
    return apply_filters(__FUNCTION__,$x);
}



function dc_get_google_authorship(){
	if (get_the_author_meta('gplus_url')&&get_the_author_meta('user_firstname')){
		$x = ('<p class="dc_google_authorship"><em><a href="'.get_the_author_meta('gplus_url').'?rel=author">'.get_the_author_meta('user_firstname').'</a> on google+</em></p>');
	} else { $x = c('No Google authorship data has been added for this author. (Users > Profile > Google+ profile URL)',1,1); }
	return apply_filters(__FUNCTION__,$x);
}


function dc_google_authorship() {
	c('e(dc_get_google_authorship());',1);
	e(dc_get_google_authorship());
}


function dc_the_loop($format){
    e(dc_get_the_loop($format));
}
    
function dc_get_the_loop($format){

	$x = c('Begin dc_the_loop()',2,1);
	$x .= "<div class='dc-wrapper dc-the-loop'>";
	$x .= "<div class='dc-liner dc-the-loop-liner'>";
	
	if(!is_single()) $x .= dc_get_sidebar('dc-before-the-loop');
	
	$x .= dc_get_post_nav(o('post_nav_next'),o('post_nav_prev'),'top-nav');

	$x .= c('Begin The Loop',1,1); 

	if (have_posts()) {
		
		$x .= br(5,1);
		
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$x .= "<div class='dc-wrapper dc-articles page-$paged clearfix'>";
		$x .= "<div class='dc-liner dc-articles-liner'>";
		
		$x .= br(5,1);
		
		while (have_posts()) { 
			the_post(); 
			
			if(is_singular()){
				$disable_wpautop = get_post_meta(get_the_ID(), 'dc_wpautop');
				if ($disable_wpautop[0]) remove_filter('the_content', 'wpautop');
			
				$post_css = get_post_meta($post->ID, 'dc_post_css');
				
				if($post_css[0]) {
					$x .= '<style type="text/css">'."\n".$post_css[0]."\n".'</style>';
				}
			}
			
			$id = 'post-'.get_the_ID();
			
			$x .= '<article id="'.$id.'" class="dc-wrapper '.implode(' ',get_post_class()).'">';
			$x .= '<div class="dc-liner">';
			$dc_hide_title = get_post_meta(get_the_ID(), 'dc_hide_title');
			$args = array('hide_title'=>$dc_hide_title[0]);
            $x .= "\n<!-- Using '$format' format. -->\n";
			$x .= dc_get_render_markup(apply_filters($format,o($format)),$args);
			$x .= '</div><!--/.post-liner-->';
			$x .= '</article><!--/'.$id.'-->';
			$x .= br(2,1);
		}
	
		$x .= br(3,1);
		
		$x .= '</div>'.c('/.dc-articles-liner',0,1);
		$x .= '</div>'.c('/.dc-articles',0,1);
		
		$x .= br(5,1);
		
	} else {
	
		$x .= c('query produced no results',0,1);
		$x .= o('contentMissing');
	
	}

	$x .= c('End The Loop',1,1);

	$x .= dc_get_post_nav(o('post_nav_next'),o('post_nav_prev'),'bottom-nav');
	
	if(!is_single()) $x .= dc_get_sidebar('dc-after-the-loop');
	
	$x .= "</div><!--/.dc-the-loop-liner-->";
	$x .= "</div><!--/.dc-the-loop-->";
	$x .= c('End dc_the_loop()',3,1);
    
    return apply_filters(__FUNCTION__,apply_filters($format,$x));
	
}


function dc_post_nav($next='next',$prev='prev',$class) {
    e(dc_get_post_nav($next='next',$prev='prev',$class));
}


function dc_get_post_nav($next='next',$prev='prev',$class) {
    
	if($class) $class = ' '.$class;
    
	$x = c('dc_post_nav()',1,1);
	$x .= '<div class="dc-wrapper dc-navigation clearfix'.$class.'">';
	$x .= '<div class="dc-liner dc-navigation-liner">';
	$x .= '<div class="dc-next-posts">';
	$x .= get_next_posts_link($next,0);
	$x .= '</div><!--/.dc-next-posts-->';
	$x .= '<div class="dc-prev-posts'.$class.'">';
	$x .= get_previous_posts_link($prev,0);
	$x .= '</div><!--/.dc-prev-posts-->';
	$x .= '</div><!--/.dc-navigation-liner-->';
	$x .= '</div><!--/.dc-navigation-->';
	$x .= c('/dc_post_nav()',1,1);
    
    return apply_filters(__FUNCTION__,$x);
    
}


function dc_author_bio() { 
	e(dc_get_author_bio());
}


function dc_get_author_bio() {
	if (o('dc_displayBio')){
		if ( $authorBio = get_the_author_meta('description') ) {
			$x = c('Begin dc_get_author_bio() (dc_renderposts.php)',1,1);
			$x .= '<div class="dc-wrapper dc-author-bio clearfix '.get_the_author_meta('user_login').'">';
			$x .= '<div class="dc-liner dc-author-bio-liner clearfix '.get_the_author_meta('user_login').'">';
			$x .= '<div class="avatar">'.get_avatar( get_the_author_meta('ID'), 96 ).'</div><!--/.avatar-->';
			$x .= '<div class="text"><h3>About '.get_the_author_link().'</h3>';
			$x .= $authorBio;
			$x .= '</div><!--/.text-->';
			$x .= '</div><!--/.dc-author-bio-liner-->';
			$x .= '</div><!--/.dc-author-bio-->';
			$x .= c('/.dc-author-bio',0,1);
			$x .= c('End dc_get_author_bio()',1,1);	
		}
	} else { $x = c('No bio found for this author.',1,1); }
	return apply_filters(__FUNCTION__,$x);
}

?>
