<?php
/**
 * Plugin Name: دیجی کالا
 */
defined( 'ABSPATH' ) || exit;

define( 'PLUGIN_DIGIKALA_URL', plugin_dir_url( __FILE__ ) );
define( 'PLUGIN_DIGIKALA_ASSETS_URL', PLUGIN_DIGIKALA_URL . 'assets/' );
define( 'PLUGIN_DIGIKALA_CSS_URL', PLUGIN_DIGIKALA_ASSETS_URL . 'css/' );
define( 'PLUGIN_DIGIKALA_JS_URL', PLUGIN_DIGIKALA_ASSETS_URL . 'js/' );
define( 'PLUGIN_DIGIKALA_IMAGES_URL', PLUGIN_DIGIKALA_ASSETS_URL . 'images/' );

define( 'PLUGIN_DIGIKALA_PATH', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_DIGIKALA_ADMIN_PATH', plugin_dir_path( __FILE__ ) . 'admin/' );
define( 'PLUGIN_DIGIKALA_VIEW_PATH', plugin_dir_path( __FILE__ ) . 'view/' );


include_once PLUGIN_DIGIKALA_PATH . 'shortcode.php';
include_once PLUGIN_DIGIKALA_PATH . 'enqueue.php';
include_once PLUGIN_DIGIKALA_PATH . 'functions.php';
include_once PLUGIN_DIGIKALA_PATH . 'ajax.php';