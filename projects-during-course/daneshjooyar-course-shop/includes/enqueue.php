<?php
defined('ABSPATH') || exit;

add_action( 'admin_enqueue_scripts', 'dcs_admin_enqueue' );
function dcs_admin_enqueue( $hook ){

    wp_register_script(
        'select2',
        DANESHJOOYAR_COURSE_SHOP_JS . 'select2.min.js',
        [],
        '4.1.0-rc.0',
        true
    );

    wp_register_script(
        'jalali-date-picker',
        DANESHJOOYAR_COURSE_SHOP_JS . 'jalalidatepicker.0.9.3.js',
        [],
        '0.9.3',
        true
    );

    wp_register_script(
        'jalali-moment',
        DANESHJOOYAR_COURSE_SHOP_JS . 'jalali-moment.3.3.11.js',
        [],
        '3.3.11',
        true
    );

    

    wp_register_style( 
        'select2',
        DANESHJOOYAR_COURSE_SHOP_CSS . 'select2.min.css',
        [],
        '4.1.0-rc.0'
    );

    wp_register_style( 
        'jalali-date-picker',
        DANESHJOOYAR_COURSE_SHOP_CSS . 'jalalidatepicker.0.9.3.css',
        [],
        '0.9.3'
    );
    
    $js_deps = [
        'jquery',
        'jalali-moment',
        'select2',
        'jalali-date-picker',
        'jquery-ui-core',
        'jquery-ui-sortable'
    ];

    wp_enqueue_media();

    wp_enqueue_script(
        'dcs_admin_js',
        DANESHJOOYAR_COURSE_SHOP_JS . 'admin.js',
        $js_deps,
        DANESHJOOYAR_COURSE_SHOP_ASSETS_VERSION,
        true
    );

    $admin_localized_data = [
        'i18n'  => [
            'demo_uploader_title'   => __( 'Demo Uploader', 'daneshjooyar-course-shop' ),
            'sure_delete'           => __( 'Are you sure for delete item?', 'daneshjooyar-course-shop' ),
        ]
    ];

    wp_localize_script( 'dcs_admin_js', 'dcs', $admin_localized_data );

    $css_deps = [
        'select2',
        'jalali-date-picker'
    ];

    wp_enqueue_style(
        'dcs_admin_css',
        DANESHJOOYAR_COURSE_SHOP_CSS . 'admin.css',
        $css_deps,
        DANESHJOOYAR_COURSE_SHOP_ASSETS_VERSION
    );

}


add_action( 'wp_enqueue_scripts', 'dcs_public_enqueue' );
function dcs_public_enqueue(){
    
    $js_deps = [
        'jquery',
    ];

    wp_enqueue_script(
        'dcs_public_js',
        DANESHJOOYAR_COURSE_SHOP_JS . 'public.js',
        $js_deps,
        DANESHJOOYAR_COURSE_SHOP_ASSETS_VERSION,
        true
    );

    $public_localized_data = [
        
    ];

    wp_localize_script( 'dcs_public_js', 'dcs', $public_localized_data );

    $css_deps = [
        
    ];

    wp_enqueue_style(
        'dcs_public_css',
        DANESHJOOYAR_COURSE_SHOP_CSS . 'public.css',
        $css_deps,
        DANESHJOOYAR_COURSE_SHOP_ASSETS_VERSION
    );

}

add_action( 'admin_footer', 'dcs_add_failed_status' );
function dcs_add_failed_status(){
    global $post;
    if( $post && $post->post_type == 'course_register' ){
        include DANESHJOOYAR_COURSE_SHOP_VIEW_ADMIN . 'post_status.php';
    }
}