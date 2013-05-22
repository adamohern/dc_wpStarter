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
            array('dc_sidebar',				'dc_get_sidebar'),
            array('dc_google_authorship',	'dc_get_google_authorship'),
            array('dc_author_bio',			'dc_get_author_bio')
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
    
    $class[]='clearfix';
    
    $x = implode(' ',get_post_class($class));
    return apply_filters(__FUNCTION__,$x);
}


function dc_google_authorship_form( $user ) { 
?>
<h3><?php _e("Extra profile information", "blank"); ?></h3>
 
<table class="form-table">
<tr>
<th><label for="gplus_url"><?php _e("Google+ profile URL"); ?></label></th>
<td>
<input type="text" name="gplus_url" id="gplus_url" value="<?php echo esc_attr( get_the_author_meta( 'gplus_url', $user->ID ) ); ?>" class="regular-text" /><br />
<span class="description"><?php _e("e.g. http://plus.google.com/104457182243197908311<br /><em>(Field added by the dc_wpStarter theme</em> for use with Google authorship recognition.)"); ?></span>
</td>
</tr>
</table>
<?php 
}
add_action( 'show_user_profile', 'dc_google_authorship_form' );
add_action( 'edit_user_profile', 'dc_google_authorship_form' );
 
 
function dc_google_authorship_update( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
	update_user_meta( $user_id, 'gplus_url', $_POST['gplus_url'] );
}
add_action( 'personal_options_update', 'dc_google_authorship_update' );
add_action( 'edit_user_profile_update', 'dc_google_authorship_update' );


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

	c('Begin dc_the_loop()',2);
	e("<div class='dc-wrapper dc-the-loop'>");
	e("<div class='dc-liner dc-the-loop-liner'>");
	dc_sidebar('dc-before-the-loop');
	dc_post_nav(o('post_nav_next'),o('post_nav_prev'),'top-nav');

	c('Begin The Loop',1); 

	if (have_posts()) {
		
		br(5);
		
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		e("<div class='dc-wrapper dc-articles page-$paged clearfix'>");
		e("<div class='dc-liner dc-articles-liner'>");
		
		br(5);
		
		while (have_posts()) { 
			the_post(); 
			
			if(is_singular()){
				$disable_wpautop = get_post_meta($post->ID, 'dc_wpautop');
				if ($disable_wpautop) remove_filter('the_content', 'wpautop');
			
				$post_css = get_post_meta($post->ID, 'dc_post_css');
				
				if($post_css[0]) {
					e('<style type="text/css">'."\n".$post_css[0]."\n".'</style>');
				}
			}
			
			$id = 'post-'.get_the_ID();
			
			e('<article id="'.$id.'" class="dc-wrapper '.get_post_class().'">');
			e('<div class="dc-liner">');
			dc_render_markup(apply_filters($format,o($format)));
			e('</div><!--/.post-liner-->');
			e('</article><!--/'.$id.'-->');
			br(2);
		}
	
		br(3);
		
		e('</div>'.c('/.dc-articles-liner',0,1));
		e('</div>'.c('/.dc-articles',0,1));
		
		br(5);
		
	} else {
	
		c('query produced no results');
		echo o('contentMissing');
	
	}

	c('End The Loop',1);

	dc_post_nav(o('post_nav_next'),o('post_nav_prev'),'bottom-nav');
	dc_sidebar('dc-after-the-loop');
	
	e("</div><!--/.dc-the-loop-liner-->");
	e("</div><!--/.dc-the-loop-->");
	c('End dc_the_loop()',3);
	
}


function dc_post_nav($next='next',$prev='prev',$class) {
	if($class) $class = ' '.$class;
	c('dc_post_nav()',1);
	e('<div class="dc-wrapper dc-navigation clearfix$class">');
	e('<div class="dc-liner dc-navigation-liner">');
	e('<div class="dc-next-posts">');
	next_posts_link($next,0);
	e('</div><!--/.dc-next-posts-->');
	e('<div class="dc-prev-posts'.$class.'">');
	previous_posts_link($prev,0);
	e('</div><!--/.dc-prev-posts-->');
	e('</div><!--/.dc-navigation-liner-->');
	e('</div><!--/.dc-navigation-->');
	c('/dc_post_nav()',1);
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
	return $x;
}

?>