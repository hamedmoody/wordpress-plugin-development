<?php
/**
 * Plugin Name: Manage Employees
 * Plugin URI: https://daneshjooyar.com
 * Description: This plugins is for manage employees
 * Author: Hamed Moodi
 * Author URI: https://daneshjooyar.com/teacher/h_m_mood
 * Text Domain: daneshjooyar-manage-employees
 * Domain Path: /languages
 */

 defined( 'ABSPATH' ) || exit;

 define( 'DANESHJOOYAR_MANAGE_EMPLOYEES_ADMIN_PATH', plugin_dir_path( __FILE__ ) . 'admin/' );
 define( 'DANESHJOOYAR_MANAGE_EMPLOYEES_VIEW', plugin_dir_path( __FILE__ ) . 'view/' );
 define( 'DANESHJOOYAR_MANAGE_EMPLOYEES_IMAGE', plugin_dir_url( __FILE__ ) . 'assets/images/' );

add_action( 'plugins_loaded', function(){
  load_plugin_textdomain(
    'daneshjooyar-manage-employees',
    false,
    dirname( plugin_basename( __FILE__ ) ) . '/languages'
  );
} );

 global $wpdb;
 $wpdb->dyme_employees = $wpdb->prefix  . 'dyme_employees';

if( is_admin() ){
   include( DANESHJOOYAR_MANAGE_EMPLOYEES_ADMIN_PATH . 'menus.php' );
}

function dyme_add_employee_stat( $result_format ){
  $GLOBALS['dyme_result_format'] = $result_format;
  add_action( 'login_form', 'dyme_add_employee_stat_view' );
}

function dyme_add_employee_stat_view(){

  global $wpdb;
  global $dyme_result_format;
  $count = $wpdb->get_var("SELECT * FROM {$wpdb->dyme_employees}");
  
  printf(
    translate_nooped_plural( $dyme_result_format, $count, 'daneshjooyar-manage-employees' ),
    $count
  );

}

register_activation_hook( __FILE__, 'dyme_install' );
function dyme_install(){

    global $wpdb;
    
    $table_employees    = $wpdb->prefix . 'dyme_employees';
    $table_collation    = $wpdb->collate;

    $sql = "
    CREATE TABLE `$table_employees` (
        `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `first_name` varchar(50) NOT NULL,
        `last_name` varchar(50) NOT NULL,
        `birthdate` date DEFAULT NULL,
        `avatar` varchar(250) NOT NULL,
        `weight` float NOT NULL,
        `mission` smallint(5) unsigned NOT NULL,
        `created_at` datetime NOT NULL DEFAULT current_timestamp()
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=$table_collation COMMENT='This is table keep employees information'
    ";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

}
