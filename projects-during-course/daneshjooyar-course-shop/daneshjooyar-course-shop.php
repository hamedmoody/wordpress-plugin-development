<?php
/*
 * Plugin Name:       Daneshjooyar Course Shop
 * Plugin URI:        https://daneshjooyar.com
 * Description:       This simple plugin for sale video course
 * Version:           1.0.0
 * Requires at least: 4.4.0
 * Requires PHP:      7.2
 * Author:            Hamed Moodi
 * Author URI:        https://daneshjooyar.com/teacher/h_m_mood/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       daneshjooyar-course-shop
 * Domain Path:       /languages
 */

defined('ABSPATH') || exit;


define( 'DANESHJOOYAR_COURSE_SHOP_ADMIN_PATH', plugin_dir_path( __FILE__ ) . 'admin/' );
define( 'DANESHJOOYAR_COURSE_SHOP_VIEW', plugin_dir_path( __FILE__ ) . 'view/' );
define( 'DANESHJOOYAR_COURSE_SHOP_INCLUDES', plugin_dir_path( __FILE__ ) . 'includes/' );
define( 'DANESHJOOYAR_COURSE_SHOP_TEMPLATES', plugin_dir_path( __FILE__ ) . 'templates/' );
define( 'DANESHJOOYAR_COURSE_SHOP_VIEW_ADMIN', DANESHJOOYAR_COURSE_SHOP_VIEW . 'admin/' );
define( 'DANESHJOOYAR_COURSE_SHOP_VIEW_PUBLIC', DANESHJOOYAR_COURSE_SHOP_VIEW . 'public/' );

define( 'DANESHJOOYAR_COURSE_SHOP_IMAGE', plugin_dir_url( __FILE__ ) . 'assets/images/' );
define( 'DANESHJOOYAR_COURSE_SHOP_CSS', plugin_dir_url( __FILE__ ) . 'assets/css/' );
define( 'DANESHJOOYAR_COURSE_SHOP_JS', plugin_dir_url( __FILE__ ) . 'assets/js/' );

define( 'DANESHJOOYAR_COURSE_SHOP_VERSION', '1.0.0' );
define(
  'DANESHJOOYAR_COURSE_SHOP_ASSETS_VERSION',
  defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : DANESHJOOYAR_COURSE_SHOP_VERSION
);

add_action( 'plugins_loaded', function(){
 load_plugin_textdomain(
   'daneshjooyar-course-shop',
   false,
   dirname( plugin_basename( __FILE__ ) ) . '/languages'
 );
} );

register_activation_hook( __FILE__,  function(){
  dcs_register_course_post_type();
  flush_rewrite_rules();
} );

register_deactivation_hook( __FILE__, function(){
  flush_rewrite_rules();
} );

/**
 * Include modules
 */
require( DANESHJOOYAR_COURSE_SHOP_INCLUDES . 'functions.php' );
require( DANESHJOOYAR_COURSE_SHOP_INCLUDES . 'post_type.php' );
require( DANESHJOOYAR_COURSE_SHOP_INCLUDES . 'taxonomy.php' );
require( DANESHJOOYAR_COURSE_SHOP_INCLUDES . 'templates.php' );
require( DANESHJOOYAR_COURSE_SHOP_INCLUDES . 'options.php' );
require( DANESHJOOYAR_COURSE_SHOP_INCLUDES . 'enqueue.php' );
require( DANESHJOOYAR_COURSE_SHOP_INCLUDES . 'functions-register.php' );
require( DANESHJOOYAR_COURSE_SHOP_INCLUDES . 'functions-course.php' );
require( DANESHJOOYAR_COURSE_SHOP_INCLUDES . 'payment.php' );
if( is_admin() ){
  require( DANESHJOOYAR_COURSE_SHOP_INCLUDES . 'metaboxes.php' );
}