<?php
/**
 * @package WordPress
 * @subpackage velocity_Theme
 */
?>

<?php
	$velocity_namelabel = __( 'Name *', 'velocity' );
	$velocity_emaillabel = __( 'Email *', 'velocity' );
	$velocity_websitelabel = __( 'Website', 'velocity' );
	$velocity_messagelabel = __( 'Message *', 'velocity' );
	$velocity_addreply = __( 'Submit Comment', 'velocity' );
	$velocity_loggedinas = __( 'You are logged in as', 'velocity' ); 
	$velocity_clickhereto = __( 'Click here to', 'velocity' );
	$velocity_logout = __( 'Log out', 'velocity' );
	$velocity_leavereply = __( 'Leave A Reply', 'velocity' );
	$velocity_leavereplyto = __( 'Leave A Reply to', 'velocity' );
	$velocity_cancelreply = __( 'Cancel Reply', 'velocity' );
?>


<?php if ( post_password_required() ) : ?>
	<p><?php _e( 'This post is password protected. Enter the password to view any comments.', 'velocity' ); ?></p>
<?php return; endif; ?>

<?php if ( have_comments() ) { ?>
	<?php $velocity_respondstyle = ''; ?>
	
	<div id="comments">
		<div class="wpb_separator wpb_content_element"></div>
		<div class="contenttitle"><div class="titletext"><h2><?php _e( 'Comments', 'velocity' ); ?></h2></div></div>
        <ul><?php wp_list_comments( array( 'callback' => 'velocity_comment' ) ); ?></ul>
	</div><div class="clear"></div>
<?php } ?>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :  ?>
	<div>
		<div class="left marginbottom10"><?php previous_comments_link( __( 'Older Comments ', 'velocity' ) ); ?></div>
		<div class="right marginbottom10"><?php next_comments_link( __( 'Newer Comments', 'velocity' ) ); ?> </div>
	</div>
<?php endif;  ?>

<?php if ( comments_open() ) : ?>
	<div class="clear"></div>
	<!-- Comment Form -->   
      <?php  
$velocity_comments_args = array(
		'fields' => apply_filters( 'comment_form_default_fields', array(
			'author' => '<input type="text" name="author" id="author" class="requiredfield" onFocus="if(this.value == \''.$velocity_namelabel.'\') { this.value = \'\'; }" onBlur="if(this.value == \'\') { this.value = \''.$velocity_namelabel.'\'; }" value=\''.$velocity_namelabel.'\'/>',
			'email'  => '<input type="text" name="email" id="email" class="requiredfield" onFocus="if(this.value == \''.$velocity_emaillabel.'\') { this.value = \'\'; }" onBlur="if(this.value == \'\') { this.value = \''.$velocity_emaillabel.'\'; }" value=\''.$velocity_emaillabel.'\'/>',
			'url'    => '<input type="text" name="url" id="url" class="last" onFocus="if(this.value == \''.$velocity_websitelabel.'\') { this.value = \'\'; }" onBlur="if(this.value == \'\') { this.value = \''.$velocity_websitelabel.'\'; }" value=\''.$velocity_websitelabel.'\'/>')),

        'title_reply'=>'<div class="wpb_separator wpb_content_element"></div><div class="contenttitle"><div class="titletext"><h2>'.$velocity_leavereply.'</h2></div></div>',
		'title_reply_to'=>'<div class="contenttitle"><div class="titletext"><h2>'.$velocity_leavereplyto.' %s</h2></div></div>',
        'comment_notes_after' => '',
        'comment_field' => '<textarea name="comment" id="comment" class="requiredfield" onFocus="if(this.value == \''.$velocity_messagelabel.'\') { this.value = \'\'; }" onBlur="if(this.value == \'\') { this.value = \''.$velocity_messagelabel.'\'; }">'.$velocity_messagelabel.'</textarea>',
		'label_submit' => __( 'Submit Comment','velocity' ),
		'cancel_reply_link' => __( '<div class="btn btn-danger btn-small">'.$velocity_cancelreply.'</div>' )
);
comment_form($velocity_comments_args); 
	?>
<?php endif; ?>