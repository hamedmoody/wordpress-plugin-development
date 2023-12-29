<?php
defined('ABSPATH')  || exit;

function dcs_get_api_key(){
    return get_option( 'dcs_option_gateway_api' );
    //return 'adxcv-zzadq-polkjsad-opp13opoz-1sdf455aadzmck1244567';
}

function dcs_bitpay_url( $path ){
    return 'https://bitpay.ir/payment/' . $path;
    //return 'https://bitpay.ir/payment-test/' . $path;
}

add_action( 'init', 'dcs_do_payment' );
function dcs_do_payment(){

    if(
        isset( $_GET['gateway'] )
        &&
        isset( $_GET['register_id'] )
        &&
        isset( $_GET['dcs_nonce'] )
        &&
        isset( $_GET['trans_id'] )
        &&
        isset( $_GET['id_get'] )
    ){


        $nonce          = $_GET['dcs_nonce'];
        $gateway        = sanitize_key(  $_GET['gateway'] );
        $register_id    = absint( $_GET['register_id'] );

        if( get_post_type( $register_id ) != 'course_register' ){
            return;
        }

        if( get_post_status( $register_id ) != 'pending' ){
            return;
        }

        if( get_the_excerpt( $register_id ) != $nonce ){
            return;
        }

        $id_get     = sanitize_text_field( $_GET['id_get'] );
        if( $id_get != get_post_meta( $register_id, '_bitpay_payment_id', true ) ){
            return;
        }

        $trans_id   = absint( $_GET['trans_id'] );
        if( $trans_id ){
            update_post_meta( $register_id, '_bitpay_trans_id', $trans_id );
        }

        $register_request = get_post( $register_id );

        $args = [
            'api'       => dcs_get_api_key(),
            'trans_id'  => $trans_id,
            'id_get'    => $id_get,
            'json'      => 1,
        ];

        $verify_request = wp_remote_post(
            dcs_bitpay_url( 'gateway-result-second'),
            [
                'body'  => $args
            ]
        );

        $error_message = '';

        if( ! is_wp_error( $verify_request ) && wp_remote_retrieve_response_code( $verify_request ) == 200 ){

            $result = json_decode( wp_remote_retrieve_body( $verify_request ) );
            

            if( isset( $result->status ) ){
                
                if( $result->status != 1 && $result->status != 11 ){
                    $error_message = dcs_get_bitpay_error_message( $result->status );
                    dcs_fail_register( $register_id, $error_message );
                    wp_safe_redirect( get_the_permalink( $register_request->post_parent ) . '?dcs_payment_error=true' );
                    exit;
                    return;
                }

                if( $result->amount != $register_request->menu_order ){
                    $error_message = __( 'invalid amount', 'daneshjooyar-course-shop' );
                    dcs_fail_register( $register_id, $error_message );
                    wp_safe_redirect( get_the_permalink( $register_request->post_parent ) . '?dcs_payment_error=true' );
                    exit;
                    return;
                }

                if( isset( $result->cardNum ) ){
                    update_post_meta( $register_id, '_bitpay_card_number', $result->cardNum );
                }

                if( $result->factorId != $register_id ){
                    $error_message = __( 'invalid factor id', 'daneshjooyar-course-shop' );
                    dcs_fail_register( $register_id, $error_message );
                    wp_safe_redirect( get_the_permalink( $register_request->post_parent ) . '?dcs_payment_error=true' );
                    exit;
                    return;
                }
                
                dcs_compelete_register( $register_id );

                wp_safe_redirect( get_the_permalink( $register_request->post_parent ) );
                exit;

            }

        }
        
    }

    //gateway=bitpay&register_course_id=789&action=dcs_payment
    if(
        isset( $_GET['gateway'] )
        &&
        isset( $_GET['register_course_id'] )
        &&
        isset( $_GET['action'] )
        &&
        $_GET['action'] == 'dcs_payment'
        &&
        is_user_logged_in()
    ){

        $course_id  = absint( $_GET['register_course_id'] );
        $gateway    = sanitize_key( $_GET['gateway'] );

        if( get_post_type( $course_id ) != 'course' || get_post_status( $course_id ) != 'publish'  ){
            wp_die( 
                __('Course is invalid', 'daneshjooyar-course-shop' )
             );
        }

        $final_price    = dcs_get_course_final_price( $course_id );

        $register_id    = dcs_create_course_register( 
            get_current_user_id(),
            $course_id,
            $gateway
        );

        $nonce_action = sprintf(
            '%s-%d'
            , $gateway
            , $register_id
        );

        $nonce  = get_the_excerpt( $register_id );
        
        $args = [
            'api'       => dcs_get_api_key(),
            'amount'    => $final_price,
            'redirect'  => get_the_permalink( $course_id ) . sprintf(
                '?gateway=%s&action=dcs_verify&register_id=%d&dcs_nonce=%s'
                , $gateway
                , $register_id
                , $nonce
            ),
            'factorId'  => intval( $register_id )
        ];
        
        $payment_request = wp_remote_post(
            dcs_bitpay_url( 'gateway-send'),
            [
                'body'  => $args
            ]
        );
        
        if( ! is_wp_error( $payment_request ) && wp_remote_retrieve_response_code( $payment_request ) == 200 ){
            $payment_id = intval( wp_remote_retrieve_body( $payment_request) );
            if( $payment_id < 0 ){
                
                $error_message = dcs_get_bitpay_error_message( $payment_id );
                dcs_fail_register( $register_id, $error_message );
                wp_safe_redirect( get_the_permalink( $course_id ) . '?dcs_payment_error=true' );
                    exit;

            }else{

                update_post_meta( $register_id, '_bitpay_payment_id', $payment_id );

                $payment_url = sprintf(
                    dcs_bitpay_url( 'gateway-%d-get')
                    , $payment_id
                );
                
                wp_redirect( $payment_url );
                exit;

            }

        }

    }

}