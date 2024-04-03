<?php
defined( 'ABSPATH' ) || exit;
add_action( 'wp_footer', 'daneshjooyar_sms_login_modal' );
function daneshjooyar_sms_login_modal(){
    if( ! is_user_logged_in(  ) ){
        include( DANESHJOOYAR_SMS_LOGIN_VIEW . 'sms_login_modal.php' );
    }
}