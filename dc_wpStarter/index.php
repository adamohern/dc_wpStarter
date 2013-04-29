<?php 

get_header();

c('Begin index.php',2);

dc_auxSidebar('Banner_All');

if(is_home()) {
    
    dc_auxSidebar('Before_Home');
    
} else if(is_archive()) {
    
    dc_auxSidebar('Before_Archive');

}

dc_archiveLoop();

dc_postNav();

dc_auxSidebar('After_Archive');
    
c('End index.php',3);

get_footer(); 

?>