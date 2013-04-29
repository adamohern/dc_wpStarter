<?php

// lists recent posts with a given taxonomy
/*  accepted args:
    tags                  // comma-delimited
    categories            // comma-delimited
    types                 // comma-delimited
    excludetags           // comma-delimited
    excludecategories     // comma-delimited
    excludetypes          // comma-delimited
	title                 // string
	caption               // string
	bannerurl             // image src URL OR 'first'
	order = 'asc'         // asc or desc
	maxposts = 12         // int
	classes               // space delimited strings
    id                    // string
    showtitles=true       // boolean OR prefix*suffix
    showcontent           // boolean OR 'excerpt' or 'content' or 'morestring...'
    showauthors           // boolean OR 'prefix*suffix'
    showdates             // boolean OR 'prefix*suffix'
    dateformat='d F, Y'   // PHP date format
    showtags              // boolean OR 'prefix*suffix'
    showcategories        // boolean OR 'prefix*suffix'
    showdurations         // boolean OR 'prefix*suffix'
    showthumbs            // boolean OR wp image size ('thumb' 'medium', 'large', 'evd_thumbnail', 'evd_large', 'evd_huge')
    showbrowselinks       // boolean OR 'prefix*suffix'
    workinprogress        // boolean OR string (e.g. '<em>More coming soon!</em>')
    offset                // int (removes X number of posts from beginning of list)
    paginate              // boolen OR 'previousPageLinkText*nextPageLinkText'
*/
function evd_query_posts ($args) {
    
    global $more; $more = 0;    // use global $more so that <--more--> tags work
    global $paged;              // use global $paged so that pagination works properly
    
    if( get_query_var( 'paged' ) ) $paged = get_query_var( 'paged' );
    elseif( get_query_var( 'page' ) ) $paged = get_query_var( 'page' );
    else $paged = 1;
	set_query_var( 'paged', $paged );
    set_query_var( 'page', $paged );

    if(!$args['paginate'] && $paged!=1) return c("This query not paginated; there is no page $paged.",1,1);
    if(!$args['showtitles']) $args['showtitles'] = true;
    
    if(strpos($args['paginate'],'*')!==false){
        $linktext = explode('*',$args['paginate']);
        $nextPageLinkText = $linktext[0];
        $previousPageLinkText = $linktext[1];
    } else {
        $nextPageLinkText = '&laquo; next';
        $previousPageLinkText = 'previous &raquo;';
    }

    $html = '';
    $html .= c('begin functions/evd_postsList.php > evd_query_posts',1,1);
    
	// Extract tags/cats from CSV
	$args['tags'] = evd_commasToTermArray($args['tags'],'post_tag');
    $args['categories'] = evd_commasToTermArray($args['categories'],'category');
    $args['excludetags'] = evd_commasToTermArray($args['excludetags'],'post_tag');
    $args['excludecategories'] = evd_commasToTermArray($args['excludecategories'],'category');
    
    if($args['types']) $args['types'] = evd_commasToTypeArray($args['types']);
    else if($args['excludetypes']) {
        $args['types'] = get_post_types();
        $args['excludetypes'] = evd_commasToTypeArray($args['excludetypes']);
        $args['types'] = array_diff($args['types'],$args['excludetypes']);
    }
    
    if($args['bannerurl']=='false' || $args['bannerurl']=='0') $args['bannerurl']=null;
    if($args['showthumbs']=='true' || $args['showthumbs']=='1') $args['showthumbs'] = 'evd_thumbnail';
    if($args['paginate'] && $paged!=1) $args['offset']=0;
    
	// Build query
	$query_args['tax_query'] = array( 'relation' => 'AND' );
	$query_args['tax_query'] = array_merge(
        $query_args['tax_query'], 
        evd_tax_query($args['tags'],'post_tag'),
        evd_tax_query($args['categories'],'category'),
        evd_tax_query($args['excludetags'],'post_tag','NOT IN'),
        evd_tax_query($args['excludecategories'],'category','NOT IN')
        );
	$query_args['order'] = $args['order'];
	$query_args['posts_per_page'] = $args['maxposts']+$args['offset'];
    if(is_array($args['types'])) $query_args['post_type'] = $args['types'];
    if($args['paginate']) $query_args['paged'] = $paged;
    
    // use $wp_query global so that pagination works properly
    global $wp_query;
    $html .= c('query:'.print_r($query_args,true),1,1);
    $temp = $wp_query;
    $wp_query = null;
	$wp_query = new WP_Query($query_args);
    
    // run the loop before assembling HTML so we can 
    // collect html, total the durations, and grab the top thumb in the same loop
    $totalDuration = 0;
    $postsList = '';
    $firstthumburl = '';
    $i=0; $j=0;
    while ($wp_query->have_posts()) : $wp_query->the_post();
        if($i>=$args['offset']){
            if(get_post_meta( get_the_ID(), 'duration', true )) $totalDuration += get_post_meta( get_the_ID(), 'duration', true );
        	
            $current_post = 'article-'.get_the_ID();
            $list_number = 'list-'.$j;
            if($j % 2 == 0) $evenodd = 'even'; else $evenodd = 'odd';
            if($j % 3 == 0) $third = ' third'; else $third = '';
            $post_format = 'format-'.get_post_format();
            $post_type = 'type-'.get_post_type();
            $articleClasses = "clearfix $current_post $list_number $evenodd$third $post_format $post_type";
            
        	$postsList .= '<li class="'.$articleClasses.'">';
            
            if($args['showthumbs'] && $args['showthumbs']!='false' && $args['showthumbs']!='0') {
                $args['thumburl']=wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), $args['showthumbs'] );
                $args['thumburl']=$args['thumburl'][0];
                if($args['thumburl']) $postsList .= apply_filters('evd_thumb',"<div class=\"thumb $showthumbs\"><a href=\"".get_permalink().'"><img src="'.$args['thumburl'].'" title="'.get_the_title().'" /></a></div>');
                else $postsList .= c('no thumbnail found',0,1);
            }
            if($args['bannerurl']=='first' && $i==$args['offset']) {
                $postsList .= c('using first thumbnail as banner',0,1);
                $args['bannerurl'] = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), $args['showthumbs'] );
                $args['bannerurl'] = $args['bannerurl'][0];
            }
            $postsList .= apply_filters('evd_title',evd_superBoolean($args['showtitles'],'<a href="'.get_permalink().'" target="_blank">'.get_the_title().'</a>','<div class="title"><h4>*</h4></div>'));
            $postsList .= evd_superBoolean($args['showauthors'],get_the_author(),'<div class="author meta">*</div>');
            $postsList .= evd_superBoolean($args['showdates'],get_the_date($dateformat),'<div class="date meta">*</div>');
            $postsList .= evd_superBoolean($args['showtags'],evd_tag_links(),'<div class="tags meta">tags: *</div>');
            $postsList .= evd_superBoolean($args['showcategories'],evd_category_links(),'<div class="categories meta">categories: *</div>');
            $postsList .= evd_superBoolean($args['showdurations'],secondsToMS(get_post_meta( get_the_ID(), 'duration', true )),'<div class="duration meta">(*)</div>');
            if($args['showcontent'] && $args['showcontent']!='false' && $args['showcontent']!='0') {
                if ($args['showcontent'] == 'excerpt') $postsList .= '<div class="excerpt">'.get_the_excerpt().'</div>';
                else { 
                    if($args['showcontent'] != 'content') $morestring = $args['showcontent']; else $morestring = 'more...';
                    $postsList .= '<div class="content">'.get_the_content($morestring).'</div>'; 
                }
            }
            
        	$postsList .= '</li>'."\n";
            
            $j++;
        }
        $i++;
	endwhile;
    if($totalDuration) $totalDuration = ' <span class="duration">('.secondsToHMS($totalDuration).')</span>';
    else $totalDuration = '';
	
    // Render html
    if($args['classes']) $args['classes'] = " ".$args['classes']; else $args['classes']='';
    if($args['bannerurl']) $args['classes'] .= ' hasthumb';
    if($args['id']) $args['id'] = 'id="'.$args['id'].'" '; else $args['id']='';
	$html .= '<div '.$args['id'].'class="evd_query_posts'.$args['classes'].'">'."\n";
	$html .= '<div class="titleBar clearfix">'."\n";
	if($args['bannerurl']) $html .= '<img class="thumb" src="'.$args['bannerurl'].'" />'."\n";
	$html .= '<div class="titleBlock">';
	if($args['title']) 		$html .= '<h3>'.$args['title'].$args['totalDuration'].'</h3>';
	if($args['caption']) 	$html .= '<p>'.$args['caption'].'</p>';
    
    // Build (Browse: link) text
    if($args['showbrowselinks']){
        $args['browselinks']='';
        if($args['categories']) $args['browselinks'] .= 'categories:'.evd_termArrayToLinks($args['categories'],'category');
        if($args['categories'] && $args['tags']) { $args['browselinks'] .= "; "; }
        if($args['tags']) $args['browselinks'] .= 'tags:'.evd_termArrayToLinks($args['tags'],'post_tag');
        $html .= evd_superBoolean($showbrowselinks,$args['browselinks'],'<div class="browselinks">(Browse *)</div>');
    }
	
    $html .= '</div><!--.titleBlock-->'."\n";
	$html .= '</div><!--.titleBar-->'."\n";	
    
    if($args['paginate']){
        $html .= '<div class="evd-nav-top clearfix"><div class="prev-posts">'.get_previous_posts_link($previousPageLinkText).'</div><div class="next-posts">'.get_next_posts_link($nextPageLinkText).'</div></div>';
    }
	$html .= '<ul class="evd_query_posts clearfix">'."\n";
    $html .= $postsList;
	
	if($args['workinprogress']) {
        if(gettype($args['workinprogress'])!='string') $args['workinprogress'] = 'Series in progress. More coming soon!';
        $html .= '<li class="clearfix workinprogress">'.$args['workinprogress'].'</li>'."\n";
	}
	
	$html .= '</ul><!--ul.evd_postsList-->'."\n";
    if($args['paginate']){
        $html .= '<div class="evd-nav-bottom clearfix"><div class="prev-posts">'.get_previous_posts_link($previousPageLinkText).'</div><div class="next-posts">'.get_next_posts_link($nextPageLinkText).'</div></div>';
    }
	$html .= '</div><!--div.evd_postsList-->'."\n";
	
	// clean up after ourselves
    $wp_query = null; $wp_query = $temp;
	wp_reset_postdata();
    wp_reset_query();


    $html .= c('end functions/evd_postsList.php > evd_postsList2',1,1);
	return $html;
}






