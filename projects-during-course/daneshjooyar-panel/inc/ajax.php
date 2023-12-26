<?php
defined('ABSPATH') || exit;

add_action( 'wp_ajax_nopriv_daneshjooyar_panel_login', 'daneshjooyar_panel_do_login' );
function daneshjooyar_panel_do_login(){
    
    $result = [
        'message'   => 'خطایی رخ داده'
    ];

    if(
        ! isset( $_POST['user_login'] )
        ||
        ! isset( $_POST['user_password'] )
        ||
        ! isset( $_POST['_wpnonce'] )
    ){
        wp_send_json_error( $result, 403 );
    }

    if( ! wp_verify_nonce( $_POST['_wpnonce'], 'daneshjooyar_panel_login' ) ){
        wp_send_json_error( $result, 403 );
    }

    $redirect = isset( $_POST['redirect'] ) && strpos( $_POST['redirect'], '/' ) === 0 ? home_url( sanitize_url( $_POST['redirect'] ) ) : false;

    $user = wp_signon( [
        'user_login'        => $_POST['user_login'],
        'user_password'     => $_POST['user_password'],
        'remember'        => isset( $_POST['rememeber'] ),
    ] );

    if( is_wp_error( $user ) ){

        $code               = $user->get_error_code();
        if(
            $code == 'empty_username'
            ||
             $code == 'incorrect_password'
             ||
             $code == 'invalid_username'
        ){
            $result['message']  = 'نام کاربری یا گذرواژه شما اشتباه است.';
            $result['code']     = 'invalid_login';
        }else{
            $result['message']  = $user->get_error_message();
            $result['code']     = $user->get_error_code();
        }

        wp_send_json_error( $result, 403 );

    }else{

        $result['message']  = $user->first_name . ' عزیز، با موفقیت وارد شدید';

        $result['redirect'] = $redirect && $redirect != '/' ? $redirect : daneshjooyar_panel_url('dashboard');

        wp_send_json_success( $result, 200 );

    }

}

add_action( 'wp_ajax_nopriv_daneshjooyar_panel_signup', 'daneshjooyar_panel_do_signup' );
function daneshjooyar_panel_do_signup(){

    $result = [
        'message'   => 'خطایی رخ داده'
    ];

    if(
        ! isset( $_POST['user_login'] )
        ||
        ! isset( $_POST['user_password'] )
        ||
        ! isset( $_POST['user_email'] )
        ||
        ! isset( $_POST['_wpnonce'] )
    ){
        wp_send_json_error( $result, 403 );
    }
    
    if( ! wp_verify_nonce( $_POST['_wpnonce'], 'daneshjooyar_panel_signup' ) ){
        wp_send_json_error( $result, 403 );
    }

    $password           = $_POST['user_password'];
    $uppercase_score    = preg_match( '@[A-Z]@', $password ) ? 1 : 0;
    $lowercase_score    = preg_match( '@[a-z]@', $password ) ? 1 : 0;
    $number_score       = preg_match( '@[0-9]@', $password ) ? 1 : 0;

    $score              = $uppercase_score + $lowercase_score + $number_score;

    if( strlen( $password ) < 8 ){
        $score = 0;
    }

    $result['fields'] = [];
    
    $redirect = isset( $_POST['redirect'] ) && strpos( $_POST['redirect'], '/' ) === 0 ? home_url( sanitize_url( $_POST['redirect'] ) ) : false;

    $user_id = wp_create_user( $_POST['user_login'],  $_POST['user_password'], $_POST['user_email'] );

    if( is_wp_error( $user_id ) ){

        $code               = $user_id->get_error_code();
        if(
            $code == 'empty_username'
            ||
             $code == 'incorrect_password'
             ||
             $code == 'invalid_username'
        ){
            $result['message']  = 'نام کاربری یا گذرواژه شما اشتباه است.';
            $result['code']     = 'invalid_login';
        }else{
            $result['message']  = $user_id->get_error_message();
            $result['code']     = $user_id->get_error_code();
        }

        wp_send_json_error( $result, 403 );

    }else{

        wp_clear_auth_cookie();
        wp_set_current_user( $user_id );
        wp_set_auth_cookie( $user_id );

        $result['message']  = $user_id->first_name . ' عزیز، ثبت نام و ورود شما با موفقیت انجام شد';

        $result['redirect'] = $redirect && $redirect != '/' ? $redirect : daneshjooyar_panel_url('dashboard');

        wp_send_json_success( $result, 200 );

    }

}

