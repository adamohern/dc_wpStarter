<?php 

query_posts('');
get_header();

c('Begin 404.php',2);

c("dc_renderMarkup(o('contentMissing'))",1);
dc_renderMarkup(o('contentMissing'));

get_search_form(); br();

c('End 404.php',3);

get_footer(); 

?>