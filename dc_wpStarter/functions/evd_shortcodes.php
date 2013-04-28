<?php 

// Shortcodes
//---------------------------------

function sc_downloads($atts){
    
    $classes = $atts[classes];
    $classes = explode(',',$classes);
	array_walk($classes,'trim_value');
    foreach($classes as $class){ $classesString .=' '.$class; }
    
    $id = $atts[id];
    if($id) $id = ' id="'.$id.'"';
    
    $title = $atts[title];
    if($title) $title = $title; else $title = "downloads";
    
    $caption = $atts[caption];
    
    $urls = $atts[urls];
	$urls = explode(',',$urls);
	array_walk($urls,'trim_value');
    
    $html = "\n".'<div class="downloads'.$classesString.'"'.$id.'>';
    if($title) $html .= "\n".'<h3 class="title">'.$title.'</h3>';
    if($caption) $html .= "\n".'<p class="caption">'.$caption.'</p>';
    if($urls){
        $html .= "\n".'<ul>';
        foreach($urls as $url){
            $html .= "\n".'<li><a href="'.$url.'">'.basename($url).'</a></li>';
        }
        $html .= "\n".'</ul>';
    }
    $html .= "\n".'</div>';
    
    return $html;
}
add_shortcode('downloads','sc_downloads');

function sc_totalDuration($atts,$content=null){
    $tags = $atts[tags];
    return '<span class="duration">'.evd_totalDuration($tags).'</span>';
}
add_shortcode('totalDuration','sc_totalDuration');

function sc_count($atts,$content=null){
    $tags = $atts[tags];
    return '<span class="count">'.evd_count($tags).'</span>';
}
add_shortcode('count','sc_count');

function sc_j_img($atts,$content=null) {
    $s = '<a href="'.$content.'" rel="shadowbox"><img class="thumbnail" src="'.$content.'" /></a>';
    return $s;
} 
add_shortcode('img', 'sc_j_img');

function sc_j_file($atts,$content=null) {
	$s = '<a href="'.$content.'">'.basename($content).'</a>';
	return $s;
}
add_shortcode('file', 'sc_j_file');

function sc_listByTag($atts) {
	$tags = $atts[tags];
	$tagArray = explode(',',$tags);
	array_walk($tagArray,'trim_value');
	return evd_postsList($tagArray,'post_tag',evd_option('evd_postListCode'));
}
add_shortcode('listByTag', 'sc_listByTag');

function sc_toggle($atts,$content=null) {
	$title = $atts[title];
	
	if(!$title) $title = 'click to expand';
	if(!$content) $content = 'Lorem ipsum dolor sit amet.';
	
	return "<div class='toggle title'>$title</div><div class='peekaboo' style='display:none;'>$content</div>";
}
add_shortcode('toggle', 'sc_toggle');

function sc_camera($atts) {
	$urls = $atts[urls];
	$urlArray = explode(',',$urls);
	array_walk($urlArray,'trim_value');
	
	$datafx = $atts[datafx];
	if(!$datafx) $datafx = 'curtainSliceRight';
	
	if(is_array($urlArray)){
		$html = '<div class="camera">'."\n";
		foreach($urlArray as $url){
			$html .= "<div data-src=\"$url\" data-fx=\"$datafx\"></div>\n";
		}
		$html .= '</div>'."\n";
		
	} else {
		$html = c('no image URLs provided for Pixedelic Camera',0,true);
	}
	return $html;
}
add_shortcode('camera', 'sc_camera');

function sc_accordion($atts,$content){
	$content = wpautop(trim($content));
	return force_balance_tags("<div class=\"accordion\">$content</div>");
}
add_shortcode('accordion', 'sc_accordion');

function sc_tabs($atts,$content){
	$content = wpautop(trim($content));
	return force_balance_tags("<div class=\"tabs\">$content</div>");
}
add_shortcode('tabs', 'sc_tabs');

function sc_listByTag2($atts) {
	if($atts[tags]) 			$tags = $atts[tags];
	if($atts[categories]) 		$categories = $atts[categories];
	if($atts[title]) 			$title = $atts[title];
	if($atts[caption]) 			$caption = $atts[caption];
	if($atts[thumburl]) 		$thumburl = $atts[thumburl];
	
	if($atts[order]) 			$order = $atts[order];
	else						$order = 'asc';
	
	if($atts[maxposts]) 		$maxposts = $atts[maxposts];
	else						$maxposts = 999;
	
	if($atts[workinprogress]) 	$workinprogress = $atts[workinprogress];
	else						$workinprogress = false;
	
	if($atts[closed]) 			$closed = $atts[closed];
	else						$closed = false;
	
	if($atts[displaycode]) 		$displaycode = $atts[displaycode];
	else						$displaycode = evd_option('evd_postListCode');
    
    if($atts[tiles]) 		    $tiles = $atts[tiles];
	else						$tiles = false;
    
    if(!$atts[displaycode] && $tiles) $displaycode = evd_option('evd_postListTileCode');
	
	return evd_postsList2 ($tags,$categories,$title,$caption,$thumburl,$order,$maxposts,$workinprogress,$closed,$displaycode,$tiles);
}
add_shortcode('listByTag2', 'sc_listByTag2');

function sc_evd_query_posts($atts) {
    if(!$atts[showtitles])     	$atts[showtitles] = true;	
    if(!$atts[order]) 			$atts[order] = 'asc';
	if(!$atts[maxposts])        $atts[maxposts] = 12;
    if(!$atts[dateformat]) 	    $atts[dateformat] = 'd F, Y';
    if(!$atts[offset])          $atts[offset] = 0;
    if(!$atts[paginate])        $atts[paginate] = false;
	
	return evd_query_posts ($atts);
}
add_shortcode('evd_query_posts', 'sc_evd_query_posts');


// Whitelist for comment shortcodes
//---------------------------------

// We don't want commenters to be able to use just any old shortcode, 
// so we remove them all first...
function evd_remove_all_shortcodes() {
  global $shortcode_tags;
  global $temp_shortcode_tags;
  $temp_shortcode_tags = $shortcode_tags;
  remove_all_shortcodes();
}

// But hang onto them for later.
function evd_restore_all_shortcodes() {
  global $shortcode_tags;
  global $temp_shortcode_tags;
  if(!empty($temp_shortcode_tags)) {
    $shortcode_tags = $temp_shortcode_tags;
  }
}

// Then we initiate the shortcodes we want to whitelist for commenters.
function evd_shortcodes_whitelist() {
	add_shortcode('img', 'sc_j_img');
	add_shortcode('file', 'sc_j_file');
}

function init_comment_shortcodes() {
  evd_remove_all_shortcodes();
  evd_shortcodes_whitelist();
  add_filter('comment_text', 'do_shortcode');
}

//add_action('init', 'init_comment_shortcodes');
//add_filter('dynamic_sidebar', 'evd_restore_all_shortcodes');
//add_filter('widget_execphp', 'do_shortcode');
evd_shortcodes_whitelist();


?>