<?php 

c('o(\'debug_mode\') is enabled. Enjoy.',1); 
c('Begin header.php',2); 
c('Header is adapted from HTML5 Boilerplate, with a few additions.',1); 

?>

<!DOCTYPE html>

<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<?php 

e("<title>".dc_archiveTitle()."</title>");

if(is_archive()||is_front_page()) { 
    $description = (strip_tags(o('index_seo_description'))); $descriptionType = 'o(index_seo_description)'; 
} else { 
    $description = strip_tags(get_the_excerpt()); $descriptionType = 'get_the_excerpt()'; 
} 

c('description type: '.$descriptionType,1); 
e('<meta name="description" content="'.$description.'" />'); 

c('o(\'author_name\')',1); 
e('<meta content="'.o('author_name').'" />'); 

c('o(\'author_name\').\' \'.date(\'Y\')',1); 
e('<meta name="Copyright" content="Copyright '.o('author_name').' '.date('Y').'. All rights reserved." />'); 

e('<meta name="viewport" content="width=device-width">');

c('traditional 16x16 favicon - o(\'favicon\')',1);
e('<link href="'.o('favicon').'" rel="shortcut icon" />');

c('iOS\'s Web Clip, 114x114, name it \'apple-touch-icon-precomposed.png\', no transparency - o(\'appleicon\')',1);
e('<link href="'.o('appleicon').'" rel="apple-touch-icon" />');

c('bloginfo(\'pingback_url\')',1);
e('<link href="'.get_bloginfo('pingback_url').'" rel="pingback" />');

e('<link rel="stylesheet" href="'.get_stylesheet_directory_uri().'/reset.css">');
e('<link rel="stylesheet" href="'.get_stylesheet_directory_uri().'/style.css">');

c("apply_filters('dc_css_overrides',o('css_overrides'))",1);
e('<style type="text/css">');
e(apply_filters('dc_css_overrides',o('css_overrides')));
e('</style>');

c('if we have js, hide the body until everything is loaded',1);
e('<script type="text/javascript">document.write(\'<style>#everything { display:none }</style>\');</script>');

br(3);

c('Begin wp_head()',1); 
wp_head(); br();
c('End wp_head()');

br(3);

c("apply_filters('dc_custom_head',o('custom_head'))",1);
e(apply_filters('dc_custom_head',o('custom_head')));

br(3);

e('<script type="text/javascript">');
c("apply_filters('dc_custom_js',o('custom_js'))",1);
e(apply_filters('dc_custom_js',o('custom_js')));
e('</script>');

e("</head>");

br(3);

c("class = implode(' ',get_body_class(o('title_slug')))",1);
e('<body class="'.implode(' ',get_body_class(o('title_slug'))).'">');

c("Admonish IE 7 users to change their ways. o('outdated_browser')",1);
e('<!--[if lt IE 7]>');
dc_render_markup(o('outdated_browser'));
e('<![endif]-->');

c('From Boilerplate:',1);
e('<script>window.jQuery || document.write(\'<script src="js/vendor/jquery-1.9.1.min.js"><\/script>\')</script>');

e('<div id="dc-everything">');

dc_sidebar('dc-header');

e('<div id="dc-page" class="dc-wrapper">');
e('<div id="dc-page-liner" class="dc-liner clearfix">');
 
dc_sidebar('dc-banner-all'); 
if(is_home()){   
    dc_sidebar('dc-banner-home'); 
} else if(is_archive()){ 
    dc_sidebar('dc-banner-archive'); 
} 

$class='clearfix';
$fullWidth = get_post_meta($post->ID, 'dc_full_width'); 
if($fullWidth[0]=='true' && is_singular()) $class.=' dc_full_width';

e('<div id="dc-content" class="dc-wrapper '.$class.'">');
e('<div id="dc-content-liner" class="dc-liner clearfix">');

c('End header.php',3); 
?>