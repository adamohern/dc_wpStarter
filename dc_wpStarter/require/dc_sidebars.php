<?php 

function dc_sidebar($args){
	e(dc_get_sidebar($args));
}
       
       
function dc_get_sidebar($args){
	
	if(isset($args['handle'])) $handle = $args['handle'];
	else if (is_string($args)) $handle = $args;
	else $handle = '[missing argument]';
	
	if(o('dc-'.$handle)){
	
		if (dc_is_active_sidebar($handle)) { $content .= dc_get_dynamic_sidebar($handle); $class = ' active_sidebar'; } 
		else { 
			$content .= '<p>The sidebar "'.$handle.'" is active, but empty. Add widgets!</p>'."\n".
			c("dc_is_active_sidebar($handle) = false",1,1); 
			$class = ' empty_sidebar';
		}

    
		$x .= c("Begin sidebar dc_get_sidebar('$handle')",2,1);
		$x .= '<div id="'.$handle.'" class="dc_get_sidebar clearfix'.$class.'">'."\n";
		$x .= '<div class="liner">'."\n";
		$x .= $content;
		$x .= "\n".'</div><!--/.liner-->'."\n";
		$x .= "\n".'</div><!--/#'.$handle.'-->'."\n";
		$x .= c("/#$handle",1,1);
		$x .= c("End sidebar '$handle'",3,1);
	
	}

    return apply_filters(__FUNCTION__,$x);
}


// adapted from the WP is_active_sidebar() code, but allows for string index
function dc_is_active_sidebar( $index ) {
	$sidebars_widgets = wp_get_sidebars_widgets();
	if ( !empty($sidebars_widgets[$index]) ) return true;
	return false;
}


function dc_register_sidebar($id,$description) {
	register_sidebar(
		array(
			'name' => $id,
			'id' => $id,
			'description' => $description.' (Invisible until widgets are added.)',
			'before_widget' => '<!-- widget \''.$id.'\' -->'."\n".'<div id="%1$s" class="widget %2$s">',
			'after_widget'=> '</div>'."\n".'<!-- /widget \''.$id.'\' -->'."\n",
			'before_title'=> '<h3>',
			'after_title' => '</h3>'
		)
	);
}

function dc_register_sidebars() {
	
	if(o('dc-Main_Sidebar')) {
	dc_register_sidebar(
		'Main_Sidebar',
		'This is where the magic happens.'
	);
	}
	
	if(o('dc-Header_Widgets')) {
	dc_register_sidebar(
		'Header_Widgets',
		'Stretches across entire top of page. This can be set to be static, animate on mouse-over, or disappear completely in the DC Config.'
	);
	}

    if(o('dc-Banner_All')) {
	dc_register_sidebar(
		'Banner_All',
		'If you want something to appear huge across the top of every post/page/archive on the site (above the content and Main Sidebar), put \'er here.'
	);
	}

	if(o('dc-Banner_Home')) {
	dc_register_sidebar(
		'Banner_Home',
		'If you want something to appear huge across the top of the home page (above the content and Main Sidebar), put \'er here.'
	);
	}
	
	if(o('dc-Banner_Archive')) {
	dc_register_sidebar(
		'Banner_Archive',
		'If you want something to appear huge across the top of an archive page (above the content and Main Sidebar), put \'er here.'
	);
	}
	
	if(o('dc-Footer_Widgets')) {
	dc_register_sidebar(
		'Footer_Widgets',
		'A place to drop widgets in the site-wide footer.'
	);
	}
	
	if(o('dc-Before_Single')) {
	dc_register_sidebar(
		'Before_Single',
		'A place to drop widgets above content on single posts.'
	);
	}
	
	if(o('dc-After_Single')) {
	dc_register_sidebar(
		'After_Single',
		'A place to drop widgets below content on single posts.'
	);
	}
	
	if(o('dc-Before_Archive')) {
	dc_register_sidebar(
		'Before_Archive',
		'A place to drop widgets above content on lists of posts.'
	);
	}

	if(o('dc-After_Archive')) {
	dc_register_sidebar(
		'After_Archive',
		'A place to drop widgets below content on lists of posts.'
	);
	}
	
	if(o('dc-Before_Home')) {
	dc_register_sidebar(
		'Before_Home',
		'A place to drop widgets above content on the home page.'
	);
	}
}
add_action( 'widgets_init', 'dc_register_sidebars' );
?>