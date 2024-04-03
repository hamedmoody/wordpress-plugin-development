<?php

defined('ABSPATH') || exit;

/**
 * Sanitize Phone Numbers
 * @param $phone
 * @return bool|string
 */
function sanitize_phone( $phone ){

    /**
     * Convert all chars to en digits
     */
    $western    = array('0','1','2','3','4','5','6','7','8','9');
    $persian    = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $arabic     = ['٠',  '١',  '٢', '٣','٤', '٥', '٦','٧','٨','٩' ];
    $phone      = str_replace( $persian, $western, $phone );
    $phone      = str_replace( $arabic, $western, $phone );

    //.9158636712   => 09158636712
    if( strpos( $phone, '.' ) === 0 ){
        $phone = '0' . substr( $phone, 1 );
    }

    //00989185223232 => 9185223232
    if( strpos( $phone, '0098' ) === 0 ){
        $phone = substr( $phone, 4 );
    }
    //0989108210911 => 9108210911
    if( strlen( $phone ) == 13 && strpos( $phone, '098' ) === 0 ){
        $phone = substr( $phone, 3 );
    }
    //+989156040160 => 9156040160
    if( strlen( $phone ) == 13 && strpos( $phone, '+98' ) === 0 ){
        $phone = substr( $phone, 3 );
    }
    //+98 9156040160 => 9156040160
    if( strlen( $phone ) == 14 && strpos( $phone, '+98 ' ) === 0 ){
        $phone = substr( $phone, 4 );
    }
    //989152532120 => 9152532120
    if( strlen( $phone ) == 12 && strpos( $phone, '98' ) === 0 ){
        $phone = substr( $phone, 2 );
    }
    //Prepend 0
    if( strpos( $phone, '0' ) !== 0 ){
        $phone = '0' . $phone;
    }
    /**
     * check for all character was digit
     */
    if( ! ctype_digit( $phone ) ){
        return '';
    }

    if( strlen( $phone ) != 11 ){
        return '';
    }

    return $phone;

}

function dsl_generate_code( $digits = 5 ){
    $code = '';
    for( $i = 0; $i < $digits; $i++ ){
        $code.= rand( ! $i ? 1 : 0, 9 );
    }
    return $code;
}

function dsl_register_code( $phone, $code, $expired_at ){

    global $wpdb;

    $data = [
        'phone'         => $phone,
        'code'          => $code,
        'expired_at'    => $expired_at,
        'created_at'    => current_time('mysql'),
        'updated_at'    => current_time('mysql'),
    ];

    $inserted = $wpdb->insert(
        $wpdb->dsl_sms_login,
        $data,
        '%s'
    );

    if( ! $inserted ){
        notificator_send_message( 'insert error for ' . $wpdb->dsl_sms_login . PHP_EOL . print_r( $data, true ) );
        new WP_Error( 'error_insertion', 'خطا در ثبت داده' );
    }

    return $wpdb->insert_id;

}

function dsl_get_user_by_phone( $phone ){

    $users = get_users([
        'meta_key'      => 'phone',
        'meta_value'    => $phone
    ]);

    return empty( $users ) ? false : $users[0];

}

function dsl_get_or_make_user( $phone ){

    $user = dsl_get_user_by_phone( $phone );

    if( ! $user ){

        $password   = wp_generate_password( 15 );
        $user_id    = wp_create_user( $phone, $password );

        if( ! is_wp_error( $user_id ) ){

            $user = new WP_User( $user_id );

            global $wpdb;
            $wpdb->update($wpdb->users, [
                'user_login'    => 'u' . $user_id,
            ],[
                'ID'            => $user_id
            ]);

            wp_cache_flush();

            update_user_meta( $user_id, 'phone', $phone );

        }else{
            $user = $user_id;
        }

    }

    return $user;

}

function dsl_send_login_sms( $phone, $code ){

    $template_id    = 0;//Your login template id here
    $params         = [
        'Mobile'        => $phone,
        'TemplateId'    => $template_id,
        'Parameters'    => [
            [
                'Name'  => 'CODE',
                'Value' => $code,
            ],
            [
                'Name'  => 'DOMAIN',
                'Value' => '@' . $_SERVER['HTTP_HOST'],
            ],
            [
                'Name'  => 'OTP_CODE',
                'Value' => '#' . $code,
            ]
        ]
    ];

    $results = wp_remote_post( 'https://api.sms.ir/v1/send/verify', [
        'headers'   => [
            'Content-Type'  => 'application/json',
            'ACCEPT'        => 'application/json',
            'X-API-KEY'     => 'Your_Api_key_here'
        ],
        'body'  => json_encode( $params )
    ] );
    
    if( is_wp_error( $results ) ){
        return $results;
    }

    if( wp_remote_retrieve_response_code( $results ) != 200 ){

        //Log
        $data       = json_decode( wp_remote_retrieve_body( $results ) );
        $meesage    = $data->message;
        $status     = $data->status;

        return new WP_Error( 'sms_send_error', 'در ارسال پیامک مشکلی بوجود آمده' );

    }

    $data = json_decode( wp_remote_retrieve_body( $results ) );

    return $data->data;

}