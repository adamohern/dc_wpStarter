	<?php
c('Begin comments.php',2);

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');

if ( post_password_required() ) { echo 'This post is password protected. Enter the password to view comments.'; return; }

if ( have_comments() ) {

	echo '<h2 id="comments">'; comments_number('No Responses', 'One Response', '% Responses' ); echo '</h2>'; br();
	
	echo '<div class="navigation">'; br();
	echo '<div class="next-posts">'; previous_comments_link(); echo '</div>'; br();
	echo '<div class="prev-posts">'; next_comments_link(); echo '</div>'; br();
	echo '</div>'; br(2);
	
	echo '<ol class="commentlist">'; br();
	wp_list_comments();
	echo '</ol>'; br(2);
	
	echo '<div class="navigation">'; br();
	echo '<div class="next-posts">'; previous_comments_link(); echo '</div>'; br();
	echo '<div class="prev-posts">'; next_comments_link(); echo '</div>'; br();
	echo '</div>'; br();
	
} else { 

	if ( comments_open() ) {
		c('comments are open, but there are no comments',1); br();
	} else { 
		echo '<p>Comments are closed.</p>'; br();
	}
	
} 

if ( comments_open() ) {

    echo '<div id="respond">'; br();
    echo '<h2>'; comment_form_title( 'Leave a Reply', 'Leave a Reply to %s' ); echo '</h2>'; br();
    echo '<div class="cancel-comment-reply">';
    cancel_comment_reply_link();
    echo '</div>';
    
    if ( get_option('comment_registration') && !is_user_logged_in() ) {
        echo '<p>You must be <a href="'.wp_login_url( get_permalink() ).'">logged in</a> to post a comment.</p>';
    } else {
        echo '<form action="'.get_option('siteurl').'/wp-comments-post.php" method="post" id="commentform">';
        if ( is_user_logged_in() ) {
        	echo '<p>Logged in as <a href="'.get_option('siteurl').'/wp-admin/profile.php">'.$user_identity.'</a>.';
			echo '<a href="'.wp_logout_url(get_permalink()).'" title="Log out of this account">Log out &raquo;</a></p>'; br();
		} else {
            echo '<div>'; br();
            echo '<input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" '; if ($req) echo "aria-required='true'"; echo ' />'; br();
            echo '<label for="author">Name <?php if ($req) echo "(required)"; ?></label>'; br();
            echo '</div>'; br();
            
            echo '<div>'; br();
            echo '<input type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2"'; if ($req) echo "aria-required='true'"; echo ' />'; br();
            echo '<label for="email">Mail (will not be published) <?php if ($req) echo "(required)"; ?></label>'; br();
            echo '</div>'; br();
            
            echo '<div>'; br();
            echo '<input type="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3" />'; br();
            echo '<label for="url">Website</label>'; br();
            echo '</div>'; br();
        }
        
        echo '<!--<p>You can use these tags: <code>'.allowed_tags().'</code></p>-->'; br();
        
        echo '<div>'; br();
        echo '<textarea name="comment" id="comment" cols="58" rows="10" tabindex="4"></textarea>'; br();
        echo '</div>'; br();
        
        echo '<div>'; br();
        echo '<input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" />'; br();
        comment_id_fields();
        echo '</div>'; br();
        
        do_action('comment_form', $post->ID);
        
        echo '</form>'; br();
    
	} // If registration required and not logged in

    echo '</div>'; br();

}

c('End comments.php',3);

?>