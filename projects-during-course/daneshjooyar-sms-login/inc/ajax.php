<?php
defined('ABSPATH') || exit;

add_action( 'wp_ajax_nopriv_dsl_login', 'daneshjooyar_sms_login' );

function daneshjooyar_sms_login(){
    global $wpdb;
    $result = [
        'message'   => 'خطایی رخ داده است'
    ];

    if(
        ! isset( $_REQUEST['phone'] )
        ||
        ! isset( $_REQUEST['_wpnonce'] )
        ||
        ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'dsl-login' )
        ){

            wp_send_json_error( $result, 401 );

    }

    $phone = sanitize_phone( $_REQUEST['phone'] );

    if( ! $phone ){
        $result['message']  = 'تلفن صحیح نیست';
        wp_send_json_error( $result, 401 );
    }

    $digit          = 5;
    $expire         = 200;
    $code           = dsl_generate_code( $digit );

    $expired_at     = date( 'Y-m-d H:i:s', current_time('timestamp') + $expire );

    $inserted       = dsl_register_code( $phone, $code, $expired_at );
    if( is_wp_error( $inserted ) ){
        $result['message'] = $inserted->get_error_message();
        wp_send_json_error( $result, 503 );
    }

    $message = 'code: ' . $code;

    //notificator_send_message( $message );
    $sent_sms = dsl_send_login_sms( $phone, $code );
    
    if( is_wp_error( $sent_sms ) ){
        $result['message'] = $sent_sms->get_error_message();
        wp_send_json_error( $result, 400 );
    }

    $wpdb->update(
        $wpdb->dsl_sms_login,
        [
            'price'         => $sent_sms->cost,
            'message_id'    => $sent_sms->messageId,
        ],
        [
            'ID'            => $inserted,
        ]
    );

    $result['message']  = 'کد ' . $digit . ' رقمی ارسال شده به شماره ' . $phone . ' را وارد کنید';
    $result['code']     = $code;
    $result['duration'] = $expire;
    $result['phone']    = $phone;
    $result['_wpnonce'] = wp_create_nonce( 'verify' . $phone );


    wp_send_json_success( $result, 200 );

}

add_action( 'wp_ajax_nopriv_dsl_verify', 'daneshjooyar_sms_verify' );
function daneshjooyar_sms_verify(){
    
    $result = [
        'message'   => 'خطایی رخ داده است'
    ];

    if(
        ! isset( $_REQUEST['phone'] )
        ||
        ! isset( $_REQUEST['code'] )
        ||
        ! isset( $_REQUEST['_wpnonce'] )
        ||
        ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'verify'.  $_REQUEST['phone'] )
        ){

            wp_send_json_error( $result, 401 );

    }

    $phone  = sanitize_phone( $_REQUEST['phone'] );

    if( ! $phone ){
        $result['message']  = 'تلفن صحیح نیست';
        wp_send_json_error( $result, 401 );
    }

    $code   = sanitize_text_field( $_REQUEST['code'] );

    global $wpdb;
    $verify = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->dsl_sms_login} WHERE phone = %s ORDER BY created_at DESC",
            $phone
        )
    );

    if( ! $verify ){
        $result['message']  = 'درخواست احراز شما یافت نشد';
        wp_send_json_error( $result, 401 );
    }

    if( $verify->code != $code ){
        $result['message']  = 'کد ارسال اشتباه است، مجدد تلاش کنید';
        wp_send_json_error( $result, 401 );
    }

    if( current_time('timestamp') >= strtotime( $verify->expired_at ) ){
        $result['message']  = 'کد منقضی شده است، مجدد تلاش کنید';
        wp_send_json_error( $result, 401 );
    }
    

    $exists = dsl_get_user_by_phone( $phone );

    $user   = dsl_get_or_make_user( $phone );

    if( is_wp_error( $user ) ){
        $result['message']  = $user->get_error_message();
        wp_send_json_error( $result, 401 );
    }

    wp_clear_auth_cookie();
    wp_set_current_user( $user->ID );
    wp_set_auth_cookie( $user->ID );
    //Login

    $data = [
        'user_id'   => $user->ID,
        'status'    => $exists ? 'login' : 'register',
        'updated_at'    => current_time('mysql'),
    ];

    $wpdb->update(
        $wpdb->dsl_sms_login,
        $data,[
            'ID'    => $verify->ID
        ]
    );

    $result['message'] = 'ورود با موفقیت انجام شد';
    wp_send_json_success( $result, 200 );
    
}