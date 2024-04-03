<?php
defined('ABSPATH') || exit;

add_action( 'wp_enqueue_scripts', 'daneshjooyar_sms_login_public_scripts' );
function daneshjooyar_sms_login_public_scripts(){

    if( is_user_logged_in(  ) ){
        return;
    }

    wp_enqueue_style(
        'dsl-public-style',
        DANESHJOOYAR_SMS_LOGIN_CSS . 'style.css',
        [],
        defined('WP_DEBUG') && WP_DEBUG ? time() : DANESHJOOYAR_SMS_LOGIN_VERSION
    );

    wp_enqueue_script(
        'dsl-public-script',
        DANESHJOOYAR_SMS_LOGIN_JS . 'script.js',
        ['jquery'],
        defined('WP_DEBUG') && WP_DEBUG ? time() : DANESHJOOYAR_SMS_LOGIN_VERSION,
        true
    );

    wp_localize_script( 'dsl-public-script', 'dsl', [
        'ajax_url'  => admin_url('admin-ajax.php'),
    ] );

}

add_action( 'admin_enqueue_scripts', 'daneshjooyar_sms_login_admin_scripts' );
function daneshjooyar_sms_login_admin_scripts(){

    wp_enqueue_script(
        'dsl-admin-script',
        DANESHJOOYAR_SMS_LOGIN_JS . 'admin.js',
        ['jquery'],
        defined('WP_DEBUG') && WP_DEBUG ? time() : DANESHJOOYAR_SMS_LOGIN_VERSION,
        true
    );


}