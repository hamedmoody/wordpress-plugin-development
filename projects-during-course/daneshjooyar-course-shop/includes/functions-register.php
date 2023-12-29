<?php
defined('ABSPATH') || exit;

function dcs_is_student( $course_id, $user_id ){
    $reigster_query = new WP_Query([
        'author'        => $user_id,
        'post_parent'   => $course_id,
        'post_status'   => 'publish',
        'post_type'     => 'course_register',
    ]);
    return $reigster_query->have_posts();
}

function dcs_get_student_count( $course_id ){
    $reigster_query = new WP_Query([
        'post_parent'   => $course_id,
        'post_status'   => 'publish',
        'post_type'     => 'course_register'
    ]);
    return $reigster_query->found_posts;
}

function dcs_get_students(){
    global $wpdb;
    $student_ids = $wpdb->get_col(
        "SELECT DISTINCT post_author FROM {$wpdb->posts} WHERE post_type = 'course_register'"
    );
    if( ! $student_ids ){
        $student_ids = [];
    }
    return get_users([
        'include'   => $student_ids,
    ]);
}

function dcs_get_course_sales( $course_id ){
    global $wpdb;
    $sql = $wpdb->prepare(
        "SELECT SUM(menu_order) FROM {$wpdb->posts} WHERE post_type = 'course_register' AND post_status = 'publish' AND post_parent = %d"
        , $course_id
    );
    return intval(
        $wpdb->get_var( $sql )
    );
}

function dcs_create_course_register( $user_id, $course_id, $gateway ){

    if( get_post_type( $course_id ) !== 'course' ){
        return new WP_Error('invalid_course', __( 'Course is invalid', 'daneshjooyar-course-shop' ));
    }

    if( ! get_user_by( 'ID',  $user_id ) ){
        return new WP_Error('invalid_user', __( 'User is invalid', 'daneshjooyar-course-shop' ));
    }

    if( get_post_status($course_id) !== 'publish' ){
        return new WP_Error('not_published', __( 'Course is not publish', 'daneshjooyar-course-shop' ));
    }

    $price          = dcs_get_course_price( $course_id );
    $final_price    = dcs_get_course_final_price( $course_id );
    $percent        = dcs_get_final_discount_percent( $course_id );

    $title          = '#' . $course_id;
    


    $register_data  = [
        'post_title'    => $title,
        'post_content'  => $title,
        'post_author'   => $user_id,
        'post_parent'   => $course_id,
        'post_type'     => 'course_register',
        'menu_order'    => $final_price,
        'post_excerpt'  => dcs_make_register_hash(),
        'post_status'   => 'pending',
        'meta_input'    => [
            '_price'            => $price,
            '_discount_percent' => $percent,
            '_gateway'          => $gateway,
        ]
    ];

    $course_register_id = wp_insert_post( $register_data, true );

    if( is_wp_error( $course_register_id ) ){
        return false;
    }else{
        wp_update_post([
            'ID'            => $course_register_id,
            'post_title'    => '#' . $course_register_id,
        ]);
        return $course_register_id;
    }

}

function dcs_make_hash( $count = 40 ){
    $alphabet   = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $str        = '';
    for( $i = 0; $i < $count; $i++ ){
        $str.= str_shuffle( $alphabet )[0];
    }
    return $str;
}

function dcs_make_register_hash(){
    global $wpdb;
    while( true ){
        $hash   = dcs_make_hash();
        $exists = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT ID FROM {$wpdb->posts} WHERE post_excerpt = %s", $hash
            )
        );
        if( ! $exists ){
            return $hash;
        } 
    }
}

function dcs_compelete_register( $register_id ){
    return wp_update_post( [
        'ID'            => $register_id,
        'post_status'   => 'publish',
    ], true );
}

function dcs_fail_register( $course_id, $error_message ){
    return wp_update_post( [
        'ID'            => $course_id,
        'post_status'   => 'failed',
        'meta_input'    => [
            '_error_message'    => $error_message,
        ]
    ] );
}

function dcs_get_bitpay_error_message( $error_code ){
    $messages = [
        -1  => 'API ارسالی با نوع API تعریف شده در
        bitpay سازگار نیست',
        -2  => 'd_trans ارسال شده، داده عددي
        نمیباشد',
        -3  => 'get_id ارسال شده، داده عددي
        نمیباشد',
        -4  => 'چنین تراکنشی در پایگاه وجود ندارد و یا
        موفقیت آمیز نبوده است',
        1   => 'تراکنش موفقیت آمیز بوده است',
        11  => 'تراکنش از قبل وریفاي شده است',
    ];
    return isset( $messages[$error_code] ) ? $messages[$error_code] : 'خطای ناشناخته';
}