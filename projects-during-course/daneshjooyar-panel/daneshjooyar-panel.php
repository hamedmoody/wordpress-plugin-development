<?php
/**
 * Plugin Name: Daneshjooyar Panel
 */

 defined('ABSPATH') || exit;

 define( 'DANESHJOOYAR_PANEL_ASSETS'    , plugin_dir_url( __FILE__ ) . 'assets/' );
 define( 'DANESHJOOYAR_PANEL_JS'        , DANESHJOOYAR_PANEL_ASSETS . 'js/' );
 define( 'DANESHJOOYAR_PANEL_CSS'       , DANESHJOOYAR_PANEL_ASSETS . 'css/' );
 define( 'DANESHJOOYAR_PANEL_IMAGES'    , DANESHJOOYAR_PANEL_ASSETS . 'images/' );

 define( 'DANESHJOOYAR_PANEL_INC', plugin_dir_path( __FILE__ ) . 'inc/' );
 define( 'DANESHJOOYAR_PANEL_VIEW', plugin_dir_path( __FILE__ ) . 'view/' );

 define( 'DANESHJOOYAR_PANEL_VERSION', '1.0.0' );

 if( ! defined( 'DANESHJOOYAR_PANEL_BASE' ) ){
    define( 'DANESHJOOYAR_PANEL_BASE', 'panel' );
 }

require( DANESHJOOYAR_PANEL_INC . 'init.php' );
require( DANESHJOOYAR_PANEL_INC . 'functions-panel.php' );
require( DANESHJOOYAR_PANEL_INC . 'functions-notification.php' );
require( DANESHJOOYAR_PANEL_INC . 'notification.php' );
require( DANESHJOOYAR_PANEL_INC . 'ajax.php' );

register_activation_hook( __FILE__, 'daneshjooyar_panel_activation' );
function daneshjooyar_panel_activation(){

    daneshjooyar_panel_rewrite();

    flush_rewrite_rules();

}