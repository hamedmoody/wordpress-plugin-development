<?php
defined('ABSPATH') || exit;

add_action( 'add_meta_boxes', 'dcs_add_metaboxes' );
function dcs_add_metaboxes(){

    add_meta_box(
        'dcs_course_data',
        __( 'Course Data', 'daneshjooyar-course-shop' ),
        'dcs_metabox_course_data',
        'course'
    );

    add_meta_box(
        'dcs_course_playlist',
        __( 'Course Playlist', 'daneshjooyar-course-shop' ),
        'dcs_metabox_course_playlist',
        'course'
    );

    add_meta_box(
        'dcs_course_reigster',
        __( 'Course Reigster Data', 'daneshjooyar-course-shop' ),
        'dcs_metabox_course_register',
        'course_register',
        'normal',
        'high'
    );

}

function dcs_metabox_course_register( $post ){
    include DANESHJOOYAR_COURSE_SHOP_VIEW_ADMIN . 'metabox_course_register.php';
}

function dcs_metabox_course_playlist( $post ){

    $playlist_items_query = new WP_Query( [
        'post_type'         => 'playlist_item',
        'posts_per_page'    => -1,
        'post_status'       => ['free', 'premium'],
        'orderby'           => 'menu_order',
        'order'             => 'asc',
        'post_parent'       => $post->ID,
    ] );

    include DANESHJOOYAR_COURSE_SHOP_VIEW_ADMIN . 'metabox_course_playlist.php';

    wp_reset_postdata();

}

function dcs_metabox_course_data( $post ){

    $price          = get_post_meta( $post->ID, '_dcs_price', true );
    $sale_price     = get_post_meta( $post->ID, '_dcs_sale_price', true );
    $teacher_id     = get_post_meta( $post->ID, '_dcs_teacher', true );
    $demo           = get_post_meta( $post->ID, '_dcs_demo', true );
    $expire         = get_post_meta( $post->ID, '_dcs_expire', true );
    $has_discount   = get_post_meta( $post->ID, '_dcs_has_discount', true );

    include DANESHJOOYAR_COURSE_SHOP_VIEW_ADMIN . 'metabox_course_data.php';
    
}

add_action( 'save_post', 'dcs_save_course_data' );
function dcs_save_course_data( $post_id ){

    global $wpdb;

    if( get_post_type( $post_id ) != 'course' ){
        return;
    }

    if( isset( $_POST['dcs_course'] )  ){

        $data           = $_POST['dcs_course'];

        $demo           = sanitize_url( $data['demo'] );
        $teacher_id     = absint( $data['teacher'] );

        $teacher        = get_user_by( 'ID', $teacher_id );
        if( ! $teacher ){
            wp_die( __( 'Teacher is invalid', 'daneshjooyar-course-shop' ) );
        }

        $price          = absint( $data['price'] );
        $sale_price     = absint( $data['sale_price'] );
        $has_discount   = isset( $data['has_discount'] );
        $expire         = sanitize_text_field( $data['discount_expire'] );
        
        update_post_meta( $post_id, '_dcs_price', $price );
        update_post_meta( $post_id, '_dcs_sale_price', $sale_price );
        update_post_meta( $post_id, '_dcs_demo', $demo );
        update_post_meta( $post_id, '_dcs_expire', $expire );
        update_post_meta( $post_id, '_dcs_has_discount', $has_discount );
        update_post_meta( $post_id, '_dcs_teacher', $teacher_id );

        $playlist_items     = [];
        $playlist_item_ids  = [];
        $size               = 0;
        $duration           = 0;
        if( isset( $_POST['dcs_playlist'] ) ){

            foreach( $_POST['dcs_playlist']['ids'] as $item_index => $item_id ){

                $url            = sanitize_url( $_POST['dcs_playlist']['urls'][$item_index] );
                $file_size      = dcs_retrieve_remote_file_size( $url );
                $video_duration = absint( $_POST['dcs_playlist']['durations'][$item_index] );

                $mime_type      = '';
                $file_info      = pathinfo( $url );
                if( isset( $file_info['extension'] ) && $file_info['extension'] == 'mp4' ){
                    $mime_type = 'video/mp4';
                }

                $playlist_items[] = [
                    'ID'            => absint( $item_id ),
                    'post_type'     => 'playlist_item',
                    'post_title'    => sanitize_text_field( $_POST['dcs_playlist']['titles'][$item_index] ),
                    'guid'          => $url,
                    'menu_order'    => $item_index,
                    'post_parent'   => $post_id,
                    'post_mime_type'    => $mime_type,
                    'post_status'   => in_array( $_POST['dcs_playlist']['statuses'][$item_index], ['free', 'premium'] ) ? $_POST['dcs_playlist']['statuses'][$item_index] : 'premium',
                    'meta_input'    => [
                        '_dcs_width'        => absint( $_POST['dcs_playlist']['widths'][$item_index] ),
                        '_dcs_height'       => absint( $_POST['dcs_playlist']['heights'][$item_index] ),
                        '_dcs_duration'     => $video_duration,
                        '_dcs_size'         => $file_size,
                    ]
                ];

                $size+= $file_size;
                $duration+= $video_duration;

                if( $item_id ){
                    $playlist_item_ids[] = $item_id;
                }
            }

            update_post_meta( $post_id, '_dcs_duration', $duration );
            update_post_meta( $post_id, '_dsc_size', $size );

            if( ! empty( $playlist_item_ids ) ){
                $wpdb->query(
                    $wpdb->prepare(
                        "DELETE FROM $wpdb->posts WHERE post_type = 'playlist_item' AND post_parent = %d AND ID NOT IN ( " . implode(',', $playlist_item_ids) . " )"
                        , $post_id
                    )
                );
            }
            
            foreach( $playlist_items as $playlist_item ){
                if( $playlist_item['ID'] ){
                    wp_update_post( $playlist_item );

                    $wpdb->update(
                        $wpdb->posts,[
                            'guid'  => $playlist_item['guid'],
                        ],[
                            'ID'    => $playlist_item['ID']
                        ]
                    );
                    
                }else{
                    wp_insert_post( $playlist_item );
                }
            }

        }

    }

}