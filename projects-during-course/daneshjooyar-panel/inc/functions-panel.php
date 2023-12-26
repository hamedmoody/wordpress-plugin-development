<?php
defined('ABSPATH') || exit;
function daneshjooyar_panel_template_path( $panel_page = false, $panel_action = false ){

    if( ! $panel_page ){
        $panel_page = get_query_var( 'panel_page' );
    }

    if( $panel_page ){

        $path   = DANESHJOOYAR_PANEL_VIEW;

        $founed = false;

        if( $panel_page == 'dashboard' ){
            $path.= 'dashboard.php';
            $founed = true;
        }

        if( $panel_page == 'login' ){
            $path.= 'login.php';
            $founed = true;
        }

        if( $panel_page == 'signup' ){
            $path.= 'signup.php';
            $founed = true;
        }

        if( $panel_page == 'profile' ){
            $path.= 'profile.php';
            $founed = true;
        }

        if( $panel_page == 'ticket' ){
            if( $panel_action == 'new' ){
                $path.= 'ticket-new.php';
            }elseif( $panel_action == 'view' ){
                $path.= 'ticket.php';
            }else{
                $path.= 'tickets.php';
            }
            $founed = true;
        }

        if( ! $founed ){
            $path.= '404.php';
        }

        return $path;

    }

    return false;

}

function daneshjooyar_panel_get_ticekts( $args ){

    $args['post_type']      = 'ticket';
    
    $args                   = wp_parse_args( $args, [
        'post_status'   => ['answer', 'pending', 'close'],
    ] );

    return get_posts( $args );
}

function daneshjooyar_panel_translate_ticket_status( $status ){
    $statuses = [
        'close'     => 'بسته شده',
        'pending'   => 'در انتظار پاسخ',
        'answer'    => 'پاسخ داده شده'
    ];
    return isset( $statuses[$status] ) ? $statuses[$status] : 'پشتیبانی';
}


function daneshjooyar_panel_translate_ticket_priority( $prioritiy ){
    $priorities = [
        'low'       => 'پایین',
        'normal'    => 'متوسط',
        'high'      => 'ضروری'
    ];
    return isset( $priorities[$prioritiy] ) ? $priorities[$prioritiy] : 'پایین';
}

function daneshjooyar_get_current_url(){
    return $_SERVER['REQUEST_URI'];
}

function daneshjooyar_panel_url( $path = '', $redirect = false ){
    $url = home_url( untrailingslashit( DANESHJOOYAR_PANEL_BASE ) . '/' . untrailingslashit( $path ) );
    if( $redirect ){
        $url.= '?redirect=' . $redirect;
    }
    return $url;
}

function daneshjooyar_panel_version_asset(){
    return defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : DANESHJOOYAR_PANEL_VERSION;
}

function daneshjooyar_panel_page(){
    return get_query_var('panel_page');
}

function daneshjooyar_panel_title(){
    $title =  apply_filters( 'daneshjooyar_panel_title', 'پنل کاربری', daneshjooyar_panel_page() );
    return esc_html( $title );
}

function daneshjooyar_panel_js( $js_path = '' ){
    return DANESHJOOYAR_PANEL_JS . $js_path . '?ver=' . daneshjooyar_panel_version_asset() ;
}

function daneshjooyar_is_panel(){
    return get_query_var( 'panel_page' ) || strpos( $_SERVER['REQUEST_URI'], '/panel' ) === 0;
}

function daneshjooyar_is_panel_login(){
    return get_query_var( 'panel_page' ) == 'login';
}

function daneshjooyar_panel_get_pending_ticket_count(){

    $ticket_query = new WP_Query([
        'post_type'     => 'ticket',
        'post_parent'   => 0,
        'post_status'   => 'pending',
    ]);

    return $ticket_query->found_posts;

}

function daneshjooyar_user_can_manage_tickets( $user_id = 0 ){
    if( ! $user_id ){
        $user_id = get_current_user_id(  );
    }
    return user_can( $user_id, 'edit_others_posts' );
}

function daneshjooyar_is_panel_signup(){
    return get_query_var( 'panel_page' ) == 'signup';
}

function daneshjooyar_panel_css( $css_path = '' ){
    return DANESHJOOYAR_PANEL_CSS . $css_path . '?ver=' . daneshjooyar_panel_version_asset();
}

function daneshjooyar_panel_json(){
    
    $localize_data = [
        'ajax_url'  => admin_url( 'admin-ajax.php' )
    ];

    $localize_data    = apply_filters( 'daneshjooyar_panel_localize_data', $localize_data );

    return json_encode( $localize_data );

}

function daneshjooyar_panel_images( $images_path = '' ){
    return DANESHJOOYAR_PANEL_IMAGES . $images_path;
}

function daneshjooyar_panel_header(){
    include DANESHJOOYAR_PANEL_VIEW . 'header.php';
}

function daneshjooyar_panel_footer(){
    include DANESHJOOYAR_PANEL_VIEW . 'footer.php';
}

function daneshjooyar_panel_sidebar(){
    include DANESHJOOYAR_PANEL_VIEW . 'sidebar.php';
}

function daneshjooyar_panel_class( $class = '' ){
    
    $classes    = ['panel'];

    $class_list = explode( ' ', $class );

    $classes    = array_merge( $classes, $class_list  );

    $classes    = apply_filters( 'daneshjooyar_panel_class', $classes );

    $classes    = array_unique( $classes );

    $class_list = array_map( 'esc_attr', $class_list );

    return implode( ' ', $classes );

}