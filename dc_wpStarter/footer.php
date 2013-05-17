<?php

c('Begin footer.php',2);
e('<footer id="footer" class="source-org vcard copyright h-lists">');
e('<ul id="copyright_etc">');
e('<li><small>&copy;'.date("Y").' '.get_bloginfo('name').'</small></li>');
e('</ul>');

if (o('sidebars-Footer_Widgets')) {
c('Begin footer widgets',2);
if (function_exists('dynamic_sidebar') && dynamic_sidebar('Footer_Widgets')) {} else {};
c('End footer widgets',3);
}

c('o(\'customJS_footer\')');
e('<script type="text/javascript">'."\n".o('customJS_footer')."\n".'</script>');

e('</footer>');
e('</div>'.c('/contentBody',0,true));
e('</div>'.c('/content',0,true)); 

br(3);
  
if ( is_single() || is_page() || is_attachment() ){
	$fullWidth = get_post_meta($post->ID, 'fullWidth');
	if($fullWidth[0]!='true') { 
		get_sidebar(); 
	}
} else {
	get_sidebar(); 
}
  
br(3);

c('/#dc-page-wrap');
e('</div>');

br(3);

c('Begin wp_footer()',1);
wp_footer(); br();
c('End wp_footer()');

br(3);

c('/#everything');
e('</div>');

e('</body>');
e('</html>'); 

c('End footer.php',3);

?>