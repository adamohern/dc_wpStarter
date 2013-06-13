<?php
/*
Plugin Name: dc_eval
Plugin URI: http://evd1.tv
Description: Add custom PHP from the wordpress admin panel. BE CAREFUL.
Version: 130613
Author: A-Dawg
License: Whatever.
*/

// evd_eval -------------------------------------------------------
// Insert arbitrary PHP into a sidebar ----------------------------

class evd_eval extends WP_Widget {
	function __construct() {
		parent::WP_Widget( 
						'evd_eval', // Base ID
						'evd_eval', // Name
						array( 'description' => 'Drop arbitrary PHP into a sidebar. WARNING: THIS COULD KILL YOU. Be careful kids.' ) );
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
				if (!empty($instance['code'])){ eval($instance['code']); }
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['code'] = $new_instance['code'];
		return $instance;
	}
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'code' => 'echo "<!--evd_eval-->";' ) );
		$code = $instance['code'];?>
		<p><label for="<?php echo $this->get_field_id('code'); ?>">Code: <input class="widefat" id="<?php echo $this->get_field_id('code'); ?>" name="<?php echo $this->get_field_name('code'); ?>" type="text" value="<?php echo attribute_escape($code); ?>" /></label></p>
<?php }
}
register_widget('evd_eval');




// ADD ACE EDITOR TO SETTINGS PANEL



?>