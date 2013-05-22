<?php

c('Begin footer.php',2);

if(is_single()) $full_width = get_post_meta($post->ID, 'dc_full_width');

if (o('dc-sidebar01') && !$full_width) dc_sidebar('dc-sidebar01');
if (o('dc-sidebar02') && !$full_width) dc_sidebar('dc-sidebar02');

if($full_width) c('This post has "dc_full_width" = true. Skipping sidebars.',1);

br(3);

e('<footer id="footer" class="source-org vcard copyright h-lists clearfix">');
e('<ul id="copyright_etc clearfix">');
e('<li><small>&copy;'.date("Y").' '.get_bloginfo('name').'</small></li>');
e('</ul>');

if (o('dc-footer')) {
	dc_sidebar('dc-footer');
}


e('</footer>');
e('</div>'.c('/dc-content-liner',0,1));
e('</div>'.c('/dc-content',0,1)); 

  
br(3);


e('</div>'.c('/#dc-page-wrap',0,1));

br(3);

c('Begin wp_footer()',1);
wp_footer(); br();
c('End wp_footer()');

br(3);

e('</div>'.c('/#dc-everything',0,1));

br(3);

c("dc_render_markup(o('custom_js_footer'));",1);
e('<script>');
dc_render_markup(o('custom_js_footer'));
e('</script>');

br(3);

e('</body>');
e('</html>'); 

c('End footer.php',3);

?>