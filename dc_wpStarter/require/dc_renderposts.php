<?php 

function dc_archiveLoop() {
	c('Begin The Loop (functions.php > dc_archiveLoop)',2); 
	if (have_posts()) {
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		echo "<div class='articles page-$paged'>";
		while (have_posts()) { 
			the_post(); 
			dc_article();
		}
		echo '</div>'; c('/.articles',1);
	} else {
		c('query produced no posts');
		echo '<h2>Not Found</h2>';
	}
	c('End The Loop',3);
}

function dc_article(){
	global $dc_options; global $wp_query;
    $current_post = 'article-'.$wp_query->current_post;
    if($wp_query->current_post % 2 == 0) $evenodd = 'even'; else $evenodd = 'odd';
    $post_format = get_post_format();
	$articleClasses = "clearfix archive $current_post $evenodd $post_format";
	$contentClasses = '';
    if($dc_options['dc_homeListContent']=='excerpt') {
        $articleClasses .= ' dc_postcard';
        $contentClasses = ' squeezeOver';
    }
	
	echo '<article '; post_class($articleClasses); echo ' id="post-'.get_the_ID().'">';
	dc_articleThumb();
	echo '<div class="content '.$contentClasses.'">';
	dc_articleHeader();
	dc_articleContent();
	echo '</div>'; c('/.content',1);
	echo '</article>';
}

function dc_articleThumb($is_archive=true) {
	global $dc_options;
	if(($is_archive && $dc_options['dc_displayThumb_archive']) || (!$is_archive && $dc_options['dc_displayThumb_single'])) {
		$dc_options['hasThumb'] = true;
        echo '<div class="thumbnail">'; br();
        if ( has_post_thumbnail() ) { 
            echo '<a href="'.get_permalink().'">'.get_the_post_thumbnail($page->ID, dc_option('dc_thumbsize_archive')).'</a>'; br();
        }
        echo apply_filters('dc_thumbOverlay',$overlay); br();
        echo '</div>'; c('/.thumbnail'); br();
    
    } else { 
        $dc_options['hasThumb'] = false;
    } 
}

function dc_articleHeader($h1=false,$longmeta=false) {
	global $dc_options;
	if(has_post_thumbnail()) $thumbclass=' class="thumbnail"'; else $thumbclass='';
	
    echo '<header>'."\n";
    if($h1) echo '<h1'; else echo '<h2';
		echo $thumbclass.'><a href="'.get_permalink().'">'.apply_filters('dc_postTitle',get_the_title()).'</a>';
	if($h1) echo '</h1>'; else echo '</h2>'; br();
    echo '<div class="meta">'."\n";
	if($longmeta) {
		c('dc_option(\'dc_longMeta\')',1);
		eval($dc_options['dc_longMeta']);
	} else {
		c('dc_option(\'dc_shortMeta\')',1);
		eval($dc_options['dc_shortMeta']);
	}
    echo "\n".'</div>'.c('/.meta',1,true)."\n";
    echo '</header>';
}

function dc_articleContent() {
	global $dc_options;
	if($dc_options['dc_homeListContent']!='none') { 
		echo '<div class="entry">';
		if($dc_options['dc_homeListContent']=='content') { 
			c('dc_option(\'dc_homeListContent\') == content; displaying the_content()',1);
			the_content(); 
		} else { 
			c('dc_option(\'dc_homeListContent\') == '.$dc_homeListContent.'; displaying the_excerpt()',1);
			the_excerpt(); 
		} 
		echo '</div>'; c('/.entry',1);
	} 
}

function dc_postNav() {
	c('dc_postNav() (functions.php)',1);
	echo '<div class="navigation clearfix">';
	echo '<div class="next-posts">';
	next_posts_link('&laquo; Older Entries',0);
	echo '</div>';
	echo '<div class="prev-posts">';
	previous_posts_link('Newer Entries &raquo;',0);
	echo '</div>';
	echo '</div>';
	c('/dc_postNav()',1);
}

function dc_authorBio() { 
	if (dc_option('dc_displayBio')){
		if ( get_the_author_meta('description') ) {
			$authorBio = get_the_author_meta('description');
			c('Begin dc_authorBio() (dc_renderposts.php)',1);
			echo '<div class="authorBio clearfix '.get_the_author_meta('user_login').'"><div class="avatar">'.get_avatar( get_the_author_meta('ID'), 96 ).'</div><div class="text"><h3>About '.get_the_author_link().'</h3>'.$authorBio.'</div></div>'.c('/.authorBio',0,true);
			c('End dc_authorBio()',1);	
		}
	}
}

?>