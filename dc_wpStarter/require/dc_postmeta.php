<?php

// Based on http://wordpress.org/support/topic/add-an-extra-text-input-field-on-admin-post-page

$dc_default_meta = new dc_meta_box('dc options');
$dc_default_meta -> add_text_field('customTitle','Custom meta title','Replaces \'title\' and \'meta name\' in <head>');
$dc_default_meta -> add_boolean_field('displayTitle', 'Display Title','show title on is_single()?','true');
$dc_default_meta -> add_boolean_field('wpautop', 'Disable Auto-HTML','keep WP from adding <p> tags?','false');


class dc_meta_box{
    private $handle;
    private $fields;
    
    public function __construct($handle){
        add_action( 'admin_menu', array( &$this, 'add_meta_box' ) );
        add_action( 'save_post', array( &$this, 'save_meta', 10, 2 ));
    }
    
    public function add_meta_box(){
        add_meta_box( 'dc-meta-box', $this->handle, array( &$this, 'render_post_meta' ), 'post', 'side', 'high' );
        add_meta_box( 'dc-meta-box', $this->handle, array( &$this, 'render_post_meta' ), 'page', 'side', 'high' );
    }
    
    public function add_text_field($handle,$title,$description,$default=null) {
        $fields[] = array('type'=>'text','handle'=>$handle,'title'=>$title,'description'=>$description,'default'=>$default);
    }
    
    public function add_radio_field($handle,$title,$description,$options=array(),$default=null) {
        $fields[] = array('type'=>'radio','handle'=>$handle,'title'=>$title,'description'=>$description,'options'=>$options,'default'=>$default);
    }

    public function add_boolean_field($handle,$title,$description,$default=null) {
        $fields[] = array('type'=>'radio','handle'=>$handle,'title'=>$title,'description'=>$description,'options'=>array('true','false'),'default'=>$default);
    }
    
    private function render_field($field=array()){
        
        e('<p class="dc-admin-meta-field '.$field['type'].'">');
        
        switch($field['type']){
            case ('text'):
                e('<label for="'.$field['handle'].'">'.$field['title'].'<br /><em>'.$field['description'].'</em></label><br />');
                e('<input type="text" name="'.$field['handle'].'" id="'.$field['handle'].'" value="'.$field['value'].'"/>');
                e('<input type="hidden" name="dc_meta_box_nonce" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />');
            break;
            
            case ('radio' || 'boolean'):
                
                e('<label for="'.$field['handle'].'">'.$field['title'].'</label><br />');
                foreach($field['options'] as $option){
                    if($option = $field['value']) $checked = ' checked'; else $checked = '';
                    e('<input type="radio" name="'.$field['handle'].'" id="'.$field['handle'].'" value="'.$option.'"'.$checked.' /> '.$option.'<br />');
                }
                e('<input type="hidden" name="dc_meta_box_nonce" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />');
            break;
        }
        
        e('</p>');
    }
    
    private function get_post_meta($object){
        foreach ($fields as $field){
            $field['value'] = get_post_meta($object->ID,$field['handle'],true);
        }
    }
    
    private function save_meta(){
        
    }
    
    public function render_post_meta($object){
        

        
    }
    
}

function dc_render_post_meta( $object, $box ) { 
    $duration = get_post_meta( $object->ID, 'duration', true );
    $duration = secondsToHMS($duration);
    $customTitle = get_post_meta( $object->ID, 'customTitle', true );
	$fullWidth = get_post_meta( $object->ID, 'fullWidth', true );
    $hideTitle = get_post_meta( $object->ID, 'hideTitle', true );
    $wpautop = get_post_meta( $object->ID, 'wpautop', true );
}

function dc_save_post_meta_box( $post_id, $post ) {

	if ( !wp_verify_nonce( $_POST['dc_meta_box_nonce'], plugin_basename( __FILE__ ) ) )
		return $post_id;

	if ( !current_user_can( 'edit_post', $post_id ) )
		return $post_id;
    
    if(strtotime($_POST['duration'])){
	$meta_value = get_post_meta( $post_id, 'duration', true );
    	$new_meta_value = strtotime($_POST['duration']);
        $new_meta_value = $new_meta_value - strtotime('00:00'); // convert timestamp to seconds
        if($new_meta_value<86400){
        	if ( $new_meta_value && !$meta_value )
        		add_post_meta( $post_id, 'duration', $new_meta_value, true );
                
            else if ( $new_meta_value && $meta_value )
                update_post_meta( $post_id, 'duration', $new_meta_value, $meta_value);
        
        	else if ( !$new_meta_value && $meta_value )
        		delete_post_meta( $post_id, 'duration', $meta_value );
        }
    }

    $meta_value = get_post_meta( $post_id, 'customTitle', true );
        $new_meta_value = $_POST['customTitle'];
    
        if ( $new_meta_value && !$meta_value )
    		add_post_meta( $post_id, 'customTitle', $new_meta_value, true );
            
        else if ( $new_meta_value && $meta_value )
            update_post_meta( $post_id, 'customTitle', $new_meta_value, $meta_value);
    
    	else if ( !$new_meta_value && $meta_value )
    		delete_post_meta( $post_id, 'customTitle', $meta_value );

    $meta_value = get_post_meta( $post_id, 'fullWidth', true );
    	$new_meta_value = $_POST['fullWidth'];
    
    	if ( 'true' == $new_meta_value )
    		add_post_meta( $post_id, 'fullWidth', $new_meta_value, true );
    
    	elseif ( 'false' == $new_meta_value && $meta_value )
    		delete_post_meta( $post_id, 'fullWidth', $meta_value );
            
    $meta_value = get_post_meta( $post_id, 'hideTitle', true );
        $new_meta_value = $_POST['hideTitle'];
    
    	if ( 'true' == $new_meta_value )
    		add_post_meta( $post_id, 'hideTitle', $new_meta_value, true );
    
    	elseif ( 'false' == $new_meta_value && $meta_value )
    		delete_post_meta( $post_id, 'hideTitle', $meta_value );
            
    $meta_value = get_post_meta( $post_id, 'wpautop', true );
        $new_meta_value = $_POST['wpautop'];
    
        if ( 'true' == $new_meta_value )
    		add_post_meta( $post_id, 'wpautop', $new_meta_value, true );
    
    	elseif ( 'false' == $new_meta_value && $meta_value )
    		delete_post_meta( $post_id, 'wpautop', $meta_value );
}
add_action( 'save_post', 'dc_save_post_meta_box', 10, 2 );






