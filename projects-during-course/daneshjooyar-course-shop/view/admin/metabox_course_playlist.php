<?php
defined('ABSPATH') || exit;
$counter = 1;
?>
<table class="dcs_playlist_table">
    <thead>
        <tr>
            <th></th>
            <th>#</th>
            <th><?php esc_html_e( 'Title', 'daneshjooyar-course-shop' );?></th>
            <th><?php esc_html_e( 'Url', 'daneshjooyar-course-shop' );?></th>
            <th><?php esc_html_e( 'File access', 'daneshjooyar-course-shop' );?></th>
            <th><?php esc_html_e( 'Duration', 'daneshjooyar-course-shop' );?></th>
            <th><?php esc_html_e( 'Action', 'daneshjooyar-course-shop' );?></th>
        </tr>
    </thead>
    <tbody>
        <?php if( $playlist_items_query->have_posts() ):?>
            <?php while( $playlist_items_query->have_posts() ):$playlist_items_query->the_post();global $post;?>
            <?php
            $width      = get_post_meta( get_the_ID(), '_dcs_width', true );
            $height     = get_post_meta( get_the_ID(), '_dcs_height', true );
            $duration   = get_post_meta( get_the_ID(), '_dcs_duration', true );
            ?>
            <tr>
                <td>
                    <span class="dashicons dashicons-move"></span>
                </td>
                <td><?php echo $counter++;?></td>
                <td class="dcs_playlist_input">
                    <input type="hidden" name="dcs_playlist[ids][]" value="<?php the_ID();?>">
                    <input type="hidden" class="dcs_item_width"  name="dcs_playlist[widths][]" value="<?php echo esc_attr( $width );?>">
                    <input type="hidden" class="dcs_item_height"  name="dcs_playlist[heights][]" value="<?php echo esc_attr( $height );?>">
                    <input type="hidden" class="dcs_item_duration"  name="dcs_playlist[durations][]" value="<?php echo esc_attr( $duration );?>">
                    <input type="text" name="dcs_playlist[titles][]" value="<?php echo esc_attr( $post->post_title );?>">
                </td>
                <td class="dcs_playlist_input">
                    <input type="url" name="dcs_playlist[urls][]" value="<?php echo esc_attr( $post->guid );?>">
                </td>
                <td>
                    <select name="dcs_playlist[statuses][]">
                        <option value="free" <?php selected( $post->post_status, 'free' );?>><?php esc_html_e( 'Free', 'daneshjooyar-course-shop' );?></option>
                        <option value="premium" <?php selected( $post->post_status, 'premium' );?>><?php esc_html_e( 'Premium', 'daneshjooyar-course-shop' );?></option>
                    </select>
                </td>
                <td>
                    <p class="dcs_duration">
                        <?php echo $duration ? dcs_second_to_time( $duration ) : '--:--';?>
                    </p>
                    <img src="<?php echo admin_url( 'images/spinner.gif' );?>" alt="spinner" width="24" height="24" class="dcs-spinner"/>
                </td>
                <td>
                    <span class="dashicons dashicons-trash"></span>
                </td>
            </tr>
            <?php endwhile;?>
        <?php else:?>
            <tr class="dcs_no_item">
                <td colspan="7">
                    <?php esc_html_e( 'No playlist item found', 'daneshjooyar-course-shop' );?>
                </td>
            </tr>
        <?php endif;?>
    </tbody>
</table>
<br>
<button type="button" class="button button-primary dcs_add_playlist_item">
    <?php esc_html_e( 'Add playlist item', 'daneshjooyar-course-shop' );?>
</button>

<script type="text/template" id="tp_no_item">
    <tr class="dcs_no_item">
        <td colspan="7">
            <?php esc_html_e( 'No playlist item found', 'daneshjooyar-course-shop' );?>
        </td>
    </tr>
</script>

<script type="text/template" id="tp_item">
    <tr>
        <td>
            <span class="dashicons dashicons-move"></span>
        </td>
        <td></td>
        <td class="dcs_playlist_input">
        <input type="hidden" name="dcs_playlist[ids][]" value="0">
            <input type="hidden" class="dcs_item_width" name="dcs_playlist[widths][]" value="0"/>
            <input type="hidden" class="dcs_item_height" name="dcs_playlist[heights][]" value="0"/>
            <input type="hidden" class="dcs_item_duration" name="dcs_playlist[durations][]" value="0"/>
            <input type="text" name="dcs_playlist[titles][]" value="">
        </td>
        <td class="dcs_playlist_input">
            <input type="url" name="dcs_playlist[urls][]" value="">
        </td>
        <td>
            <select name="dcs_playlist[statuses][]">
                <option value="free"><?php esc_html_e( 'Free', 'daneshjooyar-course-shop' );?></option>
                <option value="premium"><?php esc_html_e( 'Premium', 'daneshjooyar-course-shop' );?></option>
            </select>
        </td>
        <td>
            <p class="dcs_duration">--:--</p>
<img src="<?php echo admin_url( 'images/spinner.gif' );?>" alt="spinner" width="24" height="24" class="dcs-spinner"/>
        </td>
        <td>
            <span class="dashicons dashicons-trash"></span>
        </td>
    </tr>
</script>