<?php
defined('ABSPATH') || exit;

add_action( 'daneshjooyar_panel_ticket_sent', 'daneshjooyar_panel_on_sent_ticket' );
function daneshjooyar_panel_on_sent_ticket( $ticket_id ){

    $ticket             = get_post( $ticket_id );
    $operator_message   = 'تیکت جدید: ' . $ticket->post_title  . PHP_EOL . PHP_EOL;
    $operator_message.= sprintf( '<a href="%s">#%s</a>', daneshjooyar_panel_url( 'ticket/view?ticket_id=' . $ticket_id ), $ticket_id );
    $operator_message.= PHP_EOL . $ticket->post_content;

    daneshjooyar_panel_operator_notify( $operator_message );


    $user_text = 'تیکت شما ارسال شد. در اسرع وقت پاسخ میدیم';
    daneshjooyar_panel_user_notify( $ticket->post_author, $user_text );

}

add_action( 'daneshjooyar_panel_ticket_reply', 'daneshjooyar_panel_on_reply_ticket', 10, 2 );
function daneshjooyar_panel_on_reply_ticket( $ticket_id, $reply_id ){

    $ticket             = get_post( $ticket_id );
    $reply              = get_post( $reply_id );
    $replier            = get_user_by( 'ID', $reply->post_author );
    $user_text   = 'تیکت : ' . $ticket->post_title  . ' پاسخ داده شد' . PHP_EOL . PHP_EOL;
    $user_text.= sprintf( '<a href="%s">#%s</a>', daneshjooyar_panel_url( 'ticket/view?ticket_id=' . $ticket_id ), $ticket_id );

    daneshjooyar_panel_user_notify( $ticket->post_author, $user_text );

}

add_action( 'transition_post_status', 'daneshjooyar_panel_on_ticket_change_status', 10, 3 );
function daneshjooyar_panel_on_ticket_change_status( $new_status, $old_status, $ticket ){
    
    if( $ticket->post_type != 'ticket' ){
        return;
    }

    if( ! $ticket->post_parent && $new_status == 'close' ){
        $user_text = 'تیکت شماره ' . $ticket->ID . ' بسته شد';
        daneshjooyar_panel_user_notify( $ticket->post_author, $user_text );
    }

}