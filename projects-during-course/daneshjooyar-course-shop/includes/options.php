<?php 
defined('ABSPATH') || exit;

add_action( 'admin_menu' ,'dcs_options_page_menu' );
function dcs_options_page_menu(){

    add_submenu_page(
        'edit.php?post_type=course',
        __( 'Options', 'daneshjooyar-course-shop' ),
        __( 'Options', 'daneshjooyar-course-shop' ),
        'manage_options',
        'dcs_options',
        'dcs_options_callback'
    );

}

function dcs_options_callback(){
    
    echo '<form action="options.php" method="POST">';

        settings_fields( 'dcs-option-gateway' );
        do_settings_sections( 'dcs-option-sections' );

        submit_button();

    echo '</form>';

}

add_action( 'admin_init', 'dcs_reigster_settings' );
function dcs_reigster_settings(){

    add_settings_section( 
        'dcs_gateway',
        __( 'Gateway settings', 'daneshjooyar-course-shop' ),
        'dcs_section_cb',
        'dcs-option-sections'
    );

    add_settings_field(
        'dcs_gateway_api',
        __('gateway api', 'daneshjooyar-course-shop'),
        'dcs_gateway_api_cb',
        'dcs-option-sections',
        'dcs_gateway',
        [
            'label_for' => 'dcs_gateway_api'
        ]
    );

    register_setting(
        'dcs-option-gateway',
        'dcs_option_gateway_api',
        [
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

}

function dcs_gateway_api_cb( $args ){
    ?>
    <input
    type="text"
    name="dcs_option_gateway_api"
    id="dcs_gateway_api"
    id="<?php echo esc_attr( $args['label_for'] );?>"
    value="<?php echo esc_attr( get_option( 'dcs_option_gateway_api' ) );?>"
    >
    <?php
}

function dcs_section_cb(){
    
}