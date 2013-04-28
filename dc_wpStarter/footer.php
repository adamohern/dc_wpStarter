<?php 

c('Begin footer.php',2);
echo '<footer id="footer" class="source-org vcard copyright h-lists">'."\n";
echo '<ul id="copyright_etc">'."\n";
echo '<li><small>&copy;'.date("Y").' '.get_bloginfo('name').'</small></li>'."\n";
echo '</ul>'."\n";

if (evd_option('evd_sidebars-Footer_Widgets')) {
c('Begin footer widgets',2);
if (function_exists('dynamic_sidebar') && dynamic_sidebar('Footer_Widgets')) {} else {};
c('End footer widgets',3);
}

t(
    array(
        'tag'=>'script',
    	'c'=>'evd_option(\'evd_customJS_footer\')',
		'wrap'=>true,
		'content'=>"\n".evd_option('evd_customJS_footer')."\n"),
	array('type'=>'text/javascript')
);

echo '</footer>'."\n";
echo '</div>'.c('/contentBody',0,true)."\n";
echo '</div>'.c('/content',0,true); 

br(2);
if ( is_single() || is_page() || is_attachment() ){
	$fullWidth = get_post_meta($post->ID, 'fullWidth');
	if($fullWidth[0]!='true') { 
		get_sidebar(); 
	}
} else {
	get_sidebar(); 
}
br(2);

echo '</div>'.c('/pagewrap',0,true)."\n";

c('Begin wp_footer()',2);
wp_footer();
c('End wp_footer()',3);


echo '</div>'.c('/everything',0,true)."\n</body>\n</html>"; 

c('End footer.php',3);

?>