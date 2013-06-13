<?php

// add a few common options for posts and pages
$dc_postmeta['default'] = new dc_meta_box('dc_post_options', 'dc meta');
$dc_postmeta['default'] -> add_text_field('dc_custom_title','Custom meta title','Replaces \'title\' and \'meta name\' in <head>');
$dc_postmeta['default'] -> add_boolean_field('dc_hide_title', 'Hide Title','hide title on is_single()?','true');
$dc_postmeta['default'] -> add_boolean_field('dc_full_width', 'Full Width','hide sidebars and add the .full-width class?','false');
$dc_postmeta['default'] -> add_boolean_field('dc_wpautop', 'Disable Auto-HTML','keep WP from adding html tags?','false');

// add a CSS box for custom post-level CSS
$dc_postmeta['css'] = new dc_meta_box('dc_post_css','dc post-level CSS',array('post','page'),'normal','default');
$dc_postmeta['css'] -> add_code_field('dc_post_css_field','arbitrary CSS','Inline CSS added to header for this post only.','css','//code here');

do_action('dc_postmeta',$dc_postmeta);




// class for creating meta boxes for posts and pages
class dc_meta_box{
    private $handle, $title, $post_types, $context, $priority, $nonce, $fields;
        
    public function __construct($handle='dc_meta_box', $title='dc meta box', $post_types=array('post','page'), $context='side', $priority='default'){
    	$this->handle = $handle;
    	$this->title = $title;
    	$this->post_types = $post_types;
    	$this->context = $context;
    	$this->priority = $priority;
    	$this->nonce = $handle.'_wpnonce';
        add_action( 'add_meta_boxes', array( &$this, 'add_meta_box' ) );
        add_action( 'save_post', array( &$this, 'save_meta' ), 10, 2);
    }
    
    public function add_meta_box(){
    	foreach($this->post_types as $post_type){
        	add_meta_box( $this->handle, $this->title, array( &$this, 'render_post_meta' ), $post_type, $this->context, $this->priority );
		}
    }
    
    public function add_text_field($handle,$title,$description,$default=null) {
        $this->fields[] = array('type'=>'text','handle'=>$handle,'title'=>$title,'description'=>$description,'default'=>$default);
    }
    
    public function add_radio_field($handle,$title,$description,$options=array(),$default=null) {
        $this->fields[] = array('type'=>'radio','handle'=>$handle,'title'=>$title,'description'=>$description,'options'=>$options,'default'=>$default);
    }

    public function add_boolean_field($handle,$title,$description,$default=null) {
        $this->fields[] = array('type'=>'boolean','handle'=>$handle,'title'=>$title,'description'=>$description,'options'=>array('true','false'),'default'=>$default);
    }
    
    public function add_code_field($handle,$title='code field',$description='',$language='css',$default=''){
    	$this->fields[] = array('type'=>'code','language'=>$language,'handle'=>$handle,'title'=>$title,'description'=>$description,'default'=>$default);
    	add_action('admin_enqueue_scripts','enqueue_ace');
    }
    
    public function render_post_meta($post){
    	e(wp_nonce_field( plugin_basename( __FILE__ ), $this->nonce, 1, 0 ));
		foreach($this->fields as $field){
			$value = get_post_meta( $post->ID, $field['handle'], true );
			$this->render_field($field,$value);
		}
    }
    
    private function render_field($field=array(),$value=null){
            
        e('<p class="dc-admin-meta-field '.$field['type'].'-field">');
        e('<label for="'.$field['handle'].'">'.$field['title'].'<br /><em>'.$field['description'].'</em></label><br />');
        switch($field['type']){
            case ('text'):
                e('<input type="text" name="'.$field['handle'].'" id="'.$field['handle'].'" value="'.$value.'"/>');
            break;
            
            case ('radio'):
            case ('boolean'):
            
            	if(isset($field['options'])){
					foreach($field['options'] as $option){
						if($option == $value) $checked = ' checked'; else $checked = '';
						e('<input type="radio" name="'.$field['handle'].'" id="'.$field['handle'].'" value="'.$option.'"'.$checked.' /> '.$option.'<br />');
					}
                }
                
            break;
            
            case ('code'):
				
				e('<div id="'.$field['handle'].'_editor" style="border:1px solid #eee;width:100%;height:200px;position:relative;display:block;">'.$value.'</div>');
				e('<script>');
				e('var '.$field['handle'].' = ace.edit("'.$field['handle'].'_editor"); ');
				e($field['handle'].'.setTheme("ace/theme/textmate"); ');
				e($field['handle'].'.getSession().setMode("ace/mode/'.$field['language'].'");');
				e($field['handle'].'.getSession().on(\'change\', function(e) { document.getElementById(\''.$field['handle'].'\').value = '.$field['handle'].'.getSession().getValue(); });');
				e('</script>');
				e('<textarea name="'.$field['handle'].'" id="'.$field['handle'].'" cols="60" rows="4" tabindex="30" style="width: 100%;display:none;">'.$value.'</textarea>');

            break;
            
        }
        e('</p>');
    }
    
    private function get_post_meta($object){
        foreach ($fields as $field){
            $field['value'] = get_post_meta($object->ID,$field['handle'],true);
        }
    }
    
    public function save_meta($post_id, $post){

		if ( 'page' == $_POST['post_type'] ) { if ( ! current_user_can( 'edit_page', $post_id ) ) return;} 
		else { if ( ! current_user_can( 'edit_post', $post_id ) ) return; }
		
  		if ( ! isset( $_POST[$this->nonce] ) || ! wp_verify_nonce( $_POST[$this->nonce], plugin_basename( __FILE__ ) ) ) return;
		
		foreach($this->fields as $field){
		
		    $meta_value = get_post_meta( $post_id, $field['handle'], true );
			$new_meta_value = sanitize_text_field( $_POST[$field['handle']] );				
	
			if ( $new_meta_value && !$meta_value ){
				add_post_meta( $post_id, $field['handle'], $new_meta_value, true );
			}
			
			else if ( $new_meta_value && $meta_value ){
				update_post_meta( $post_id, $field['handle'], $new_meta_value, $meta_value);
			}
	
			else if ( !$new_meta_value && $meta_value ){
				delete_post_meta( $post_id, $field['handle'], $meta_value );
			}
		}
    }
    
}


?>