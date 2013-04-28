<?php 

if (evd_is_active_sidebar('Main_Sidebar')) {
	c('Begin sidebar.php',2);
	echo '<div id="sidebar">'."\n";
	
	if (function_exists('dynamic_sidebar') && dynamic_sidebar('Main_Sidebar')) {} else {}
	
	echo '</div>'.c('/#sidebar',0,true);
	c('End sidebar.php',3);
} 

?>