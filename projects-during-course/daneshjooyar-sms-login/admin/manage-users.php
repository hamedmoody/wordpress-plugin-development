<?php
defined('ABSPATH') || exit;

add_action( 'edit_user_profile', 'dsl_user_inputs' );
add_action( 'show_user_profile', 'dsl_user_inputs' );
add_action( 'user_new_form', 'dsl_user_inputs' );

function dsl_user_inputs( $user ){

    $phone  = '';
    if( is_a( $user, 'WP_User' ) ){
        $phone = $user->phone;
    }

    include DANESHJOOYAR_SMS_LOGIN_VIEW . 'user-inputs.php';
}

add_action( 'edit_user_profile_update', 'dsl_update_user' );
add_action( 'personal_options_update', 'dsl_update_user' );
add_action( 'user_register', 'dsl_update_user' );

function dsl_update_user( $user_id ){

    if( ! current_user_can( 'edit_user', $user_id ) ){
        return;
    }

    if( isset( $_POST['dsl_phone'] ) ){
        $phone = sanitize_phone( $_POST['dsl_phone'] );
        update_user_meta( $user_id, 'phone', $phone );
    }

}

add_filter( 'manage_users_columns', 'dsl_users_columns' );
function dsl_users_columns( $cols ){
    $cols['rate']   = 'امتیاز';
    $cols['phone']  = 'تلفن';
    return $cols;
}

add_filter( 'manage_users_custom_column', 'dsl_users_columns_data', 10, 3 );
function dsl_users_columns_data( $output, $column_name, $user_id ) {
    if( $column_name == 'rate' ){
        $output = get_user_meta( $user_id, 'rate', true );
        if( ! $output ){
            $output = 0;
        }
    }elseif( $column_name == 'phone' ){
        $output = get_user_meta( $user_id, 'phone', true );
        if( ! $output ){
            $output = '-';
        }
    }
    return $output;
}

add_filter( 'manage_users_sortable_columns', 'dsl_users_sortable_columns' );
function dsl_users_sortable_columns( $sortable_columns ){
    $sortable_columns['rate'] = 'rate';
    $sortable_columns['phone'] = 'phone';
    return $sortable_columns;
}

add_filter( 'users_list_table_query_args', 'dsl_users_sortable_query' );
function dsl_users_sortable_query( $args ){

    if( isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], ['phone', 'rate'] ) ){
        $args['orderby']    = $_GET['orderby'] == 'rate' ? 'meta_value_num' : 'meta_value';
        $args['meta_key']   = sanitize_key( $_GET['orderby'] );
    }

    if( isset($_GET['filter_rank']) ){
        if( isset( $_GET['ranking'] ) && in_array( $_GET['ranking'], ['A', 'B', 'C', 'D', 'E'] ) ){

            $rank_key = sanitize_key( $_GET['ranking'] );

            $ranks = [
                'a' => [81,100],
                'b' => [61,80],
                'c' => [41,60],
                'd' => [21,40],
                'e' => [0,20],
            ];

            $args['meta_query'] = [
                [
                    'key'       => 'rate',
                    'value'     => $ranks[$rank_key],
                    'type'      => 'numeric',
                    'compare'   => 'BETWEEN',
                ]
            ];

        }
    }

    return $args;
}

add_filter( 'bulk_actions-users', 'dsl_user_bulk_actions' );
function dsl_user_bulk_actions( $actions ){
    $actions['ویژه سازی کاربران'] = [
        'set_special'       => 'کاربران ویژه شوند',
        'unset_special'   => 'کاربران از ویژه خارج شوند',
    ];
    return $actions;
}

add_filter( 'handle_bulk_actions-users', 'dsl_handle_user_bulk_actions', 10, 3 );
function dsl_handle_user_bulk_actions( $redirect_url, $action, $user_ids ){
    if( in_array( $action, ['set_special', 'unset_special'] ) ){
        foreach( $user_ids as $user_id ){
            if( $action == 'set_special' ){
                update_user_meta( $user_id, 'dsl_is_speical', 1 );
            }else{
                delete_user_meta( $user_id, 'dsl_is_speical' );
            }
        }
        $redirect_url = add_query_arg( 'user_' . $action, count($user_ids), $redirect_url );
    }
    return $redirect_url;
}

add_action( 'admin_notices', 'dsl_bulk_notices' );
function dsl_bulk_notices(){
    $message = '';
    if( isset( $_REQUEST['user_set_special'] ) ){
        $message = sprintf( '<div class="notice updated is-dismissible"><p>تعداد %d کاربر با موفقیت به کاربر ویژه تبدیل شدند</p></div>', absint( $_REQUEST['user_set_special'] ) );
    }elseif( isset( $_REQUEST['user_unset_special'] ) ){
        $message = sprintf( '<div class="notice updated is-dismissible"><p>تعداد %d کاربر با موفقیت به از کاربری ویژه برکنار شدند</p></div>', absint( $_REQUEST['user_unset_special'] ) );
    }
    echo $message;
}

add_action( 'manage_users_extra_tablenav', 'dsl_ranking_field' );
function dsl_ranking_field( $which ){
    $name = 'ranking';
    $button_id  = 'filter_rank';
    if( $which == 'bottom' ){
        $name = 'ranking2';
        $button_id = 'filter_rank2';
    }
    
    $selected = isset( $_GET['ranking'] ) && in_array(  $_GET['ranking'], ['A','B', 'C', 'D', 'E'] ) ? $_GET['ranking'] : '';

    ?>
    <div class="alignleft actions">
		<label class="screen-reader-text" for="new_role">تغییر نقش به …</label>
		<select name="<?php echo $name;?>" id="<?php echo $name;?>">
			<option value="">انتخاب رنگنیگ</option>
			<option value="A" <?php selected( $selected, 'A' );?>>Rank A</option>
			<option value="B" <?php selected( $selected, 'B' );?>>Rank B</option>
			<option value="C" <?php selected( $selected, 'C' );?>>Rank C</option>
			<option value="D" <?php selected( $selected, 'D' );?>>Rank D</option>
			<option value="E" <?php selected( $selected, 'E' );?>>Rank E</option>
		</select>
		<?php submit_button( 'فیلتر رنگینگ', '', $button_id, false );?>
    </div>
    <?php
}

add_action( 'admin_menu', 'dsl_menus' );
function dsl_menus(){

    add_menu_page(
        'پیامک ها',
        'پیامک ها',
        'view_sms',
        'dsl-sms',
        function(){echo 'SmsList';}
    );

    add_submenu_page(
        'dsl-sms',
        'تنظیمات',
        'تنظیمات',
        'manage_sms_options',
        'dsl-sms-settings',
        function(){echo 'Sms Settings';}
    );

}

/**
 * Add a new dashboard widget.
 */
function dsl_sms_widget() {
	wp_add_dashboard_widget( 'dashboard_widget', 'خلاصه گزارش پیامک ها', 'dsl_widget_view' );
}
add_action( 'wp_dashboard_setup', 'dsl_sms_widget' );

function dsl_widget_view(){
    echo 'Sms Dashboard Widget';
}