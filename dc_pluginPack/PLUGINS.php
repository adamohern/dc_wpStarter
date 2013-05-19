google analytics {


$theme_options -> set('analyticsHeading', array(
    'section' => 'seo',
    'title'   => '', // Not used for headings.
    'desc'    => 'Performance Tracking',
    'type'    => 'heading'
));

$theme_options -> set('googleAnalytics', array(
    'title'   => 'Google Analytics',
    'desc'    => 'Paste the block of code provided by Google for performance tracking. This will be inserted in the page header.',
    'std'     => '',
    'type'    => 'html',
    'section' => 'seo'
));

$theme_options -> set('googleVerification', array(
    'title'   => 'Google Site Verification Code',
    'desc'    => 'Speaking of Google, don\'t forget to set your site up: http://google.com/webmasters.',
    'std'     => '',
    'type'    => 'text',
    'section' => 'seo'
));



}



google authorship {

<?php
function evd_googleAuthorship_form( $user ) { ?>
    <h3><?php _e("Extra profile information", "blank"); ?></h3>
     
    <table class="form-table">
    <tr>
    <th><label for="gplus_url"><?php _e("Google+ profile URL"); ?></label></th>
    <td>
    <input type="text" name="gplus_url" id="gplus_url" value="<?php echo esc_attr( get_the_author_meta( 'gplus_url', $user->ID ) ); ?>" class="regular-text" /><br />
    <span class="description"><?php _e("e.g. http://plus.google.com/104457182243197908311<br /><em>(This field is added by the EvD_HTML5_Reset theme</em>.)"); ?></span>
    </td>
    </tr>
    </table>
<?php }
add_action( 'show_user_profile', 'evd_googleAuthorship_form' );
add_action( 'edit_user_profile', 'evd_googleAuthorship_form' );
 
function evd_googleAuthorship_update( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
	update_user_meta( $user_id, 'gplus_url', $_POST['gplus_url'] );
}
add_action( 'personal_options_update', 'evd_googleAuthorship_update' );
add_action( 'edit_user_profile_update', 'evd_googleAuthorship_update' );


function evd_googleAuthorship() {
	if (get_the_author_meta('gplus_url')&&get_the_author_meta('user_firstname')){
		echo '<p class="evd_googleAuthorship"><em><a href="'.get_the_author_meta('gplus_url').'?rel=author">'.get_the_author_meta('user_firstname').'</a> on google+</em></p>';
	}
}
?>


dc_googleAuthorship();

}

EVAL:
<?
widget {

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


}
?>

theme options {

<?

        	/* PHP
		===========================================*/
        
        $this->settings['evd_customPHP'] = array(
			'section' => 'php',
			'title'   => __( 'Custom PHP' ),
			'desc'    => __( 'Enter valid PHP that will run before the page is rendered. All valid WordPress hooks can be used.' ),
			'type'    => 'php_big',
			'std'     => ''
		);
		
		$this->settings['evd_postListCode'] = array(
			'section' => 'php',
			'title'   => __( 'Post List Code' ),
			'desc'    => __( 'Enter valid PHP code for a string defining the content between &lt;li&gt; tags in a evd_postsList list.' ),
			'type'    => 'php',
			'std'     => '\'&#039;&lt;div class=&quot;title&quot;&gt;&lt;a href=&quot;&#039;.get_permalink($post-&gt;ID).&#039;&quot; title=&quot;posted &#039;.get_the_date(&#039;d M,Y&#039;).&#039;&quot;&gt;&#039;.get_the_title($post-&gt;ID).&#039;&lt;/a&gt;&lt;/div&gt;&#039;'
		);
    
}


// INSERT INTO FUNCTIONS.PHP
// Now that we've loaded everything up, run whatever the user's defined in Theme Option > Custom PHP
eval (evd_option('evd_customPHP'));
?>


