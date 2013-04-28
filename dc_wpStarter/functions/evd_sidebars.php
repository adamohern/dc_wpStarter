<?php 
// adapted from the WP is_active_sidebar() code, but allows for string index
function evd_is_active_sidebar( $index ) {
	$sidebars_widgets = wp_get_sidebars_widgets();
	if ( !empty($sidebars_widgets[$index]) ) return true;
	return false;
}

function evd_auxSidebar($sidebarHandle) {
    c("evd_is_active_sidebar($sidebarHandle)? ...");
	if (evd_is_active_sidebar($sidebarHandle)) {
        c("... Yes.");
		c("Begin sidebar evd_auxSidebar('$sidebarHandle') (functions.php)",2);
		echo '<div id="'.$sidebarHandle.'" class="clearfix">'."\n";
		if (dynamic_sidebar($sidebarHandle)) {} else {}
		echo '</div>'.c("/#$sidebarHandle",0,true);
		c("End sidebar '$sidebarHandle'",3);
	} else { c("... No."); }
}

function evd_registerSidebar($id,$description) {
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

function evd_registerSidebars() {
	
	if(evd_option('evd_sidebars-Main_Sidebar')) {
	evd_registerSidebar(
		'Main_Sidebar',
		'This is where the magic happens.'
	);
	}
	
	if(evd_option('evd_sidebars-Header_Widgets')) {
	evd_registerSidebar(
		'Header_Widgets',
		'Stretches across entire top of page. This can be set to be static, animate on mouse-over, or disappear completely in the EvD Config.'
	);
	}

    if(evd_option('evd_sidebars-Banner_All')) {
	evd_registerSidebar(
		'Banner_All',
		'If you want something to appear huge across the top of every post/page/archive on the site (above the content and Main Sidebar), put \'er here.'
	);
	}

	if(evd_option('evd_sidebars-Banner_Home')) {
	evd_registerSidebar(
		'Banner_Home',
		'If you want something to appear huge across the top of the home page (above the content and Main Sidebar), put \'er here.'
	);
	}
	
	if(evd_option('evd_sidebars-Banner_Archive')) {
	evd_registerSidebar(
		'Banner_Archive',
		'If you want something to appear huge across the top of an archive page (above the content and Main Sidebar), put \'er here.'
	);
	}
	
	if(evd_option('evd_sidebars-Footer_Widgets')) {
	evd_registerSidebar(
		'Footer_Widgets',
		'A place to drop widgets in the site-wide footer.'
	);
	}
	
	if(evd_option('evd_sidebars-Before_Single')) {
	evd_registerSidebar(
		'Before_Single',
		'A place to drop widgets above content on single posts.'
	);
	}
	
	if(evd_option('evd_sidebars-After_Single')) {
	evd_registerSidebar(
		'After_Single',
		'A place to drop widgets below content on single posts.'
	);
	}
	
	if(evd_option('evd_sidebars-Before_Archive')) {
	evd_registerSidebar(
		'Before_Archive',
		'A place to drop widgets above content on lists of posts.'
	);
	}

	if(evd_option('evd_sidebars-After_Archive')) {
	evd_registerSidebar(
		'After_Archive',
		'A place to drop widgets below content on lists of posts.'
	);
	}
	
	if(evd_option('evd_sidebars-Before_Home')) {
	evd_registerSidebar(
		'Before_Home',
		'A place to drop widgets above content on the home page.'
	);
	}
}
add_action( 'widgets_init', 'evd_registerSidebars' );
?>