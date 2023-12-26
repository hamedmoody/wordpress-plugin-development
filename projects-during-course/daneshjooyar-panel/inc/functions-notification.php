<?php
defined('ABSPATH') || exit;

function daneshjooyar_panel_notify( $to, $text ){

    $params     = [
        'to'            => $to,
        'text'          => print_r( $text, true ),
        'parse_mode'    => 'html'
    ];
    
    $ch         = curl_init( 'https://notificator.ir/api/v1/send' );
    
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    curl_setopt( $ch, CURLOPT_POSTFIELDS     , http_build_query( $params ) );
    
    $result     = curl_exec( $ch );
    
    curl_close( $ch );
    
    $result     = json_decode( $result );

}

function daneshjooyar_panel_user_notify( $user_id, $text ){

    $token = get_user_meta( $user_id, 'notificator_token', true );
    if( $token ){
        daneshjooyar_panel_notify( $token, $text );
    }

}

function daneshjooyar_panel_operator_notify( $text ){

    $operator = 1;
    daneshjooyar_panel_user_notify( $operator, $text );

}