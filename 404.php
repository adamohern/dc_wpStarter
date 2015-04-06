<?php 

c('Begin 404.php',2);

get_header();

e('<div class="dc-wrapper dc-the-loop">');
c("dc_renderMarkup(o('content_missing'))",1);
dc_render_markup(apply_filters('content_missing',o('content_missing')));
e('</div><!--/.dc-the-loop-->');

get_footer(); 

c('End 404.php',3);

?>