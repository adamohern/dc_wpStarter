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
    showthumbs            // boolean OR wp image size ('thumb' 'medium', 'large', 'dc_thumbnail', 'dc_large', 'dc_huge')
    showbrowselinks       // boolean OR 'prefix*suffix'
    workinprogress        // boolean OR string (e.g. '<em>More coming soon!</em>')
    offset                // int (removes X number of posts from beginning of list)
    paginate              // boolen OR 'previousPageLinkText*nextPageLinkText'
*/

function dc_query_posts ($args) {

    $html = '';
    $html .= c('begin functions/dc_postsList.php > dc_query_posts',1,1);
    
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
    
	// Extract tags/cats from CSV
	$args['tags'] = dc_commasToTermArray($args['tags'],'post_tag');
    $args['categories'] = dc_commasToTermArray($args['categories'],'category');
    $args['excludetags'] = dc_commasToTermArray($args['excludetags'],'post_tag');
    $args['excludecategories'] = dc_commasToTermArray($args['excludecategories'],'category');
    
    if($args['types']) $args['types'] = dc_commasToTypeArray($args['types']);
    else if($args['excludetypes']) {
        $args['types'] = get_post_types();
        $args['excludetypes'] = dc_commasToTypeArray($args['excludetypes']);
        $args['types'] = array_diff($args['types'],$args['excludetypes']);
    }
    
    if($args['bannerurl']=='false' || $args['bannerurl']=='0') $args['bannerurl']=null;
    if($args['showthumbs']=='true' || $args['showthumbs']=='1') $args['showthumbs'] = 'dc_thumbnail';
    if($args['paginate'] && $paged!=1) $args['offset']=0;
    
	// Build query
	$query_args['tax_query'] = array( 'relation' => 'AND' );
	$query_args['tax_query'] = array_merge(
        $query_args['tax_query'], 
        dc_tax_query($args['tags'],'post_tag'),
        dc_tax_query($args['categories'],'category'),
        dc_tax_query($args['excludetags'],'post_tag','NOT IN'),
        dc_tax_query($args['excludecategories'],'category','NOT IN')
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
                if($args['thumburl']) $postsList .= apply_filters('dc_thumb',"<div class=\"thumb $showthumbs\"><a href=\"".get_permalink().'"><img src="'.$args['thumburl'].'" title="'.get_the_title().'" /></a></div>');
                else $postsList .= c('no thumbnail found',0,1);
            }
            if($args['bannerurl']=='first' && $i==$args['offset']) {
                $postsList .= c('using first thumbnail as banner',0,1);
                $args['bannerurl'] = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), $args['showthumbs'] );
                $args['bannerurl'] = $args['bannerurl'][0];
            }
            $postsList .= apply_filters('dc_title',dc_superBoolean($args['showtitles'],'<a href="'.get_permalink().'" target="_blank">'.get_the_title().'</a>','<div class="title"><h4>*</h4></div>'));
            $postsList .= dc_superBoolean($args['showauthors'],get_the_author(),'<div class="author meta">*</div>');
            $postsList .= dc_superBoolean($args['showdates'],get_the_date($dateformat),'<div class="date meta">*</div>');
            $postsList .= dc_superBoolean($args['showtags'],dc_tag_links(),'<div class="tags meta">tags: *</div>');
            $postsList .= dc_superBoolean($args['showcategories'],dc_category_links(),'<div class="categories meta">categories: *</div>');
            $postsList .= dc_superBoolean($args['showdurations'],secondsToMS(get_post_meta( get_the_ID(), 'duration', true )),'<div class="duration meta">(*)</div>');
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
	$html .= '<div '.$args['id'].'class="dc_query_posts'.$args['classes'].'">'."\n";
	$html .= '<div class="titleBar clearfix">'."\n";
	if($args['bannerurl']) $html .= '<img class="thumb" src="'.$args['bannerurl'].'" />'."\n";
	$html .= '<div class="titleBlock">';
	if($args['title']) 		$html .= '<h3>'.$args['title'].$args['totalDuration'].'</h3>';
	if($args['caption']) 	$html .= '<p>'.$args['caption'].'</p>';
    
    // Build (Browse: link) text
    if($args['showbrowselinks']){
        $args['browselinks']='';
        if($args['categories']) $args['browselinks'] .= 'categories:'.dc_termArrayToLinks($args['categories'],'category');
        if($args['categories'] && $args['tags']) { $args['browselinks'] .= "; "; }
        if($args['tags']) $args['browselinks'] .= 'tags:'.dc_termArrayToLinks($args['tags'],'post_tag');
        $html .= dc_superBoolean($showbrowselinks,$args['browselinks'],'<div class="browselinks">(Browse *)</div>');
    }
	
    $html .= '</div><!--.titleBlock-->'."\n";
	$html .= '</div><!--.titleBar-->'."\n";	
    
    if($args['paginate']){
        $html .= '<div class="dc-nav-top clearfix"><div class="prev-posts">'.get_previous_posts_link($previousPageLinkText).'</div><div class="next-posts">'.get_next_posts_link($nextPageLinkText).'</div></div>';
    }
	$html .= '<ul class="dc_query_posts clearfix">'."\n";
    $html .= $postsList;
	
	if($args['workinprogress']) {
        if(gettype($args['workinprogress'])!='string') $args['workinprogress'] = 'Series in progress. More coming soon!';
        $html .= '<li class="clearfix workinprogress">'.$args['workinprogress'].'</li>'."\n";
	}
	
	$html .= '</ul><!--ul.dc_postsList-->'."\n";
    if($args['paginate']){
        $html .= '<div class="dc-nav-bottom clearfix"><div class="prev-posts">'.get_previous_posts_link($previousPageLinkText).'</div><div class="next-posts">'.get_next_posts_link($nextPageLinkText).'</div></div>';
    }
	$html .= '</div><!--div.dc_postsList-->'."\n";
	
	// clean up after ourselves
    $wp_query = null; $wp_query = $temp;
	wp_reset_postdata();
    wp_reset_query();


    $html .= c('end functions/dc_postsList.php > dc_postsList2',1,1);
	return $html;
}

?>