// DEPRECATED ****************************************************************************************
// ***************************************************************************************************



// lists recent posts with a given taxonomy
function evd_postsList (
    $tags,
    $taxonomy='post_tag',
    $display='\'<div class="title"><a href="\'.get_permalink($post->ID).\'" title="posted \'.get_the_date(\'d M,Y\').\'">\'.get_the_title($post->ID).\'</a></div>\''
    ) {

	$display = '$html .= '.$display.';';

	$currentID = get_the_ID();
	$myquery['tax_query'] = array(
		'relation' => 'AND'
	);

	foreach($tags as $tag) {
	$myquery['tax_query'][] =
		array(
			'taxonomy' => $taxonomy,
			'terms' => $tag,
			'field' => 'slug'
		);
	}

	if(in_array("Tips and Tricks",$tags)){ $myquery['order'] = 'desc'; } else { $myquery['order'] = 'asc'; }
	$myquery['posts_per_page'] = 99;
	query_posts($myquery);
	
	$html = '<!-- Begin evd_postsList() -->'."\n".'<ul class="evd_postsList">'."\n";
	
	while (have_posts()) : the_post();
	
	$html .= '<li class="clearfix">';
	eval($display);
	$html .= '</li>'."\n";

	endwhile;
	
	$html .= '</ul>'."\n".'<!-- End evd_postsList() -->'."\n";
	
	wp_reset_query();
	return $html;	
}


