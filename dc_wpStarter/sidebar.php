<?php 

if (dc_is_active_sidebar('Main_Sidebar')) {
	c('Begin sidebar.php',2);
	e('<div id="sidebar">');
	
	if (function_exists('dynamic_sidebar') && dynamic_sidebar('Main_Sidebar')) {} else {}
	
    c('/#sidebar');
	e('</div>');
	c('End sidebar.php',3);
} 

?>