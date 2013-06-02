<?php 

global $dc_sidebars;

$dc_sidebars = array(

	array( 'dc Sidebar 01', 		'dc-sidebar01', 		'Typical Wordpress sidebar.' ),
	array( 'dc Sidebar 02', 		'dc-sidebar02', 		'In case you want a 3-column layout (i.e. second sidebar).' ),
	array( 'dc Header', 			'dc-header', 			'Stretches across entire top of page.' ),
	array( 'dc Banner (all)', 		'dc-banner-all', 		'Spans across the top of everything on the site.' ),
	array( 'dc Banner (home)', 		'dc-banner-home', 		'Spans across the top of the home page.' ),
	array( 'dc Banner (archive)', 	'dc-banner-archive', 	'Spans across the top of archive pages.' ),
	array( 'dc Banner (single)', 	'dc-banner-single', 	'Spans across the top of single posts and pages.' ),
	array( 'dc Footer', 			'dc-footer', 			'A place to drop widgets in the site-wide footer.' ),
	array( 'dc Before Single', 		'dc-before-single', 	'A place to drop widgets above content on single posts.' ),
	array( 'dc After Single', 		'dc-after-single', 		'A place to drop widgets below content on single posts.' ),
	array( 'dc Before Archive', 	'dc-before-the-loop', 	'A place to drop widgets above content on lists of posts.' ),
	array( 'dc After Archive', 		'dc-after-the-loop', 	'A place to drop widgets below content on lists of posts.' )
	
);

foreach($dc_sidebars as $key => $array){
	
	$array['name'] = $array[0];
	unset($array[0]);
	$array['id'] = $array[1];
	unset($array[1]);
	$array['description'] = $array[2];
	unset($array[2]);
	
	$dc_sidebars[$key] = $array;
}


function dc_sidebar($args){
	e(dc_get_sidebar($args));
}
       

function dc_get_sidebar($args){
	
	if(isset($args['handle'])) $handle = $args['handle'];
	else if (is_string($args)) $handle = $args;
	else $handle = '[missing argument]';
	
	if(o('dc-'.$handle)){
	
		if (dc_is_active_sidebar($handle)) { $content .= dc_get_dynamic_sidebar($handle); $class = ' dc-active-sidebar'; } 
		else { 
			$content .= '<p>The sidebar "'.$handle.'" is active, but empty. Add widgets!</p>'."\n".
			c("dc_is_active_sidebar($handle) = false",1,1); 
			$class = ' dc-empty-sidebar';
		}

    
		$x .= c("Begin sidebar dc_get_sidebar('$handle')",2,1);
		$x .= '<div id="'.$handle.'" class="dc-get-sidebar clearfix'.$class.'">'."\n";
		$x .= '<div class="dc-liner">'."\n";
		$x .= $content;
		$x .= "\n".'</div><!--/.dc-liner-->'."\n";
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


function dc_register_sidebar($name,$id,$description) {
	global $dc_sidebars;
	
	$sidebars[$id] = array(
		'name' => $name,
		'id' => $id,
		'description' => $description.' (Invisible until widgets are added.)',
		'before_widget' => '<!-- widget \''.$id.'\' -->'."\n".'<div id="%1$s" class="widget %2$s">',
		'after_widget'=> '</div>'."\n".'<!-- /widget \''.$id.'\' -->'."\n",
		'before_title'=> '<h3>',
		'after_title' => '</h3>'
	);
	
	register_sidebar($sidebars[$id]);
}

function dc_register_sidebars() {
	global $dc_sidebars;
	
	foreach($dc_sidebars as $sidebar){
		if(o($sidebar['id'])) {
			dc_register_sidebar(
				$sidebar['name'],
				$sidebar['id'],
				$sidebar['description']
			);
		}
	}
}
add_action( 'widgets_init', 'dc_register_sidebars' );
?>