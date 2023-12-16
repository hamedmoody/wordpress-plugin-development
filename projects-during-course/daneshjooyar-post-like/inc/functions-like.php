<?php
 defined( 'ABSPATH' ) || exit;
 
function daneshjooyar_post_like_get_post_like( $post_id ){
    global $wpdb;
    $like_count = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->dypl_post_likes} WHERE post_id = %d"
            , $post_id
        )
    ); 
    return absint( $like_count );
}

function daneshjooyar_post_like_do_like( $post_id, $user_id, $like ){

    global $wpdb;
    
    if( ! get_post_type( $post_id ) ){
        return new WP_Error( 'invalid_post_id', __( 'Post is invalid', 'daneshjooyar-post-like' ) );
    }

    $exists_id     = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT ID FROM {$wpdb->dypl_post_likes} WHERE post_id = %d AND user_id = %d"
            , $post_id
            , get_current_user_id()
        )
    );

    if( $exists_id && $like ){
        //You like previously
        return new WP_Error( 'liked_prev', __( 'you liked previously', 'daneshjooyar-post-like' ) );
    }

    if( ! $exists_id && ! $like ){
        //You did not like this post
        return new WP_Error( 'did_not_liked', __( 'you did not like previously', 'daneshjooyar-post-like' ) );
    }

    if( $like ){

        $like_data = [
            'post_id'       => $post_id,
            'user_id'       => $user_id,
            'ip'            => $_SERVER['REMOTE_ADDR'],
            'liked'         => 1,
            'created_at'    => current_time('mysql')
        ];

        $liked = $wpdb->insert(
            $wpdb->dypl_post_likes,
            $like_data,
            ['%d', '%d', '%s', '%d', '%s']
        );

        if( $liked ){
            return [
                'message'   =>  __( 'liked successfuly', 'daneshjooyar-post-like' ),
                'liked'     => true,
                'count'     => daneshjooyar_post_like_get_post_like( $post_id ),
            ];
        }else{
            return new WP_Error( 'like_error', __( 'error in like', 'daneshjooyar-post-like' ) );
        }

    }else{

        $disliked = $wpdb->delete(
            $wpdb->dypl_post_likes,
            [
                'ID'    => $exists_id
            ]
        );

        if( $disliked ){
            return [
                'message'   =>  __( 'disliked successfuly', 'daneshjooyar-post-like' ),
                'liked'     => false,
                'count'     => daneshjooyar_post_like_get_post_like( $post_id ),
            ];
        }else{
            return new WP_Error( 'dislike_error', __( 'error in dislike', 'daneshjooyar-post-like' ) );
        }

    }

}

function daneshjooyar_post_like_is_like_post( $post_id, $user_id ){

    global $wpdb;

    if( $user_id ){
        $where = $wpdb->prepare( " AND user_id = %d ", $user_id );
    }else{
        $where = $wpdb->prepare( " AND ip = %s ", $_SERVER['REMOTE_ADDR'] );
    }

    $liked = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->dypl_post_likes} WHERE post_id = %d $where"
            , $post_id
        )
    );
    return $liked;
}