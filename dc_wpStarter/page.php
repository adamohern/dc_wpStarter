<?php 

c('Begin page.php',2);

get_header();

dc_the_loop('post_format_page');
	
get_footer(); 

c('End page.php',3);

?>