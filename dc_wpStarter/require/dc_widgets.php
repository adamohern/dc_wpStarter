<?php

// True to the DC ethos, dc_Blank does nothing at all. That's the point!

class dc_Blank extends WP_Widget {

	function __construct() {
		parent::WP_Widget( 
            'dc_Blank', // Base ID
            'dc_Blank', // Name
            array( 'description' => 'Allows you to blank out the defaults for any sidebar.' ) );
	}

	function widget() {
		c('dc_Blank widget',1);
	}
}
register_widget("dc_Blank");


// dc_image -------------------------------------------------------
// Insert an arbitrary link image into a sidebar -------------------

class dc_image extends WP_Widget {
	function __construct() {
		parent::WP_Widget( 
						'dc_image', // Base ID
						'dc_image', // Name
						array( 'description' => 'Drop an arbitrary image into a sidebar.' ) );
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
				if (!empty($instance['css'])){ $css = ' style="'.$instance['css'].'"'; }
				echo '<!-- widget \'' . $instance['title'] . '\' -->'."\n".'<div class="dc_image" id="'.$this->get_field_id('imageURL').'"'.$css.'>'."\n";
				if ( !empty( $instance['imageURL'] ) && !empty( $instance['linkURL'] ) ) {  
					echo '<a href="'.$instance['linkURL'].'"><img src="'.$instance['imageURL'].'"></a>'."\n"; 
				};
				echo '</div>'."\n".'<!-- /widget \'' . $instance['title'] . '\' -->'."\n\n";
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['imageURL'] = $new_instance['imageURL'];
		$instance['linkURL'] = $new_instance['linkURL'];
		$instance['css'] = $new_instance['css'];
		return $instance;
	}
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'imageURL' => '', 'linkURL' => '', 'css' => '') );
		$imageURL = $instance['imageURL'];
		$linkURL = $instance['linkURL']; 
		$css = $instance['css'];?>
		<p><label for="<?php echo $this->get_field_id('imageURL'); ?>">Image URL: <input class="widefat" id="<?php echo $this->get_field_id('imageURL'); ?>" name="<?php echo $this->get_field_name('imageURL'); ?>" type="text" value="<?php echo attribute_escape($imageURL); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('linkURL'); ?>">Link URL: <input class="widefat" id="<?php echo $this->get_field_id('LinkURL'); ?>" name="<?php echo $this->get_field_name('linkURL'); ?>" type="text" value="<?php echo attribute_escape($linkURL); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('css'); ?>">Custom CSS: <input class="widefat" id="<?php echo $this->get_field_id('css'); ?>" name="<?php echo $this->get_field_name('css'); ?>" type="text" value="<?php echo attribute_escape($css); ?>" /></label></p>
<?php }
}
register_widget('dc_image');


?>