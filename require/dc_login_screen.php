<?php

function my_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo o('login_logo_url'); ?>);
            width:<?php echo o('login_logo_width'); ?>;
            height:<?php echo o('login_logo_height'); ?>;
            background-size:100%;
        }
        
        .login #backtoblog {display:none;}
        
        .login #nav {text-align:center;}
        
        <?php echo o('login_css'); ?>
        
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return get_bloginfo( 'description' );
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

?>