<?php 

get_header();

c('Begin search.php',2);
evd_auxSidebar('Banner_All');
evd_auxSidebar('Before_Archive');

$allsearch = &new WP_Query("s=$s&showposts=-1"); 
$key = wp_specialchars($s, 1); 
$count = $allsearch->post_count; 
echo '<h2 class="pagetitle">';
echo $count.' results for "<span class="search-terms">'.$key.'</span>"'; 
wp_reset_query();
echo '</h2>';

evd_postNav();
evd_archiveLoop();
evd_postNav();

evd_auxSidebar('After_Archive');

c('End search.php',3);

get_footer(); 

?>