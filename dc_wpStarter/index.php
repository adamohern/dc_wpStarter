<?php 

get_header();

c('Begin index.php',2);

evd_auxSidebar('Banner_All');
if(is_home()) {evd_auxSidebar('Before_Home');} else if(is_archive()) {evd_auxSidebar('Before_Archive');}

evd_archiveLoop();

evd_postNav();

evd_auxSidebar('After_Archive');
    
c('End index.php',3);

get_footer(); 

?>