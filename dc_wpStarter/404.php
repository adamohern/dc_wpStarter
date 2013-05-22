<?php 

c('Begin 404.php',2);

get_header();

c("dc_renderMarkup(o('content_missing'))",1);
dc_render_markup(apply_filters('content_missing'),o('content_missing')));

get_search_form(); br();

get_footer(); 

c('End 404.php',3);

?>