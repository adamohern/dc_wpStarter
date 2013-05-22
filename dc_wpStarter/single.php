<?php 

c('Begin single.php',2);

get_header(); 

dc_the_loop('post_format_single');
	
get_footer(); 

c('End single.php',3);

?>