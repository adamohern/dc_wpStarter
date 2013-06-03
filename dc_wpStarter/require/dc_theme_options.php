<?php

global $dc_options;

$dc_options['std'] = new dc_theme_options('Theme Options','dc Options','dc_options');
$dc_options['std'] -> add_section('SEO','seo');
$dc_options['std'] -> add_section('Icons','icons');
$dc_options['std'] -> add_section('Sidebars','sidebars');

$dc_options['css'] = new dc_theme_options('Theme CSS','dc CSS','dc_css_options');
$dc_options['css'] -> add_section('CSS','css');

$dc_options['js'] = new dc_theme_options('Theme Javascript','dc Javascript','dc_javascript_options');
$dc_options['js'] -> add_section('Javascript','js');

$dc_options['content'] = new dc_theme_options('Theme Content','dc Content','dc_content_options');
$dc_options['content'] -> add_section('Common Markup','common');
$dc_options['content'] -> add_section('Special Markup','special');


$instructions = '(add supported wordpress template tags in [shortcode] form (e.g. [the_date]) listing arguments by name (e.g. [the_date format="format=\'M d, Y\'"])';









/* CSS
===========================================*/

$dc_options['css'] -> set('css_overrides', array(
    'title'   => 'CSS Overrides',
    'desc'    => 'Enter any custom CSS here to apply it to your theme. Hint: copy/paste this into a <a href="http://notepad-plus-plus.org/">CSS editor</a> for editing.',
    'std'     => htmlspecialchars( 
        
"/* custom CSS goes here */

body{ font-family:'Source Sans Pro' sans-serif; font-weight:300; }

a {color: #ff3c00;}
a:hover {color: #f13a09;}

::-moz-selection{background: #ffa200;color: #351a00;}
::selection {background: #ffa200;color: #351a00;}
a:link {-webkit-tap-highlight-color: #ffa200;}
ins {background-color: #ffa200;}
mark {background-color: #ffa200;}

#searchform #sLabel {display:none;}
#searchform input {   
    -webkit-border-radius: 3px;
    border-radius: 3px;
    border-width: 0;
    border-style: solid;
    border-color:#eee;
    color:#999;
    font-family:'Source Sans Pro' sans-serif;
}

#searchform #s {   
    width:78%;
}
#searchform #searchsubmit{
    width:19%;
    margin-left:1%;
}

#dc-sidebars .dc-liner {padding:0 1em;}
#dc-sidebars .widget {margin-bottom:1em;}

#footer {margin-top:2em;}

@media only screen 
and (min-width : 641px) {
    .dc-the-loop{width:66%;float:left;}
    #dc-sidebars{width:33%;float:left;}
    #dc-sidebar01,#dc-sidebar02{margin-left:1%;}
}

@media only screen 
and (min-width : 1025px) {
    .dc-the-loop{width:60%;}
    #dc-sidebars{width:40%;}
    #dc-sidebar01,#dc-sidebar02{width:49%;float:right;margin-left:1%;max-width:30em;}
    .single article{max-width:40em;margin:0 auto;}
}

@media only screen 
and (min-width : 1281px) {
    .dc-articles article{width:49%;float:left;margin-right:1%}
}"
    
    ),
    
    'type'    => 'css_big',
    'section' => 'css',
    'class'   => 'cssOverrides code'
));   








/* Content
===========================================*/       

$dc_options['content'] -> set('custom_head', array(

    'title'   => htmlspecialchars('Custom <head> html'),
    'desc'    => htmlspecialchars('loaded at the bottom of the <head>, just after wp_head()'),
    'std'     => htmlspecialchars(

        "<!-- Everybody loves custom fonts! -->\n".
        "<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700' rel='stylesheet' type='text/css'>"
        
    ),
    'type'    => 'html',
    'section' => 'common',
    'class'   => 'code'
));

$dc_options['content'] -> set('post_format_index', array(
    'title'   => 'post format for index.php',
    'desc'    => 'HTML to display index posts<br />'.$instructions,
    'std'     => htmlspecialchars(
        
'<div class="thumb">[the_post_thumbnail link="true"]</div>'."\n".
'<div class="entry-content">'."\n".
"\t".'<h2>[the_title link="true"]</h2>'."\n".
"\t".'<p>[the_excerpt]</p>'."\n".
'</div><!--/.entry-content-->'
        
    ),
    'type'    => 'html',
    'section' => 'common',
    'class'   => 'code'
)); 


$dc_options['content'] -> set('post_format_archive', array(
    'title'   => 'post format for archive.php',
    'desc'    => 'HTML to display archive posts<br />'.$instructions,
    'std'     => htmlspecialchars(
        
'<div class="thumb">[the_post_thumbnail link="true"]</div>'."\n".
'<div class="entry-content">'."\n".
"\t".'<h2>[the_title link="true"]</h2>'."\n".
"\t".'<p>[the_excerpt]</p>'."\n".
'</div><!--/.entry-content-->'
        
    ),
    'type'    => 'html',
    'section' => 'common',
    'class'   => 'code'
)); 


$dc_options['content'] -> set('post_format_search', array(
    'title'   => 'post format for search.php',
    'desc'    => 'HTML to display search posts<br />'.$instructions,
    'std'     => htmlspecialchars(
        
'<div class="thumb">[the_post_thumbnail link="true"]</div>'."\n".
'<div class="entry-content">'."\n".
"\t".'<h2>[the_title link="true"]</h2>'."\n".
"\t".'<p>[the_excerpt]</p>'."\n".
'</div><!--/.entry-content-->'
        
    ),
    'type'    => 'html',
    'section' => 'common',
    'class'   => 'code'
)); 
                    

$dc_options['content'] -> set('post_format_single', array(
    'title'   => 'post format for single.php',
    'desc'    => 'HTML to display individual posts<br />'.$instructions,
    'std'     => htmlspecialchars(
        
'<div class="thumb">[the_post_thumbnail size="dc_large"]</div>'."\n".
'<div class="entry-content">'."\n".
"\t".'<h1>[the_title]</h1>'."\n".
"\t".'[dc_sidebar id="dc-before-single"]'."\n".
"\t".'<p>[the_content]</p>'."\n".
"\t".'[dc_sidebar id="dc-after-single"]'."\n".
'</div><!--/.entry-content-->'."\n".
'[dc_google_authorship]'."\n".
'[dc_author_bio]'."\n\n".
'[comments_template]'
        
    ),
    'type'    => 'html',
    'section' => 'common',
    'class'   => 'code'
));   


$dc_options['content'] -> set('post_format_page', array(
    'title'   => 'post format for page.php',
    'desc'    => 'HTML to display pages<br />'.$instructions,
    'std'     => htmlspecialchars(
        
'<div class="entry-content">'."\n".
"\t".'<h1>[the_title]</h1>'."\n".
"\t".'[dc_sidebar id="dc-before-single"]'."\n".
"\t".'<p>[the_content]</p>'."\n".
"\t".'[dc_sidebar id="dc-after-single"]'."\n".
'</div><!--/.entry-content-->'
        
    ),
    'type'    => 'html',
    'section' => 'common',
    'class'   => 'code'
)); 


$dc_options['content'] -> set('post_format_dc_query_posts', array(
    'title'   => 'post format for dc_query_posts.php',
    'desc'    => 'HTML to display custom queries in shortcodes<br />'.$instructions,
    'std'     => htmlspecialchars(
        
'<div class="thumb">[the_post_thumbnail link="true"]</div>'."\n".
'<div class="entry-content">'."\n".
"\t".'<h2>[the_title link="true"]</h2>'."\n".
"\t".'<p>[the_excerpt]</p>'."\n".
'</div><!--/.entry-content-->'
        
    ),
    'type'    => 'html',
    'section' => 'common',
    'class'   => 'code'
)); 


$dc_options['content'] -> set('content_missing', array(
    'title'   => 'post format for 404.php and empty queries',
    'desc'    => 'HTML to display pages<br />'.$instructions,
    'std'     => htmlspecialchars(
        
'<div class="404" id="404">'."\n".
"\t".'<h2>Oops.</h2>'."\n".
"\t".'<p>Well that\'s annoying. Looks like we\'re missing something here.</p>'."\n".
"\t".'<p><small>[request_uri] not found.</small></p>'.
"</div><!-- /#404 -->\n\n".
"[get_search_form]"
        
    ),
    'type'    => 'html',
    'section' => 'common',
    'class'   => 'code'
)); 


$dc_options['content'] -> set('outdated_browser', array(
    'title'   => 'Outdated browser message',
    'desc'    => 'Admonish IE 7 users to upgrade.',
    'std'     => htmlspecialchars(
        
'<p class="chromeframe">
You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.
</p>'
        
    ),
    'type'    => 'html',
    'section' => 'common',
    'class'   => 'code'
)); 


$dc_options['content'] -> set('search_form', array(
    'title'   => 'Search Form',
    'desc'    => 'Renders whenever searchform.php is called.',
    'std'     => htmlspecialchars(
        
'<form action="[bloginfo show="siteurl"]" id="searchform" method="get">
<div>
<label id="sLabel" for="s" class="screen-reader-text">Search:</label>
<input type="search" id="s" name="s" value="Search..." onfocus="if (this.value == \'Search...\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \'Search...\';}"/>
<input type="submit" value="Go" id="searchsubmit" />
</div>
</form>'
        
    ),
    'type'    => 'html',
    'section' => 'common',
    'class'   => 'code'
)); 


$dc_options['content'] -> set('post_nav_next', array(
    'section' => 'common',
    'title'   => 'Post Nav: Next',
    'desc'    => 'Text for pagination links on archive and search pages.',
    'type'    => 'text',
    'std'     => '&laquo; Older'
));

$dc_options['content'] -> set('post_nav_prev', array(
    'section' => 'common',
    'title'   => 'Post Nav: Previous',
    'desc'    => 'Text for pagination links on archive and search pages.',
    'type'    => 'text',
    'std'     => 'Newer &raquo;'
));

                                   
$dc_options['content'] -> set('debug_mode', array(
    'section' => 'special',
    'title'   => 'Debug Mode',
    'desc'    => 'Displays helpful comments explaining the HTML output.',
    'type'    => 'radio',
    'std'     => '1',
    'choices' => array(
        '1' => 'yes, display the comments',
        '0' => 'no, don\'t display comments'
    )
));                       
                                   







/* JS
===========================================*/

$dc_options['js'] -> set('custom_js', array(
    'section' => 'js',
    'title'   => 'Custom Javascript - head',
    'desc'    => 'Enter valid jquery-compatible Javascript to insert into the header.',
    'type'    => 'js',
    'std'     => ''
));


$dc_options['js'] -> set('custom_js_footer', array(
    'section' => 'js',
    'title'   => 'Custom Javascript - foot',
    'desc'    => 'Enter valid jquery-compatible Javascript to insert into the header.',
    'type'    => 'js',
    'std'     => htmlspecialchars(
    
"// Google Analytics: change UA-XXXXX-X to be your site's ID.
var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
g.src='//www.google-analytics.com/ga.js';
s.parentNode.insertBefore(g,s)}(document,'script'));" 
)));


$dc_options['js'] -> set('header_js', array(

    'title'   => 'External Javascript URLs - header',
    'desc'    => 'URLs of external javascript files to be enqueued in wp_header, separated by line break',
    'std'     => '',
    'type'    => 'textarea',
    'section' => 'js'
));


$dc_options['js'] -> set('footer_js', array(
    'title'   => 'External Javascript URLs - footer',
    'desc'    => 'URLs of external javascript files to be enqueued in wp_footer, separated by line break',
    'std'     => '',
    'type'    => 'textarea',
    'section' => 'js'
));










/* Sidebars
===========================================*/


global $dc_sidebars;

foreach($dc_sidebars as $sidebar){
	$dc_options['std'] -> set($sidebar['id'], array(
		'section' => 'sidebars',
		'title' => $sidebar['name'],
		'desc' => $sidebar['description'],
		'type' => 'checkbox',
		'std' => 0
	));
}









/* Icons
===========================================*/

$dc_options['std'] -> set('favicon', array(
    'section' => 'icons',
    'title'   => 'Favicon',
    'desc'    => 'Enter the URL to your custom favicon. It should be 32x32, transparency is ok. PNG works well.',
    'type'    => 'text',
    'std'     => get_bloginfo('wpurl').'/favicon.ico'
));

$dc_options['std'] -> set('appleicon', array(
    'section' => 'icons',
    'title'   => 'Apple Favorite Icon',
    'desc'    => 'Enter the URL to your custom favicon for iOS. It should <a href="http://developer.apple.com/library/ios/#documentation/userexperience/conceptual/mobilehig/IconsImages/IconsImages.html">probably</a> be 114x114. PNG works well.',
    'type'    => 'text',
    'std'     => get_bloginfo('wpurl').'/appleicon.ico'
));

$dc_options['std'] -> set('jqueryui_theme', array(
    'section' => 'icons',
    'title'   => 'jquery UI theme',
    'desc'    => 'Enter the URL to your favorite jquery UI theme.',
    'type'    => 'text',
    'std'     => 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/smoothness/jquery-ui.css'
));











/* SEO Settings
===========================================*/

  
$dc_options['std'] -> set('title_slug', array(
    'title'   => 'Title Slug',
    'desc'    => 'Programmer-friendly site title, used as a class for the <body> element on all pages.',
    'std'     => preg_replace("/[^a-zA-Z0-9]/", "",get_bloginfo('name')),
    'type'    => 'text',
    'section' => 'seo'
));

$dc_options['std'] -> set('author_name', array(
    'title'   => 'Author Name',
    'desc'    => 'Used for copyright and author meta tags.',
    'std'     => get_bloginfo('name'),
    'type'    => 'text',
    'section' => 'seo'
));

$dc_options['std'] -> set('index_seo_description', array(

    'title'   => 'Index SEO Description',
    'desc'    => '70 chars describing the site. Will be used by default for index and archive pages. On single posts or pages, the excerpt is used.',
    'std'     => '',
    'type'    => 'textarea',
    'section' => 'seo'
));

?>