function dc_add_css_box() {
	add_meta_box( 'dc-css-box', 'EvD Post CSS', 'dc_render_post_css', 'post', 'normal', 'default' );
	add_meta_box( 'dc-css-box', 'EvD Page CSS', 'dc_render_post_css', 'page', 'normal', 'default' );
	add_action('admin_enqueue_scripts','enqueue_ace');
}
add_action( 'admin_menu', 'dc_add_css_box' );

function dc_render_post_css( $object, $box ) { 
	$postCSS = get_post_meta( $object->ID, 'postCSS', true )
	
?>
<p>
<label for="postCSS">Post CSS</label><br />
<div id="postCSS_editor" style="border:1px solid #eee;width:100%;height:200px;position:relative;display:block;"><?php echo $postCSS; ?></div>
<script>
var postCSS = ace.edit("postCSS_editor"); 
postCSS.setTheme("ace/theme/textmate"); 
postCSS.getSession().setMode("ace/mode/css");
postCSS.getSession().on('change', function(e) { document.getElementById('postCSS').value = postCSS.getSession().getValue(); });
</script>
<textarea name="postCSS" id="postCSS" cols="60" rows="4" tabindex="30" style="width: 100%;display:none;"><?php echo $postCSS; ?></textarea>
<input type="hidden" name="dc_css_box_nonce" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
</p>
<?php 

}

function dc_save_post_css_box( $post_id, $post ) {

	if ( !wp_verify_nonce( $_POST['dc_css_box_nonce'], plugin_basename( __FILE__ ) ) )
		return $post_id;

	if ( !current_user_can( 'edit_post', $post_id ) )
		return $post_id;

	$meta_value = get_post_meta( $post_id, 'postCSS', true );
	$new_meta_value = $_POST['postCSS'];

	if ( $new_meta_value!='' && !$meta_value )
		add_post_meta( $post_id, 'postCSS', $new_meta_value, true );
		
	else if ( $new_meta_value!='' && $meta_value )
		update_post_meta( $post_id, 'postCSS', $new_meta_value );

	else if ( $meta_value ) delete_post_meta( $post_id, 'postCSS', $meta_value );
}
add_action( 'save_post', 'dc_save_post_css_box', 10, 2 );






function dc_add_shortcode_box() {
	add_meta_box( 'dc-shortcode-box', 'EvD Shortcodes', 'dc_render_post_shortcodes', 'post', 'normal', 'high' );
	add_meta_box( 'dc-shortcode-box', 'EvD Shortcodes', 'dc_render_post_shortcodes', 'page', 'normal', 'high' );
}
add_action( 'admin_menu', 'dc_add_shortcode_box' );

function dc_render_post_shortcodes( $object, $box ) { 
$shortcodes = array(
	'shadowbox image'=>'[img]url[/img]',
	'downloadable file'=>'[file]url[/file]',
    'list of downloads'=>'[downloads urls=&quot;url1,url2&quot; title=&quot;title&quot; caption=&quot;caption&quot; classes=&quot;class1,class2&quot; id=&quot;id&quot;]',
	'list posts by tag (simple)'=>'[dc_query_posts tags=&quot;tag1,tag2&quot;]',
	'list posts by tag (advanced)'=>'[dc_query_posts tags=&quot;tag1,tag2&quot; title=&quot;Title&quot; caption=&quot;Caption&quot; bannerurl=&quot;thumbnail_url&quot; order=&quot;desc&quot;]',
	'collapsable ("toggle") div'=>'[toggle title=&quot;Title&quot;]html[/toggle]',
	'interactive slideshow (Pixedelic Camera)'=>'[camera urls=&quot;imageURL1,imageURL2...&quot; datafx=&quot;optional&quot;]',
	'jquery UI "accordion"'=>'[accordion]<h3><a href=&quot;#&quot;>title1</a></h3><div>content1</div><h3><a href=&quot;#&quot;>title2</a></h3><div>content2</div>[/accordion]',
	'jquery UI "tabs"'=>'[tabs]<ul><li><a href=&quot;#t1&quot;>tab1</a></li><li><a href=&quot;#t2&quot;>tab2</a></li></ul><div id=&quot;t1&quot;>Lorem Ipsum</div><div id=&quot;t2&quot;>Dolor Sit Amet</div>[/tabs]'
);
echo '<table width="100%">';
foreach($shortcodes as $label => $value){
	echo dc_render_shortcode($label,$value);
}
echo '</table>';

}

function dc_render_shortcode($label,$value){
	return "<tr><td width=\"25%\">$label </td><td><input type=\"text\" style=\"width:100%;\" value=\"$value\" onclick=\"this.select()\" /></td></tr>";
}


?>