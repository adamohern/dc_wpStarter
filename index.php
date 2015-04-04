<?php 

c('Begin index.php',2);

get_header();

dc_the_loop('post_format_index');
    
get_footer(); 

c('End index.php',3);

?>