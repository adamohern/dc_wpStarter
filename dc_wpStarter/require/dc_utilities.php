<?php 

// loads all theme options into a single array
function dc_load_options(){

    global $dc_options, $dc_options_array;
    
    $dc_options_array = array();
    
    foreach($dc_options as $key => $object){
        $dc_options_array = array_merge($dc_options_array,$object->get_all());
    }

}





// return a theme option by handle
function o($handle){

    global $dc_options_array;
    return $dc_options_array[$handle];

}





// safely load a linebreak-dilimited list of scripts
function dc_enqueue_scripts ( $list, $footer=false ){
    $urls = preg_split ('/$\R?^/m', $list);
    if( is_array($urls) ) {
        foreach($urls as $url) dc_enqueue_script( basename($url), $url, array(), '', $footer );
    }
}




// safely load scripts into WP
function dc_enqueue_script( $handle, $src, $deps='', $ver='', $in_footer='' ){
        wp_deregister_script( $handle );  
		wp_register_script( $handle, $src, $deps, $ver, $in_footer );  
		wp_enqueue_script( $handle );  
}






// safely load stylesheets into WP
function dc_enqueue_style( $handle, $src, $deps='', $ver='', $media='' ){
        wp_deregister_style( $handle );  
		wp_register_style( $handle, $src, $deps, $ver, $media );  
		wp_enqueue_style( $handle );  
}



// load dynamic sidebar into output buffer
function dc_get_dynamic_sidebar($handle){
    ob_start();
    dynamic_sidebar($handle);
    $sidebar_contents = ob_get_clean();
    return $sidebar_contents;
}



// counts number of posts with a given set of comma-separated tags
function dc_count($tags){
    
	$tags = explode(',',$tags);
	array_walk($tags,'trim_value');
	
    foreach ($tags as $key => $tag) {
        $term = term_exists($tag,'post_tag');
        if ($term == 0 || $term == null) {
            unset ($tags[$key]);
        }
    }
    
    if($tags){
    
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
    	
    	wp_reset_query();
    
    } else {}
    
	return $count;	
}






// useful
function trim_value(&$string) { $string = trim($string); }






// converts CSV to array
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






// generates a link for each tag on a given post
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





// generates a link for each category on a given post
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





// searches through an array of terms (e.g. 'post_tag' or 'category') 
// removes any invalid entries before returning the array
function dc_term_exists_array($array=array(),$taxonomy){
    foreach ($array as $key => $item) {
        $term = term_exists($item,$taxonomy);
        if ($term == 0 || $term == null) {
            unset ($array[$key]);
        }
    }
    return $array;
}





// explodes a CSV string and returns an array containing only valid terms
function dc_commasToTermArray($csv,$taxonomy){
    if($csv){
        $array = commasToArray($csv);
        $array = dc_term_exists_array($array,$taxonomy);
        return $array;
    } else {
        return null;
    }
}





// explodes a CSV string and returns an array of valid post types (i.e. video etc)
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





// converts a valid array of terms to an array of valid term links
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





// for parsing shortcode arguments with wildcards
function dc_superBoolean($superboolean,$data,$default='*'){
    if($superboolean && $superboolean != 'false' && $superboolean != '0' && $data){
        if(strpos($superboolean,'*')===false) $superboolean=$default;
        $result = str_replace('*',$data,$superboolean);
        return $result;
    }
}





// query by tag or category with support for optional 'IN', 'NOT IN', or 'AND' operators
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






// elegant echo (short, sweet, adds a newline by default)
function e($string,$newline=1){
    if($newline) echo $string."\n";
    else echo $string;
}





// elegant newline (echoes $n newlines, nice for tidying up output code)
function br($n=0) { 
	for($i==1;$i<=$n;$i++){
		echo "\n"; 
	}
}





// HTML comments have three modes:
// 0 : <!-- comment -->
// 1 : <!-- comment -->\n
// 2 : [begin code block]
// 3 : [end code block]

function c($comment='',$mode=0,$return=false) {

    if ( o('debugMode') == 1 ) {
        $source = debug_backtrace();
        $comment = basename( $source[0]['file'] ).' line '.$source[0]['line'].': '.$comment;
        
        $l = strlen($comment); 
        if($l%2==0) $d=1; //even numbered strings need a compensation character
        $w = 100;
        $s = '/';
	
		if ( $mode == 0 ) 
		{
			$html = '<!-- '.$comment.' -->';
		} else if ( $mode == 1 ) 
		{
			$html = '<!-- '.$comment.' -->'."\n";
		} 
        else if ( $mode == 2 ) 
		{
			$html = "\n\n\n".'<!-- ';
			for($i=1; $i<$w; $i++) { $html .= $s; }
			$html .= ' -->'."\n".'<!-- ';
			for($i=1; $i<($w-$l)/2; $i++) { $html .= $s; }
			$html .= $comment;
			for($i=1; $i<($w-$l)/2+$d; $i++) { $html .= $s; }
			$html .= ' -->'."\n\n\n";
		}
		else if ( $mode == 3 ) 
		{
			$html = "\n\n\n".'<!-- ';
			for($i=1; $i<($w-$l)/2; $i++) { $html .= $s; }
			$html .= $comment;
			for($i=1; $i<($w-$l)/2+$d; $i++) { $html .= $s; }
			$html .= ' -->'."\n".'<!-- ';
			for($i=1; $i<$w; $i++) { $html .= $s; }
			$html .= ' -->'."\n\n\n";
		}

		if ($return) return $html;
		else echo $html;
	}
}

?>