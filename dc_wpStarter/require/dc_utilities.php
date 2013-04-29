<?php 

function dc_count($tags){
    
    // Extract tags/cats from CSV

	$tags = explode(',',$tags);
	array_walk($tags,'trim_value');
	
    foreach ($tags as $key => $tag) {
        $term = term_exists($tag,'post_tag');
        if ($term == 0 || $term == null) {
            unset ($tags[$key]);
        }
    }
    
    if($tags){
    	
    	// Build query
    
    	$myquery['tax_query'] = array(
    		'relation' => 'AND'
    	);
    
    	foreach($tags as $tag) {
    	$myquery['tax_query'][] =
    		array(
    			'taxonomy' => 'post_tag',
    			'terms' => $tag,
    			'field' => 'slug'
    		);
    	}
    
    	$myquery['posts_per_page'] = 99999;
    	
    	query_posts($myquery);
        
        $count = 0;
        while (have_posts()) : the_post();
            $count++;    
        endwhile;
    	
    	// Finish up
    	wp_reset_query();
    
    } else {}
    
	return $count;	

}

function dc_get_duration($disp=2,$before='',$after='') {
    if(get_post_meta( get_the_ID(), 'duration', true )){
        $duration = get_post_meta( get_the_ID(), 'duration', true );
        if($disp==1){
            $duration = $duration;
        } else if($disp==2){
            $duration = secondsToMS($duration);
        } else if($disp==3){
            $duration = secondsToHMS($duration);
        }
        return c('get_post_meta "duration" for post id "'.$id.'" (dc_renderposts.php)',0,1).$before.$duration.$after;
    } else { return c('get_post_meta "duration" for post id "'.$id.'" returned nothing (dc_renderposts.php)',0,1); }
}

function dc_totalDuration($tags){
    
	// Extract tags/cats from CSV

	$tags = explode(',',$tags);
	array_walk($tags,'trim_value');
	
    foreach ($tags as $key => $tag) {
        $term = term_exists($tag,'post_tag');
        if ($term == 0 || $term == null) {
            unset ($tags[$key]);
        }
    }
    
    if($tags){
    	
    	// Build query
    
    	$myquery['tax_query'] = array(
    		'relation' => 'AND'
    	);
    
    	foreach($tags as $tag) {
    	$myquery['tax_query'][] =
    		array(
    			'taxonomy' => 'post_tag',
    			'terms' => $tag,
    			'field' => 'slug'
    		);
    	}
    
    	$myquery['posts_per_page'] = 99999;
    	
    	query_posts($myquery);
        
        $totalDuration = 0;
        while (have_posts()) : the_post();
            if(get_post_meta( get_the_ID(), 'duration', true )) $totalDuration += get_post_meta( get_the_ID(), 'duration', true );
    	endwhile;
    	
    	// Finish up
    	wp_reset_query();
    
    } else {}
    
	return secondsToHMS($totalDuration);	

}

function secondsToHMS($duration){
    $seconds = sprintf("%02d",$duration % 60);
    $duration = ($duration - $seconds) / 60;
    $minutes = sprintf("%02d",$duration % 60);
    $hours = sprintf("%02d",($duration - $minutes) / 60);
    $duration = "$hours:$minutes:$seconds";
    
    return $duration;
}

function secondsToMS($duration){
    $seconds = sprintf("%02d",$duration % 60);
    $duration = ($duration - $seconds) / 60;
    $minutes = sprintf("%02d",$duration % 60);
    $hours = sprintf("%02d",($duration - $minutes) / 60);
    if($hours==0) $duration = "$minutes:$seconds";
    else $duration = "$hours:$minutes:$seconds";
    
    return $duration;
}

function trim_value(&$string) { $string = trim($string); }

function commasToArray($wholeString){
		if (strpos($wholeString,',')) {$pieces = explode(',',$wholeString); foreach($pieces as &$piece) { $piece = trim($piece); }}
		else {if($wholeString) { $pieces[] = trim($wholeString); } else { $pieces[] = ''; } }
		return $pieces;
}

// Useful for grabbing the slug of a tag by title (http://wordpress.org/support/topic/get-tag-slug#post-800335)
function dc_get_tag_slug($title) {
	global $wpdb;
	$slug = $wpdb->get_var("SELECT slug FROM $wpdb->terms WHERE name='$title'");
	return $slug;
}

function dc_tag_links(){
    $tags = get_the_tags();
    if($tags){
    foreach ($tags as $tag){
        $tag_link = get_tag_link($tag->term_id);	
    	$html .= "<a href='{$tag_link}' title='{$tag->name} Tag' class='{$tag->slug}'>{$tag->name}</a> ";
    }
    return $html;
    }
}

function dc_category_links(){
    $categories = get_the_category();
    if($categories){
    foreach ($categories as $category){
        $category_link = get_category_link($category->term_id);    
    	$html .= "<a href='{$category_link}' title='{$category->name} Tag' class='{$category->slug}'>{$category->name}</a> ";
    }
    return $html;
    }
}

