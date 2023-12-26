<?php
defined('ABSPATH') || exit;
add_action( 'init', 'daneshjooyar_panel_rewrite' );
function daneshjooyar_panel_rewrite(){

    add_rewrite_rule(
        DANESHJOOYAR_PANEL_BASE . '/([a-z0-9]+)/?([a-z0-9]+)?',
        'index.php?panel_page=$matches[1]&panel_action=$matches[2]',
        'top'
    );

    $labels = array(
		'name'                  => 'Tickets',
		'singular_name'         => 'Ticket',
	);

	$args = array(
		'labels'             => $labels,
		'public'             => false,
		//'publicly_queryable' => true,
		//'show_ui'            => true,
		//'show_in_menu'       => true,
		//'query_var'          => true,
		//'rewrite'            => array( 'slug' => 'book' ),
		//'capability_type'    => 'post',
		//'has_archive'        => true,
		'hierarchical'       => true,
		//'menu_position'      => null,
		//'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
	);

	register_post_type( 'ticket', $args );

    register_post_status( 'answer' );
    register_post_status( 'close' );

}

add_filter( 'query_vars', 'daneshjooyar_panel_vars' );
function daneshjooyar_panel_vars( $vars ){
    $vars[] = 'panel_page';
    $vars[] = 'panel_action';
    return $vars;
}

add_filter( 'template_include', 'daneshjooyar_panel_load' );
function daneshjooyar_panel_load( $template_path ){
    $panel_page     = get_query_var( 'panel_page' );
    $panel_action   = get_query_var( 'panel_action' );
    if( $panel_page ){
        $path = daneshjooyar_panel_template_path( $panel_page, $panel_action );
        if( $path ){
            return $path;
        }
    }
    return $template_path;
}

add_filter( 'daneshjooyar_panel_class', 'daneshjooyar_panel_body_class' );
function daneshjooyar_panel_body_class( $classes ){

    if( daneshjooyar_is_panel_login() ){
        $classes[] = 'panel-login';
    }

    if(  daneshjooyar_is_panel_signup() ){
        $classes[] = 'panel-login';
        $classes[] = 'panel-signup';
    }

    if( is_user_logged_in() ){
        $classes[] = 'panel-logged-in';
    }

    return $classes;

}

add_action( 'template_redirect', 'daneshjooyar_panel_redirect' );
function daneshjooyar_panel_redirect(){

    if( daneshjooyar_is_panel() ){

        if( isset( $_GET['logout'] ) && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'daneshjooyar-logout' ) ){
            wp_logout();
            wp_safe_redirect( daneshjooyar_panel_url( 'login?logged_out=ture' ) );
            exit;
        }

        if( daneshjooyar_is_panel_login() || daneshjooyar_is_panel_signup() ){
            if( is_user_logged_in() ){
                wp_safe_redirect( 
                    daneshjooyar_panel_url( 'dashboard' )
                 );
                exit;
            }
        }else{
            if(  ! is_user_logged_in() ){
                wp_safe_redirect( 
                    daneshjooyar_panel_url('login', daneshjooyar_get_current_url() )
                );
                exit;
            }
        }
    }

}

add_filter( 'get_avatar_url', 'daneshjooyar_panel_avatar', 10, 2 );
function daneshjooyar_panel_avatar( $url, $id_or_email ){
    $user_id = 0;
    if( strpos( $id_or_email, '@' ) > 0 ){
        $user = get_user_by( 'email', $id_or_email );
        if( $user ){
            $user_id = $user->ID;
        }
    }else{
        $user_id = $id_or_email;
    }

    if( $user_id ){
        $avatar = get_user_meta( $user_id, 'daneshjooyar_avatar', true );
        if( $avatar ){
            $url = $avatar;
        }
    }

    return $url;
}