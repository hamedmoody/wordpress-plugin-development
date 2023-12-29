<?php
defined('ABSPATH') || exit;
global $post;
$label      = __( 'Failed', 'daneshjooyar-course-shop' );
$selected   = $post->post_status == 'failed' ? 'selected' : '';

?>
<script>
    jQuery(document).ready(function($){
        $('select#post_status').append(
            `<option value="failed" <?php echo $selected;?>><?php echo $label;?></option>`
        );
        <?php if( $post->post_status == 'failed' ):?>
        $('#post-status-display').text( '<?php echo $label;?>' );
        <?php endif;?>
        $('a.save-post-status').click( function(e){
            if( $('select#post_status').val() == 'failed' ){
                console.log('okokoko');
                setTimeout(() => {
                    $('input#save-post').val( '<?php _e( 'Save as failed', 'daneshjooyar-course-shop' );?>' );
                }, 5);
            }
        } );
    });
</script>