// searches through an array of terms (e.g. 'post_tag' or 'category') and removes any invalid entries before returning the array
function dc_term_exists_array($array=array(),$taxonomy){
    foreach ($array as $key => $item) {
        $term = term_exists($item,$taxonomy);
        if ($term == 0 || $term == null) {
            unset ($array[$key]);
        }
    }
    return $array;
}

function dc_commasToTermArray($csv,$taxonomy){
    if($csv){
        $array = commasToArray($csv);
        $array = dc_term_exists_array($array,$taxonomy);
        return $array;
    } else {
        return null;
    }
}

function dc_commasToTypeArray($csv){
    if($csv){
        $array = commasToArray($csv);
        foreach($array as $key => $item){
            if(!post_type_exists($item)) unset($array[$key]);
        }
        return $array;
    } else {
        return null;
    }
}

function dc_termArrayToLinks($array=array(),$taxonomy){
    if(is_array($array)){
        $links = '';
        $i=1;
    	foreach ($array as $item){
            if($url = get_term_link($item,$taxonomy)){ 
                $links .= c('Found item "'.$item.'" with url "'.$url.'"',1,1); 
        		$links .= '<a href="'.get_term_link($item,$taxonomy).'">';
        		$links .= $item;
        		$links .= '</a>';
        		if($i<count($array)) $links .= ', ';
            }
    		$i++;
    	}
        return $links;
    }
}

function dc_superBoolean($superboolean,$data,$default='*'){
    if($superboolean && $superboolean != 'false' && $superboolean != '0' && $data){
        if(strpos($superboolean,'*')===false) $superboolean=$default;
        $result = str_replace('*',$data,$superboolean);
        return $result;
    }
}

function dc_tax_query($terms=array(),$taxonomy,$operator=null){
    if(is_array($terms)){
        foreach($terms as $term) {
            if($operator){
        	    $query[] =
            		array(
            			'taxonomy' => $taxonomy,
            			'terms' => $term,
            			'field' => 'slug',
                        'operator' => $operator
            		);
            } else {
                $query[] =
                	array(
            			'taxonomy' => $taxonomy,
            			'terms' => $term,
            			'field' => 'slug',
            		);
            }
    	}
        return $query;
    } else {
        $query = array();
        return $query;
    }
}

// renders an arbitrary HTML tag with arbitrary attributes
// atts: c, selfclose, return, content
function t($atts,$tags=array()){
	$html = '';
	if($atts['c']) $html .= c($atts['c'],0,true);
	$html .= "<";
	if($atts['tag']) $html .= $atts['tag'];
	if(is_array($tags)){
		foreach($tags as $tag => $val){
			$html .= " $tag=\"$val\"";
		}
	}
	if($atts['wrap']) {
		$html .= ">";
		if($atts['content']) $html .= $atts['content'];
		$html .= "</";
		if($atts['tag']) $html .= $atts['tag'];
		$html .= ">\n";
	} else {
		$html .= " />\n"; 
	}
	if($return) return $html;
	else echo $html;
}

function e($string,$newline=1){
    if($newline) echo $string."\n";
    else echo $string;
}

// If debug mode is turned on in the theme settings, html comments in the theme are displayed
$dc_debugMode = dc_option('debugMode');
function c($comment='',$mode=0,$return=false) {
	global $dc_debugMode;
	$l = strlen($comment); 
	if($l%2==0) $d=1; //even numbered strings need a compensation character
	$w = 100;
	$s = '*';
	
	if ( $dc_debugMode == 1 ) {
		if ( $mode == 0 ) 
		{
			$html = '<!-- '.$comment.' -->';
		} else if ( $mode == 1 ) 
		{
			$html = '<!-- '.$comment.' -->'."\n";
		} 
		else if ( $mode == 2 ) 
		{
			$html = "\n\n".'<!-- ';
			for($i=1; $i<($w-$l)/2; $i++) { $html .= $s; }
			$html .= $comment;
			for($i=1; $i<($w-$l)/2+$d; $i++) { $html .= $s; }
			$html .= ' -->'."\n".'<!-- ';
			for($i=1; $i<$w; $i++) { $html .= $s; }
			$html .= ' -->'."\n\n";
		}
		else if ( $mode == 3 ) 
		{
			$html = "\n\n".'<!-- ';
			for($i=1; $i<$w; $i++) { $html .= $s; }
			$html .= ' -->'."\n".'<!-- ';
			for($i=1; $i<($w-$l)/2; $i++) { $html .= $s; }
			$html .= $comment;
			for($i=1; $i<($w-$l)/2+$d; $i++) { $html .= $s; }
			$html .= ' -->'."\n\n";
		}
		
		if ($return) return $html;
		else echo $html;
	}
}

function br($n=0) { 
	for($i==1;$i<=$n;$i++){
		echo "\n"; 
	}
}

?>