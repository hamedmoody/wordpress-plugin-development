<?php
 defined( 'ABSPATH' ) || exit;
 
function daneshjooyar_post_like_install(){

    global $wpdb;
    
    $table_post_likes    = $wpdb->prefix . 'dypl_post_likes';
    
    $sql = "
    CREATE TABLE `{$table_post_likes}` (
        `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `post_id` bigint(20) unsigned NOT NULL,
        `user_id` bigint(20) unsigned NOT NULL,
        `ip` varchar(15) NOT NULL,
        `liked` tinyint(1) NOT NULL DEFAULT 1,
        `created_at` datetime NOT NULL,
        PRIMARY KEY (`ID`),
        KEY `post_id` (`post_id`),
        KEY `user_id` (`user_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    
}

function daneshjooyar_post_like_script(){

    wp_enqueue_script(
        'dypl_script',
        DANESHJOOYAR_POST_LIKE_JS . 'post-like.js',
        ['jquery'],
        defined('WP_DEBUG') && WP_DEBUG ? time() : DANESHJOOYAR_POST_LIKE_VERSION,
        true
    );

    wp_localize_script( 'dypl_script', 'dypl', [
        'ajax_url'  => admin_url( 'admin-ajax.php' )
    ] );

}

function daneshjooyar_post_like_style(){
    ?>
    <style>
        button.like-post {
            background: #FFF;
            border-radius: 4px;
            padding: 4px 15px;
            border: 1px solid #e5e5e5;
            color: #3a3a3a;
            transition: 0.3s ease-in;
            display: inline-flex;
            gap: 5px;
            align-items: center;
            flex-direction: row-reverse;
        }

        button.like-post svg{
            display: none;
        }
        
        button.like-post.loading svg{
            display: inline;
        }

        button.like-post.post-liked {
            background: #4CAF50;
            color: #FFF;
        }

        span.like-message {
            color: green;
            visibility: hidden;
            opacity: 0;
            transition: 0.3s ease-in;
            display: inline;
        }

        span.like-message.error {
            color: red;
        }

        .like-message.show{
            visibility: visible;
            opacity: 1;
        }

    </style>
    <?php
}



function daneshjooyar_post_like_button( $content ){
    
    $like_text      = __( 'Like', 'daneshjooyar-post-like' );
    $post_id        = get_the_ID();

    $like_count     = daneshjooyar_post_like_get_post_like( $post_id );
    $liked_class    = daneshjooyar_post_like_is_like_post( $post_id, get_current_user_id() ) ? 'post-liked' : '';
    $nonce          = wp_create_nonce( 'post-like' . $post_id );

    $button = "
    <button class='like-post $liked_class' type='button' data-id='$post_id' data-nonce='$nonce'>
        <svg width='16' height='16' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><style>.spinner_ajPY{transform-origin:center;animation:spinner_AtaB .75s infinite linear}@keyframes spinner_AtaB{100%{transform:rotate(360deg)}}</style><path d='M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z' opacity='.25'/><path d='M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z' class='spinner_ajPY'/></svg>
        $like_text
        <span class='like-count'>($like_count)</span>
    </button>
    <span class='like-message'></span>
    ";
    return $content . $button;
}

function daneshjooyar_post_like_ajax_callback(){

    $result     = [];

    $post_id    = absint( $_POST['post_id'] );
    $like       = $_POST['like'] == 'true' ? true : false;

    if( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'post-like' . $post_id ) ){
        wp_send_json_error( [
            'message'   => 'Forbidden, nonce is invalid',
            'code'      => '403',
        ], 401 );
    }

    $liked      = daneshjooyar_post_like_do_like( $post_id, get_current_user_id(), $like );

    if( is_wp_error( $liked ) ){
        wp_send_json_error( [
            'message'   => $liked->get_error_message(),
            'code'      => $liked->get_error_code(),
        ], 401 );
    }else{
        wp_send_json_success( $liked );
    }

}