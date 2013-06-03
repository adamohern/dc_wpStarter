<?php

$gplus_url = new dc_user_option(array(
    'type'=>'text',
    'title'=>'Google+ profile URL',
    'handle'=>'gplus_url',
    'description'=>"e.g. http://plus.google.com/104457182243197908311<br /><em>(Field added by the dc_wpStarter theme</em> for use with Google authorship recognition.)",
    'std'=>''
));





// We want to gang all of our theme-related user options together, so we add our own hook
function dc_display_user_options($user){
    
    e('<div class="dc-user-options">');
    e('<h3>dc User Options</h3>');
    e('<p>These options are added by the dc_wpStarter theme, and are only usable within that theme.</p>');
    do_action('dc_display_user_options',$user);
    e('</div><!--/.dc-user-options-->');
    
}
add_action( 'show_user_profile', 'dc_display_user_options' );
add_action( 'edit_user_profile', 'dc_display_user_options' );






class dc_user_option{
    
    private $type, $title, $handle, $description, $std;
    
    public function __construct($args){
        
        $this->type = $args['type'];
        $this->title = $args['title'];
        $this->handle = $args['handle'];
        $this->description = $args['description'];
        $this->std = $args['std'];
        
        add_action( 'dc_display_user_options', array( &$this, 'form' ) );
        add_action( 'dc_display_user_options', array( &$this, 'form' ) );

        add_action( 'personal_options_update', array( &$this, 'update' ) );
        add_action( 'edit_user_profile_update', array( &$this, 'update' ) );

    }
    
    public function form( $user ){

        e($this->get_form( $user ));   
    
    }
    
    public function get_form( $user ){
        
        $x = '<table class="form-table">'."\n";
        $x .= '<tr>'."\n";
        $x .= '<th><label for="'.$this->handle.'">'.$this->title.'</label></th>'."\n";
        $x .= '<td>'."\n";
        
        switch($this->type){
            
            case 'text':
                $x .= '<input type="text" name="'.$this->handle.'" id="'.$this->handle.'" value="'.esc_attr( get_the_author_meta( $this->handle, $user->ID ) ).'" class="regular-text" /><br />'."\n";
            break;
            
        }
        
        $x .= '<span class="description">'.$this->description.'</span>'."\n";
        $x .= '</td>'."\n";
        $x .= '</tr>'."\n";
        $x .= '</table>'."\n";
        
        $x = apply_filters('dc_user_option_form',$x);
        return apply_filters('dc_user_option_form_'.$this->handle,$x);
        
    }
    
    public function update( $user_id ){
        
        if ( !current_user_can( 'edit_user', $user_id ) ) 
            return false;
        
        update_user_meta( $user_id, $this->handle, $_POST[$this->handle] );
        
    }
    
}


?>