<?php 
// adapted from the WP is_active_sidebar() code, but allows for string index
function dc_is_active_sidebar( $index ) {
	$sidebars_widgets = wp_get_sidebars_widgets();
	if ( !empty($sidebars_widgets[$index]) ) return true;
	return false;
}

function dc_auxSidebar($sidebarHandle) {
    c("dc_is_active_sidebar($sidebarHandle)? ...");
	if (dc_is_active_sidebar($sidebarHandle)) {
        c("... Yes.",1);
		c("Begin sidebar dc_auxSidebar('$sidebarHandle') (functions.php)",2);
		echo '<div id="'.$sidebarHandle.'" class="clearfix">'."\n";
		if (dynamic_sidebar($sidebarHandle)) {} else {}
		echo '</div>'.c("/#$sidebarHandle",0,true);
		c("End sidebar '$sidebarHandle'",3);
	} else { c("... No.",1); }
}

function dc_registerSidebar($id,$description) {
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

function dc_registerSidebars() {
	
	if(dc_option('dc_sidebars-Main_Sidebar')) {
	dc_registerSidebar(
		'Main_Sidebar',
		'This is where the magic happens.'
	);
	}
	
	if(dc_option('dc_sidebars-Header_Widgets')) {
	dc_registerSidebar(
		'Header_Widgets',
		'Stretches across entire top of page. This can be set to be static, animate on mouse-over, or disappear completely in the DC Config.'
	);
	}

    if(dc_option('dc_sidebars-Banner_All')) {
	dc_registerSidebar(
		'Banner_All',
		'If you want something to appear huge across the top of every post/page/archive on the site (above the content and Main Sidebar), put \'er here.'
	);
	}

	if(dc_option('dc_sidebars-Banner_Home')) {
	dc_registerSidebar(
		'Banner_Home',
		'If you want something to appear huge across the top of the home page (above the content and Main Sidebar), put \'er here.'
	);
	}
	
	if(dc_option('dc_sidebars-Banner_Archive')) {
	dc_registerSidebar(
		'Banner_Archive',
		'If you want something to appear huge across the top of an archive page (above the content and Main Sidebar), put \'er here.'
	);
	}
	
	if(dc_option('dc_sidebars-Footer_Widgets')) {
	dc_registerSidebar(
		'Footer_Widgets',
		'A place to drop widgets in the site-wide footer.'
	);
	}
	
	if(dc_option('dc_sidebars-Before_Single')) {
	dc_registerSidebar(
		'Before_Single',
		'A place to drop widgets above content on single posts.'
	);
	}
	
	if(dc_option('dc_sidebars-After_Single')) {
	dc_registerSidebar(
		'After_Single',
		'A place to drop widgets below content on single posts.'
	);
	}
	
	if(dc_option('dc_sidebars-Before_Archive')) {
	dc_registerSidebar(
		'Before_Archive',
		'A place to drop widgets above content on lists of posts.'
	);
	}

	if(dc_option('dc_sidebars-After_Archive')) {
	dc_registerSidebar(
		'After_Archive',
		'A place to drop widgets below content on lists of posts.'
	);
	}
	
	if(dc_option('dc_sidebars-Before_Home')) {
	dc_registerSidebar(
		'Before_Home',
		'A place to drop widgets above content on the home page.'
	);
	}
}
add_action( 'widgets_init', 'dc_registerSidebars' );
?>