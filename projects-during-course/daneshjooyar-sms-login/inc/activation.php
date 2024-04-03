<?php

defined('ABSPATH') || exit;

function daneshjooyar_sms_login_activation(){

    global $wpdb;
    
    $table              = $wpdb->dsl_sms_login;
    $table_collation    = $wpdb->collate;

    $sql = "
    CREATE TABLE `$table` (
        `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
        `phone` varchar(11) NOT NULL,
        `code` varchar(20) NOT NULL,
        `message_id` bigint(20) unsigned NOT NULL DEFAULT 0,
        `price` smallint(5) unsigned NOT NULL DEFAULT 0,
        `status` varchar(20) NOT NULL DEFAULT 'pending',
        `expired_at` datetime NOT NULL,
        `created_at` datetime NOT NULL,
        `updated_at` datetime NOT NULL,
        PRIMARY KEY (`ID`),
        KEY `user_id` (`user_id`),
        KEY `status` (`status`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=$table_collation
    ";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    /**
     * Mange Roles
     */
    
    add_role(
      'sms_manager',
      'مدیر پیامک',
      [
        'read'                => true,
        'view_sms'            => true,
        'manage_sms_options'  => true,
      ]
    );

    add_role(
      'sms_operator',
      'اپراتور پیامک',
      [
        'read'                => true,
        'view_sms'            => true,
        //'manage_sms_options'  => true,
      ]
    );

    $admin_rol = get_role( 'administrator' );
    if( $admin_rol ){
      $admin_rol->add_cap( 'view_sms' );
      $admin_rol->add_cap( 'manage_sms_options' );
    }


}

function daneshjooyar_sms_login_deactivation(){

  $users = get_users( [
    'role__in'  => ['sms_manager', 'sms_operator']
  ] );

  if( empty( $users ) ){
    remove_role( 'sms_manager' );
    remove_role( 'sms_operator' );
  }

}