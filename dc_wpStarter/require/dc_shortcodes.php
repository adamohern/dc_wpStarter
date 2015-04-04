<?php 

// creates a list of downloads from CSV URLs
function dc_downloads($atts){
    
    if(isset($atts['classes'])) $classes = implode(' ',dc_commasToArray($atts['classes']));
    
    if(isset($atts['id'])) $id = ' id="'.$atts['id'].'"'; 
    
    if(isset($atts['title'])) $title = $atts['title'];
    else $title = "downloads";
    
    if(isset($atts['caption'])) $caption = $atts['caption'];
    
    if(isset($atts['urls'])) $urls = dc_commasToArray($atts['urls']);
    
    $html = br(3,1).'<div class="downloads'.$classes.'"'.$id.'>'.br(0,1);
    if($title) $html .= '<h3 class="title">'.$title.'</h3>'.br(0,1);
    if($caption) $html .= '<p class="caption">'.$caption.'</p>'.br(0,1);
    if($urls){
        $html .= '<ul>';
        foreach($urls as $url){
            $html .= '<li><a href="'.$url.'">'.basename($url).'</a></li>'.br(0,1);
        }
        $html .= '</ul>';
    } else {
    	$html .= c('no URLs provided',1,1);
    }
    $html .= '</div>'.br(3,1);
    
    return apply_filters(__FUNCTION__,$html);
}
add_shortcode('dc_downloads','dc_downloads');




// counts the number of posts with a given set of tags
function dc_count_shortcode($atts,$content=null){
    $tags = $atts[tags];
    return apply_filters(__FUNCTION__,'<span class="count">'.dc_count($tags).'</span>');
}
add_shortcode('dc_count','dc_count_shortcode');




// renders a properly formatted shadowbox image link
function dc_j_img($atts,$content=null) {
    $s = '<a href="'.$content.'" rel="shadowbox"><img class="thumbnail" src="'.$content.'" /></a>';
    return apply_filters(__FUNCTION__,$s);
} 
add_shortcode('dc_img', 'dc_j_img');




// renders a properly formatted shadowbox file link
function dc_j_file($atts,$content=null) {
	$s = '<a href="'.$content.'">'.basename($content).'</a>';
	return apply_filters(__FUNCTION__,$s);
}
add_shortcode('dc_file', 'dc_j_file');




// renders a properly formatted jquery UI toggle
function dc_toggle($atts,$content=null) {
	$title = $atts[title];
	
	if(!$title) $title = 'click to expand';
	if(!$content) $content = 'Lorem ipsum dolor sit amet.';
	
	return apply_filters(__FUNCTION__,"<div class='toggle title'>$title</div><div class='peekaboo' style='display:none;'>$content</div>");
}
add_shortcode('dc_toggle', 'dc_toggle');




// renders a properly formatted jquery UI accordion
function dc_accordion($atts,$content){
	$content = wpautop(trim($content));
	return apply_filters(__FUNCTION__,force_balance_tags("<div class=\"accordion\">$content</div>"));
}
add_shortcode('dc_accordion', 'dc_accordion');




// renders properly formatted jquery UI tabs
function dc_tabs($atts,$content){
	$content = wpautop(trim($content));
	return apply_filters(__FUNCTION__,force_balance_tags("<div class=\"tabs\">$content</div>"));
}
add_shortcode('dc_tabs', 'dc_tabs');




// renders a list of post links based on a custom query
function dc_query_posts_shortcode($atts) {
    if(!$atts['showtitles'])     	$atts['showtitles'] = true;	
    if(!$atts['order']) 			$atts['order'] = 'asc';
	if(!$atts['maxposts'])        $atts['maxposts'] = 12;
    if(!$atts['dateformat']) 	    $atts['dateformat'] = 'd F, Y';
    if(!$atts['offset'])          $atts['offset'] = 0;
    if(!$atts['paginate'])        $atts['paginate'] = false;
	
	return dc_query_posts($atts);
}
add_shortcode('dc_query_posts', 'dc_query_posts_shortcode');




// Whitelist for comment shortcodes
//---------------------------------

// We don't want commenters to be able to use just any old shortcode, 
// so we remove them all first...
function dc_remove_all_shortcodes() {
  global $shortcode_tags;
  global $temp_shortcode_tags;
  $temp_shortcode_tags = $shortcode_tags;
  remove_all_shortcodes();
}

// But hang onto them for later.
function dc_restore_all_shortcodes() {
  global $shortcode_tags;
  global $temp_shortcode_tags;
  if(!empty($temp_shortcode_tags)) {
    $shortcode_tags = $temp_shortcode_tags;
  }
}

// Then we initiate the shortcodes we want to whitelist for commenters.
function dc_shortcodes_whitelist() {
	add_shortcode('img', 'dc_j_img');
	add_shortcode('file', 'dc_j_file');
}

function init_comment_shortcodes() {
  dc_remove_all_shortcodes();
  dc_shortcodes_whitelist();
  add_filter('comment_text', 'do_shortcode');
}

//add_action('init', 'init_comment_shortcodes');
//add_filter('dynamic_sidebar', 'dc_restore_all_shortcodes');
//add_filter('widget_execphp', 'do_shortcode');
dc_shortcodes_whitelist();


?>