add_action( 'wp_ajax_daneshjooyar_panel_edit_profile', 'daneshjooyar_edit_profile' );
function daneshjooyar_edit_profile(){


    $result = [
        'message'   => 'خطایی رخ داده'
    ];

    if(
        ! isset( $_POST['email'] )
        ||
        ! isset( $_POST['first_name'] )
        ||
        ! isset( $_POST['last_name'] )
        ||
        ! isset( $_POST['_wpnonce'] )
    ){
        wp_send_json_error( $result, 403 );
    }
    
    if( ! wp_verify_nonce( $_POST['_wpnonce'], 'daneshjooyar_panel_edit_profile' ) ){
        wp_send_json_error( $result, 403 );
    }

    $email                  = sanitize_email( $_POST['email'] );
    $first_name             = sanitize_text_field( $_POST['first_name'] );
    $last_name              = sanitize_text_field( $_POST['last_name'] );
    $phone                  = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
    $notificator_token      = isset( $_POST['notificator_token'] ) ? sanitize_text_field( $_POST['notificator_token'] ) : '';

    $updated                = wp_update_user([
        'ID'            => get_current_user_id(  ),
        'user_email'    => $email,
        'first_name'    => $first_name,
        'last_name'     => $last_name,
    ]);

    update_user_meta( get_current_user_id(  ), 'phone', $phone );
    update_user_meta( get_current_user_id(  ), 'notificator_token', $notificator_token );

    $avatar     = isset( $_FILES['avatar'] ) ? $_FILES['avatar']['error'] == UPLOAD_ERR_OK &&  $_FILES['avatar'] : false;
    if( $avatar ){
        $upload         = wp_upload_bits( $avatar['name'], null, file_get_contents( $_FILES['avatar']['tmp_name'] ) );
        if( $upload['error'] ){
            $result['message'] = $upload['error'];
            wp_send_json_error( $result, 400 );
        }
        $avatar_url     = ! $upload['error'] ? $upload['url'] : false;
        if( $avatar_url ){
            update_user_meta( get_current_user_id(  ), 'daneshjooyar_avatar', $avatar_url );
        }
    }



    if( $updated ){

        $result['message']  = 'پروفایل شما با موفقیت بروز شد';
    
        wp_send_json_success( $result, 200 );

    }


}

add_action( 'wp_ajax_daneshjooyar_panel_change_password', 'daneshjooyar_panel_change_password' );
function daneshjooyar_panel_change_password(){

    $result = [
        'message'   => 'خطایی رخ داده'
    ];

    if(
        ! isset( $_POST['password'] )
        ||
        ! isset( $_POST['new_password'] )
        ||
        ! isset( $_POST['_wpnonce'] )
    ){
        wp_send_json_error( $result, 403 );
    }
    
    if( ! wp_verify_nonce( $_POST['_wpnonce'], 'daneshjooyar_panel_change_password' ) ){
        wp_send_json_error( $result, 403 );
    }

    $old_password      = $_POST['password'];
    $new_password      = $_POST['new_password'];
    
    $authenticated      = wp_authenticate( wp_get_current_user(  )->user_login, $old_password );
    
    if( is_wp_error( $authenticated ) ){
        $result['message'] = 'گذزواژه قبلی اشتباه است';
        wp_send_json_error( $result, 403 );
    }

    wp_set_password( $new_password, get_current_user_id(  ) );

    $result['message']  = 'پروفایل شما با موفقیت بروز شد';

    wp_send_json_success( $result, 200 );

    

}

add_action( 'wp_ajax_daneshjooyar_panel_new_ticket', 'daneshjooyar_panel_new_ticket' );
function daneshjooyar_panel_new_ticket(){

    $result = [
        'message'   => 'خطایی رخ داده'
    ];

    if(
        ! isset( $_POST['content'] )
        ||
        ! isset( $_POST['_wpnonce'] )
    ){
        wp_send_json_error( $result, 403 );
    }

    $ticket_parent_id  = isset( $_POST['ID'] ) ? absint( $_POST['ID'] ) : 0;
    
    if( ! wp_verify_nonce( $_POST['_wpnonce'], 'daneshjooyar_panel_new_ticket' . $ticket_parent_id ) ){
        wp_send_json_error( $result, 403 );
    }

    if( ! $ticket_parent_id && ! isset( $_POST['title'] ) ){
        $result['message'] = 'عنوان تیکت الزامی است';
        wp_send_json_error( $result, 400 );
    }

    $content        = wp_kses_post( $_POST['content'] );
    $title          = isset( $_POST['title'] )      ? sanitize_text_field( $_POST['title'] ) : '';
    $priority       = isset( $_POST['priority'] )   ? sanitize_text_field( $_POST['priority'] ) : 'low';
    $dep            = isset( $_POST['department'] ) ? sanitize_text_field( $_POST['department'] ) : 'support';
    $files          = isset( $_POST['file'] ) && is_array( $_POST['file'] ) ? array_map( 'sanitize_url', $_POST['file'] ) : [];
    
    $is_reply       = $ticket_parent_id;

    if( strlen( $content ) < 5 ){
        $result['message'] = 'محتوای تیکت نمی تواند کمتر از 5 کاراکتر باشد';
        wp_send_json_error( $result, 400 );
    }

    $meta_input     = [
        '_department'   => $dep,
        '_priority'     => $priority,
    ];

    $ticket_attr = [
        'post_title'    => $title,
        'post_content'  => $content,
        'post_type'     => 'ticket',
        'post_date_gmt' => get_gmt_from_date( current_time('mysql') ),
        'post_parent'   => $ticket_parent_id,
        'post_status'   => 'pending',
        'meta_input'    => $is_reply ? [] : $meta_input,
    ];

    $insreted_ticket    = wp_insert_post( $ticket_attr, true );

    if( is_wp_error( $insreted_ticket ) ){
        $result['message'] = $insreted_ticket->get_error_message();
        wp_send_json_error( $result, 400 );
    }

    $new_ticket_id  = $insreted_ticket;
    
    foreach( $files as $file_url ){
        add_post_meta( $new_ticket_id, '_file', $file_url );
    }

    if( $is_reply ){

        $ticket_parent  = get_post( $ticket_parent_id );
        $update_data    = [
            'ID'            => $ticket_parent_id,
        ];
        if( $ticket_parent->post_author != get_current_user_id() ){
            $update_data['post_status'] = 'answer';
        }else{
            $update_data['post_status'] = 'pending';
        }
        wp_update_post( $update_data );

        $result['reload'] = true;

    }else{
        $result['redirect'] = daneshjooyar_panel_url( 
            sprintf( 'ticket/view?ticket_id=%d', $new_ticket_id )
         );
    }

    $message = $is_reply ? 'پاسخ' : 'تیکت';

    $result['message']  = $message . ' شما با موفقیت ارسال شد';

    if( $is_reply ){
        do_action( 'daneshjooyar_panel_ticket_reply', $ticket_parent_id, $new_ticket_id );
    }else{
        do_action( 'daneshjooyar_panel_ticket_sent', $new_ticket_id );
    }

    wp_send_json_success( $result, 200 );

    

}

