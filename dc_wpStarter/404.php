<?php 

query_posts('');
get_header();

c('Begin 404.php',2);

evd_auxSidebar('Before_Archive');

echo '<div class="alert">'; br();
echo '<h2>404: Page not found.</h2>'; br();
echo '<p>\''.get_bloginfo('wpurl').$_SERVER['REQUEST_URI'].'\'</p>'; br();
c('evd_404Message (theme options)'); echo '<p>'.evd_option('evd_404Message').'</p>'; br();
get_search_form(); br();
echo '</div>'; br();

c('Begin 404.php',3);

get_footer(); 

?>