<?php

// This is a big huge nasty function. But it works.

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
    showbrowselinks       // boolean OR 'prefix*suffix'
    workinprogress        // boolean OR string (e.g. '<em>More coming soon!</em>')
    offset                // int (removes X number of posts from beginning of list)
    paginate              // boolen OR 'previousPageLinkText*nextPageLinkText'
*/

function dc_query_posts ($args) {

    $x = '';
    $x .= c('begin functions/dc_postsList.php > dc_query_posts',1,1);
    
    global $more; $more = 0;    // use global $more so that <--more--> tags work
    global $paged;              // use global $paged so that pagination works properly
    
    if( get_query_var( 'paged' ) ) 
        $paged = get_query_var( 'paged' );
    elseif( get_query_var( 'page' ) ) 
        $paged = get_query_var( 'page' );
    else $paged = 1;
    
	set_query_var( 'paged', $paged );
    set_query_var( 'page', $paged );

    if(!$args['paginate'] && $paged!=1) 
        return c("This query not paginated; there is no page $paged.",1,1);
    
    if(strpos($args['paginate'],'*')!==false){
        
        $linktext = explode('*',$args['paginate']);
        $nextPageLinkText = $linktext[0];
        $previousPageLinkText = $linktext[1];
        
    } else {
        
        $nextPageLinkText = o('post_nav_next');
        $previousPageLinkText = o('post_nav_prev');
    
    }
    
    
    
    
    
    
	// Extract tags/cats from CSV
	$args['tags'] = dc_commas_to_term_array($args['tags'],'post_tag');
    $args['categories'] = dc_commas_to_term_array($args['categories'],'category');
    $args['excludetags'] = dc_commas_to_term_array($args['excludetags'],'post_tag');
    $args['excludecategories'] = dc_commas_to_term_array($args['excludecategories'],'category');
    
    
    
    if($args['types']) {
        $args['types'] = dc_commas_to_type_array($args['types']);
    } else if($args['excludetypes']) {
        $args['types'] = get_post_types();
        $args['excludetypes'] = dc_commas_to_type_array($args['excludetypes']);
        $args['types'] = array_diff($args['types'],$args['excludetypes']);
    }
    
    if($args['bannerurl']=='false' || $args['bannerurl']=='0') 
        $args['bannerurl']=null;
    
    if($args['paginate'] && $paged!=1) 
        $args['offset']=0;
    
    
    
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
    
    if(is_array($args['types'])) 
        $query_args['post_type'] = $args['types'];
    
    if($args['paginate']) 
        $query_args['paged'] = $paged;
    
    
    
    // use $wp_query global so that pagination works properly
    global $wp_query;
    $x .= c('query:'.print_r($query_args,true),1,1);
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
            
            if(get_post_meta( get_the_ID(), 'duration', true )) 
                $totalDuration += get_post_meta( get_the_ID(), 'duration', true );
        	
            $current_post = 'article-'.get_the_ID();
            $list_number = 'list-'.$j;
            
            if($j % 2 == 0) 
                $evenodd = 'even'; 
            else $evenodd = 'odd';
            
            if($j % 3 == 0) 
                $third = ' third'; 
            else $third = '';
            
            $post_format = 'format-'.get_post_format();
            $post_type = 'type-'.get_post_type();
            $articleClasses = "clearfix $current_post $list_number $evenodd$third $post_format $post_type";
            
        	$postsList .= '<li class="'.$articleClasses.'">';
            
            $postsList .= apply_filters('post_format_dc_query_posts',dc_get_render_markup(o('post_format_dc_query_posts')));
            
        	$postsList .= '</li>'."\n";
            
            $j++;
            
        }
    
        $i++;
    
	endwhile;
	
    
    
    
    
    
    
    // Render html
    if($args['classes']) 
        $args['classes'] = " ".$args['classes']; 
    else $args['classes']='';
    
    if($args['bannerurl']) 
        $args['classes'] .= ' hasthumb';
    
    if($args['id']) 
        $args['id'] = 'id="'.$args['id'].'" '; 
    else 
        $args['id']='';
    
	$x .= '<div '.$args['id'].'class="dc_query_posts'.$args['classes'].'">'."\n";
	$x .= '<div class="titleBar clearfix">'."\n";
    
	if($args['bannerurl']) 
        $x .= '<img class="banner" src="'.$args['bannerurl'].'" />'."\n";
	
    $x .= '<div class="titleBlock">';
    
	if($args['title']) 		
        $x .= '<h3>'.$args['title'].$args['totalDuration'].'</h3>';
	
    if($args['caption']) 	
        $x .= '<p>'.$args['caption'].'</p>';
    
    // Build (Browse: link) text
    if($args['showbrowselinks']){
        
        $args['browselinks']='';
        
        if($args['categories']) 
            $args['browselinks'] .= 'categories:'.dc_term_array_to_links($args['categories'],'category');
        
        if($args['categories'] && $args['tags']) 
            $args['browselinks'] .= "; ";
        
        if($args['tags']) 
            $args['browselinks'] .= 'tags:'.dc_term_array_to_links($args['tags'],'post_tag');
        
        $x .= dc_super_boolean($showbrowselinks,$args['browselinks'],'<div class="browselinks">(Browse *)</div>');
        
    }
	
    $x .= '</div><!--.titleBlock-->'."\n";
	$x .= '</div><!--.titleBar-->'."\n";	
    
    $x = apply_filters('dc_query_posts_titleBar',$x);
    
    if($args['paginate'])
        $x .= '<div class="dc-nav-top clearfix"><div class="prev-posts">'.get_previous_posts_link($previousPageLinkText).'</div><div class="next-posts">'.get_next_posts_link($nextPageLinkText).'</div></div>';
    
	$x .= '<ul class="dc_query_posts clearfix">'."\n";
    $x .= $postsList;
	
	if($args['workinprogress']) {
        
        if(gettype($args['workinprogress'])!='string') 
            $args['workinprogress'] = 'Series in progress. More coming soon!';
        
        $x .= '<li class="clearfix workinprogress">'.$args['workinprogress'].'</li>'."\n";
        
	}
	
	$x .= '</ul><!--ul.dc_postsList-->'."\n";
    
    if($args['paginate'])
        $x .= '<div class="dc-nav-bottom clearfix"><div class="prev-posts">'.get_previous_posts_link($previousPageLinkText).'</div><div class="next-posts">'.get_next_posts_link($nextPageLinkText).'</div></div>';
    
	$x .= '</div><!--div.dc_postsList-->'."\n";
	
    
    
    
    
    
    
	// clean up after ourselves
    $wp_query = null; $wp_query = $temp;
	wp_reset_postdata();
    wp_reset_query();


    $x .= c('end functions/dc_postsList.php > dc_postsList2',1,1);
	return apply_filters(__FUNCTION__,$x);
}

?>