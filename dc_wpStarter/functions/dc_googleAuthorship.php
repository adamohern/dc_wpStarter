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