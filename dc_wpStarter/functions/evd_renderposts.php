<?php 

function evd_archiveLoop() {
	c('Begin The Loop (functions.php > evd_archiveLoop)',2); 
	if (have_posts()) {
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		echo "<div class='articles page-$paged'>";
		while (have_posts()) { 
			the_post(); 
			evd_article();
		}
		echo '</div>'; c('/.articles',1);
	} else {
		c('query produced no posts');
		echo '<h2>Not Found</h2>';
	}
	c('End The Loop',3);
}

function evd_article(){
	global $evd_options; global $wp_query;
    $current_post = 'article-'.$wp_query->current_post;
    if($wp_query->current_post % 2 == 0) $evenodd = 'even'; else $evenodd = 'odd';
    $post_format = get_post_format();
	$articleClasses = "clearfix archive $current_post $evenodd $post_format";
	$contentClasses = '';
    if($evd_options['evd_homeListContent']=='excerpt') {
        $articleClasses .= ' evd_postcard';
        $contentClasses = ' squeezeOver';
    }
	
	echo '<article '; post_class($articleClasses); echo ' id="post-'.get_the_ID().'">';
	evd_articleThumb();
	echo '<div class="content '.$contentClasses.'">';
	evd_articleHeader();
	evd_articleContent();
	echo '</div>'; c('/.content',1);
	echo '</article>';
}

function evd_articleThumb($is_archive=true) {
	global $evd_options;
	if(($is_archive && $evd_options['evd_displayThumb_archive']) || (!$is_archive && $evd_options['evd_displayThumb_single'])) {
		$evd_options['hasThumb'] = true;
        echo '<div class="thumbnail">'; br();
        if ( has_post_thumbnail() ) { 
            echo '<a href="'.get_permalink().'">'.get_the_post_thumbnail($page->ID, evd_option('evd_thumbsize_archive')).'</a>'; br();
        }
        echo apply_filters('evd_thumbOverlay',$overlay); br();
        echo '</div>'; c('/.thumbnail'); br();
    
    } else { 
        $evd_options['hasThumb'] = false;
    } 
}

function evd_articleHeader($h1=false,$longmeta=false) {
	global $evd_options;
	if(has_post_thumbnail()) $thumbclass=' class="thumbnail"'; else $thumbclass='';
	
    echo '<header>'."\n";
    if($h1) echo '<h1'; else echo '<h2';
		echo $thumbclass.'><a href="'.get_permalink().'">'.apply_filters('evd_postTitle',get_the_title()).'</a>';
	if($h1) echo '</h1>'; else echo '</h2>'; br();
    echo '<div class="meta">'."\n";
	if($longmeta) {
		c('evd_option(\'evd_longMeta\')',1);
		eval($evd_options['evd_longMeta']);
	} else {
		c('evd_option(\'evd_shortMeta\')',1);
		eval($evd_options['evd_shortMeta']);
	}
    echo "\n".'</div>'.c('/.meta',1,true)."\n";
    echo '</header>';
}

function evd_articleContent() {
	global $evd_options;
	if($evd_options['evd_homeListContent']!='none') { 
		echo '<div class="entry">';
		if($evd_options['evd_homeListContent']=='content') { 
			c('evd_option(\'evd_homeListContent\') == content; displaying the_content()',1);
			the_content(); 
		} else { 
			c('evd_option(\'evd_homeListContent\') == '.$evd_homeListContent.'; displaying the_excerpt()',1);
			the_excerpt(); 
		} 
		echo '</div>'; c('/.entry',1);
	} 
}

function evd_postNav() {
	c('evd_postNav() (functions.php)',1);
	echo '<div class="navigation clearfix">';
	echo '<div class="next-posts">';
	next_posts_link('&laquo; Older Entries',0);
	echo '</div>';
	echo '<div class="prev-posts">';
	previous_posts_link('Newer Entries &raquo;',0);
	echo '</div>';
	echo '</div>';
	c('/evd_postNav()',1);
}

function evd_authorBio() { 
	if (evd_option('evd_displayBio')){
		if ( get_the_author_meta('description') ) {
			$authorBio = get_the_author_meta('description');
			c('Begin evd_authorBio() (evd_renderposts.php)',1);
			echo '<div class="authorBio clearfix '.get_the_author_meta('user_login').'"><div class="avatar">'.get_avatar( get_the_author_meta('ID'), 96 ).'</div><div class="text"><h3>About '.get_the_author_link().'</h3>'.$authorBio.'</div></div>'.c('/.authorBio',0,true);
			c('End evd_authorBio()',1);	
		}
	}
}

?>