// lists recent posts with a given taxonomy
function evd_postsList2 (
	$tags,
	$categories,
	$title,
	$caption,
	$thumburl,
	$order = 'asc',
	$maxposts = 999,
	$workinprogress = false,
	$closed = false,
	$displaycode,
    $tiles = false
	) {

    $html = '';
    
	// Extract tags/cats from CSV
    
    $html .= c('begin functions/evd_postsList.php > evd_postsList2',1,1);

	$tags = explode(',',$tags);
	array_walk($tags,'trim_value');
	
    foreach ($tags as $key => $tag) {
        $term = term_exists($tag,'post_tag');
        if ($term == 0 || $term == null) {
            unset ($tags[$key]);
        }
    }
    
    $categories = explode(',',$categories);
	array_walk($categories,'trim_value');
	
    foreach ($categories as $key => $category) {
        $term = term_exists($category,'category');
        if ($term == 0 || $term == null) {
            unset ($categories[$key]);
        }
    }
    
    // Don't bother proceeding if we don't have cats or tags
    if($tags || $categories){
    
        // Build (Browse: link) text
    	$tagLinks = '(Browse: ';
    	$i=1;
    	foreach ($tags as $tag){
            if($url = get_term_link($tag,'post_tag')){ $html .= c('Found tag "'.$tag.'" with url "'.$url.'"',1,1); }
    		$tagLinks .= '<a href="'.get_term_link($tag,'post_tag').'">';
    		$tagLinks .= $tag;
    		$tagLinks .= '</a>';
    		if($i<count($tags)) $tagLinks .= ', ';
    		$i++;
    	}
        foreach ($categories as $category){
            if($url = get_term_link($category,'category')){ $html .= c('Found tag "'.$category.'" with url "'.$url.'"',1,1); }
    		$tagLinks .= '<a href="'.get_term_link($category,'category').'">';
    		$tagLinks .= $category;
    		$tagLinks .= '</a>';
    		if($i<count($categories)) $tagLinks .= ', ';
    		$i++;
    	}
    	$tagLinks .= ')';
    	
    	// Build query
        
        if($tiles) $html .= c('tiles=true',0,1);
    	else $html .= c('tiles=false',0,1);
    	if(!$displaycode && !$tiles) $displaycode = evd_option('evd_postListCode');
        else if(!$displaycode) $displaycode = evd_option('evd_postListTileCode');
    	$displaycode = '$postsList .= '.$displaycode.';';
    
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
        
        foreach($categories as $category) {
    	$myquery['tax_query'][] =
    		array(
    			'taxonomy' => 'category',
    			'terms' => $category,
    			'field' => 'slug'
    		);
    	}
    
    	$myquery['order'] = $order;
    	$myquery['posts_per_page'] = $maxposts;
    	
    	query_posts($myquery);
        
        
        // run the loop first (so we can total the durations)
        
        $totalDuration = 0;
        $postsList = '';
        while (have_posts()) : the_post();
            if(get_post_meta( get_the_ID(), 'duration', true )) $totalDuration += get_post_meta( get_the_ID(), 'duration', true );
        	
        	$postsList .= '<li class="clearfix">';
        	eval($displaycode);
        	$postsList .= '</li>'."\n";
    	endwhile;
    	
    	
        // Render html
    	
        if($tiles) $tilestyle = ' tiles'; else $tilestyle = '';
    	$html .= '<div class="evd_postsList'.$tilestyle.'">'."\n";
    	$html .= '<div class="titleBar clearfix">'."\n";
    	
    	if($thumburl) 	{ 
    		$html .= '<img class="thumb" src="'.$thumburl.'" />'."\n";
    		$hasthumb = ' hasthumb';
    	} else {
    		$hasthumb = '';
    	}
    	
    	$html .= '<div class="titleBlock'.$hasthumb.'">';
    	if($title) 		$html .= '<h3>'.$title.' <span class="duration">('.secondsToHMS($totalDuration).')</span></h3>';
    	if($caption) 	$html .= '<p>'.$caption.'</p>';
    	$html .= '</div><!--.titleBlock-->'."\n";
    	
    	$html .= '</div><!--.titleBar-->'."\n";	
    	
    	$html .= '<ul class="evd_postsList clearfix">'."\n";

        $html .= $postsList;
    	
    	if($workinprogress) $html .= '<li class="workinprogress"><em>Series in progress; more coming soon!</em></li>'."\n";
    	if($tagLinks)$html .= '<li class="browseByTag">'.$tagLinks.'</li>'."\n";
    	
    	$html .= '</ul><!--ul.evd_postsList-->'."\n";
    	$html .= '</div><!--div.evd_postsList-->'."\n";
    	
    	// Finish up
    	
    	wp_reset_query();
    
    } else {
        
        $html .= c('no valid tags or categories specified',1,1);
        
    }
    
    $html .= c('end functions/evd_postsList.php > evd_postsList2',1,1);
	return $html;	
}

?>