add_action( 'wp_ajax_daneshjooyar_panel_change_ticket_status', 'daneshjooyar_panel_change_ticket_status' );
function daneshjooyar_panel_change_ticket_status(){

    $result = [
        'message'   => 'خطایی رخ داده'
    ];

    if(
        ! isset( $_POST['ID'] )
        ||
        ! isset( $_POST['status'] )
        ||
        ! isset( $_POST['_wpnonce'] )
    ){
        wp_send_json_error( $result, 403 );
    }
    
    if( ! wp_verify_nonce( $_POST['_wpnonce'], 'ticket-status' . $_POST['ID'] ) ){
        wp_send_json_error( $result, 403 );
    }

    $status     = sanitize_text_field( $_POST['status'] );
    $ticket     = get_post( absint( $_POST['ID'] ) );
    
    if( $ticket->post_author != get_current_user_id() && ! daneshjooyar_user_can_manage_tickets() ){
        $result['message'] = 'شما درسترسی برای تغییر وضعیت تیکت را ندارید';
        wp_send_json_error( $result, 403 );
    }

    if( ! daneshjooyar_user_can_manage_tickets() && $status != 'close' ){
        $result['message'] = 'شما درسترسی برای تغییر وضعیت تیکت را ندارید';
        wp_send_json_error( $result, 403 );
    }

    if( ! in_array( $status, ['close', 'answer', 'process'] ) ){
        $result['message'] = 'وضعیت جدید مجاز نیست';
        wp_send_json_error( $result, 403 );
    }

    wp_update_post([
        'ID'            => $ticket->ID,
        'post_status'   => $status,
    ]);

    $result['message']  = 'وضعیت تیکت شما بروز شد';

    wp_send_json_success( $result, 200 );

}

add_action( 'wp_ajax_daneshjooyar_panel_upload_file', 'daneshjooyar_panel_upload' );
function daneshjooyar_panel_upload(){

    $result = [
        'message'   => 'خطایی رخ داده'
    ];

    if(
        ! isset( $_FILES['attachemnt'] )
        ||
        ! isset( $_POST['_wpnonce'] )
    ){
        wp_send_json_error( $result, 403 );
    }
    
    if( ! wp_verify_nonce( $_POST['_wpnonce'], 'upload-file' ) ){
        wp_send_json_error( $result, 403 );
    }

    
    $attachment     = isset( $_FILES['attachemnt'] ) ? $_FILES['attachemnt'] : false;

    if( ! $attachment || $attachment['error'] !== UPLOAD_ERR_OK ){
        $result['message'] = 'آپلود ناموفقی بود';
        wp_send_json_error( $result, 403 );
    }

    $upload         = wp_upload_bits( $attachment['name'], null, file_get_contents( $attachment['tmp_name'] ) );
    if( $upload['error'] ){
        $result['message'] = $upload['error'];
        wp_send_json_error( $result, 400 );
    }

    $attachment_url     = ! $upload['error'] ? $upload['url'] : false;
    if( $attachment_url ){
        $result['url'] = $attachment_url;
        $result['message'] = 'آپلود با موفقیت انجام شد';
        wp_send_json_success( $result, 200 );
    }
    

    wp_send_json_error( $result, 400 );


}