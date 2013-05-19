<?php 

c('Begin 404.php',2);

get_header();

c("dc_renderMarkup(o('contentMissing'))",1);
dc_render_markup(apply_filters('contentMissing'),o('contentMissing')));

get_search_form(); br();

get_footer(); 

c('End 404.php',3);

?>