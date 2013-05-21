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

$dc_options['css'] -> set('cssOverrides', array(
    'title'   => 'CSS Overrides',
    'desc'    => 'Enter any custom CSS here to apply it to your theme. Hint: copy/paste this into a <a href="http://notepad-plus-plus.org/">CSS editor</a> for editing.',
    'std'     => htmlspecialchars(

'body{ background-color:#f3f3f3; }

#dc-content{ width:960px; margin:80px auto; background-color:#fff; }
#contentBody {  }
#Main_Sidebar { width:240px; min-height:400px; float:right; }
.dc_get_sidebar .liner { margin:20px; }
.empty_sidebar { font-size:.8em; font-style:italic; color:#999; background-color:#f5f5f5; }
.articles { width:720px; float:left; }

.thumb { width:240px; height:135px; background-color:#eee; float:left; }
.entry-content { float:left; padding:0 20px 0 40px; width:420px; }

h1, h2, h3, h4 { margin:0; }

#footer ul { padding:0; }
#footer li { list-style-type:none; float:left; margin-right:1em; }

a {color: #ff3c00;}
a:hover {color: #f13a09;}

::-moz-selection{background: #ffa200;color: #351a00;}
::selection {background: #ffa200;color: #351a00;}
a:link {-webkit-tap-highlight-color: #ffa200;}
ins {background-color: #ffa200;}
mark {background-color: #ffa200;}'
    
    ),
    
    'type'    => 'css_big',
    'section' => 'css',
    'class'   => 'cssOverrides code'
));   



/* Content
===========================================*/           

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

$dc_options['content'] -> set('postFormatIndex', array(
    'title'   => 'post format for index.php',
    'desc'    => 'HTML to display index posts<br />'.$instructions,
    'std'     => htmlspecialchars(
        
'<article class="[get_post_class]" id="post-[the_ID]">'."\n".
"\t".'<div class="thumb">[the_post_thumbnail link="true"]</div>'."\n".
"\t".'<div class="entry-content">'."\n".
"\t\t".'<h2>[the_title link="true"]</h2>'."\n".
"\t\t".'<p>[the_excerpt]</p>'."\n".
"\t".'</div><!--/.entry-content-->'."\n".
'</article>'
        
    ),
    'type'    => 'html',
    'section' => 'common',
    'class'   => 'code'
)); 


$dc_options['content'] -> set('postFormatArchive', array(
    'title'   => 'post format for archive.php',
    'desc'    => 'HTML to display archive posts<br />'.$instructions,
    'std'     => htmlspecialchars(
        
'<article class="[get_post_class]" id="post-[the_ID]">'."\n".
"\t".'<div class="thumb">[the_post_thumbnail link="true"]</div>'."\n".
"\t".'<div class="entry-content">'."\n".
"\t\t".'<h2>[the_title link="true"]</h2>'."\n".
"\t\t".'<p>[the_excerpt]</p>'."\n".
"\t".'</div><!--/.entry-content-->'."\n".
'</article>'
        
    ),
    'type'    => 'html',
    'section' => 'common',
    'class'   => 'code'
)); 


$dc_options['content'] -> set('postFormatSearch', array(
    'title'   => 'post format for search.php',
    'desc'    => 'HTML to display search posts<br />'.$instructions,
    'std'     => htmlspecialchars(
        
'<article class="[get_post_class]" id="post-[the_ID]">'."\n".
"\t".'<div class="thumb">[the_post_thumbnail link="true"]</div>'."\n".
"\t".'<div class="entry-content">'."\n".
"\t\t".'<h2>[the_title link="true"]</h2>'."\n".
"\t\t".'<p>[the_excerpt]</p>'."\n".
"\t".'</div><!--/.entry-content-->'."\n".
'</article>'
        
    ),
    'type'    => 'html',
    'section' => 'common',
    'class'   => 'code'
)); 
                    

$dc_options['content'] -> set('postFormatSingle', array(
    'title'   => 'post format for single.php',
    'desc'    => 'HTML to display individual posts<br />'.$instructions,
    'std'     => htmlspecialchars(
        
'<article class="[get_post_class]" id="post-[the_ID]">'."\n".
"\t".'<div class="thumb">[the_post_thumbnail size="dc_large"]</div>'."\n".
"\t".'<div class="entry-content">'."\n".
"\t\t".'<h1>[the_title]</h1>'."\n".
"\t\t".'[dc_sidebar handle="Before_Single"]'."\n".
"\t\t".'<p>[the_content]</p>'."\n".
"\t\t".'[dc_sidebar handle="After_Single"]'."\n".
"\t".'</div><!--/.entry-content-->'."\n".
"\t".'[dc_google_authorship]'."\n".
"\t".'[dc_author_bio]'."\n".
'</article>'."\n\n".
'[comments_template]'
        
    ),
    'type'    => 'html',
    'section' => 'common',
    'class'   => 'code'
));   


$dc_options['content'] -> set('postFormatPage', array(
    'title'   => 'post format for page.php',
    'desc'    => 'HTML to display pages<br />'.$instructions,
    'std'     => htmlspecialchars(
        
'<article class="[get_post_class]" id="page-[the_ID]">'."\n".
"\t".'<div class="entry-content">'."\n".
"\t\t".'<h1>[the_title]</h1>'."\n".
"\t\t".'[dc_sidebar handle="Before_Single"]'."\n".
"\t\t".'<p>[the_content]</p>'."\n".
"\t\t".'[dc_sidebar handle="After_Single"]'."\n".
"\t".'</div><!--/.entry-content-->'."\n".
'</article>'
        
    ),
    'type'    => 'html',
    'section' => 'common',
    'class'   => 'code'
)); 


$dc_options['content'] -> set('contentMissing', array(
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


$dc_options['content'] -> set('outdatedBrowser', array(
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


$dc_options['content'] -> set('searchform', array(
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

                                   
$dc_options['content'] -> set('debugMode', array(
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

$dc_options['js'] -> set('customJS', array(
    'section' => 'js',
    'title'   => 'Custom Javascript - head',
    'desc'    => 'Enter valid jquery-compatible Javascript to insert into the header.',
    'type'    => 'js',
    'std'     => ''
));


$dc_options['js'] -> set('customJS_footer', array(
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


$dc_options['js'] -> set('headerJS', array(

    'title'   => 'External Javascript URLs - header',
    'desc'    => 'URLs of external javascript files to be enqueued in wp_header, separated by line break',
    'std'     => '',
    'type'    => 'textarea',
    'section' => 'js'
));


$dc_options['js'] -> set('footerJS', array(
    'title'   => 'External Javascript URLs - footer',
    'desc'    => 'URLs of external javascript files to be enqueued in wp_footer, separated by line break',
    'std'     => '',
    'type'    => 'textarea',
    'section' => 'js'
));





/* Sidebars
===========================================*/


$dc_options['std'] -> set('dc-Main_Sidebar', array(
    'section' => 'sidebars',
    'title'   => 'Main_Sidebar',
    'desc'    => 'Appears at either left or right of #content.',
    'type'    => 'checkbox',
    'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$dc_options['std'] -> set('dc-Header_Widgets', array(
    'section' => 'sidebars',
    'title'   => 'Header_Widgets',
    'desc'    => 'Topmost header on the page, above all content.',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$dc_options['std'] -> set('dc-Banner_All', array(
    'section' => 'sidebars',
    'title'   => 'Banner_All',
    'desc'    => 'Full-width sidebar below Header_Widgets but above #content and Main_Sidebar on home, archive, search, post, and page views.',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$dc_options['std'] -> set('dc-Banner_Home', array(
    'section' => 'sidebars',
    'title'   => 'Banner_Home',
    'desc'    => 'Full-width sidebar below Header_Widgets but above #content and Main_Sidebar on is_home().',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$dc_options['std'] -> set('dc-Banner_Archive', array(
    'section' => 'sidebars',
    'title'   => 'Banner_Archive',
    'desc'    => 'Full-width sidebar below Header_Widgets but above #content and Main_Sidebar on is_archive().',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$dc_options['std'] -> set('dc-Footer_Widgets', array(
    'section' => 'sidebars',
    'title'   => 'Footer_Widgets',
    'desc'    => 'Appears at bottom of #content.',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));		

$dc_options['std'] -> set('dc-Before_Single', array(
    'section' => 'sidebars',
    'title'   => 'Before_Single',
    'desc'    => 'Appears at top of #content on is_single().',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$dc_options['std'] -> set('dc-After_Single', array(
    'section' => 'sidebars',
    'title'   => 'After_Single',
    'desc'    => 'Appears at bottom of #content on is_single().',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$dc_options['std'] -> set('dc-Before_Archive', array(
    'section' => 'sidebars',
    'title'   => 'Before_Archive',
    'desc'    => 'Appears at top of #content on is_archive().',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$dc_options['std'] -> set('dc-After_Archive', array(
    'section' => 'sidebars',
    'title'   => 'After_Archive',
    'desc'    => 'Appears at bottom of #content on is_archive().',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$dc_options['std'] -> set('dc-Before_Home', array(
    'section' => 'sidebars',
    'title'   => 'Before_Home',
    'desc'    => 'Appears at top of #content on is_home().',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));








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

  
$dc_options['std'] -> set('titleSlug', array(
    'title'   => 'Title Slug',
    'desc'    => 'Programmer-friendly site title, used as a class for the <body> element on all pages.',
    'std'     => preg_replace("/[^a-zA-Z0-9]/", "",get_bloginfo('name')),
    'type'    => 'text',
    'section' => 'seo'
));

$dc_options['std'] -> set('authorName', array(
    'title'   => 'Author Name',
    'desc'    => 'Used for copyright and author meta tags.',
    'std'     => get_bloginfo('name'),
    'type'    => 'text',
    'section' => 'seo'
));

$dc_options['std'] -> set('indexSEODescription', array(

    'title'   => 'Index SEO Description',
    'desc'    => '70 chars describing the site. Will be used by default for index and archive pages. On single posts or pages, the excerpt is used.',
    'std'     => '',
    'type'    => 'textarea',
    'section' => 'seo'
));

?>