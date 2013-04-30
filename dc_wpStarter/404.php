<?php 

query_posts('');
get_header();

c('Begin 404.php',2);

dc_auxSidebar('Before_Archive');

e('<div class="alert">');

e('<h2>404: Page not found.</h2>');

e('<p>\''.get_bloginfo('wpurl').$_SERVER['REQUEST_URI'].'\'</p>');

c('dc_404Message (theme options)'); 
e('<p>'.dc_option('404Message').'</p>');

get_search_form(); br();

e('</div>');

c('Begin 404.php',3);

get_footer(); 

?>