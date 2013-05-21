<?php 

if(is_single()) $fullWidth = get_post_meta($post->ID, 'fullWidth');

if (o('dc-Main_Sidebar') && !$fullWidth){

	dc_sidebar('Main_Sidebar');

	c('End sidebar.php',3);

} else if($fullWidth) {

	c('This post has the fullWidth = true. Skipping sidebar.',1);

} else {
	
	c('Main sidebar disabled.',1);
	
}

?>