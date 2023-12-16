<?php
defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', 'dyme_add_menus' );
function dyme_add_menus(){

    $list_hook_suffix = add_menu_page(
        esc_html__( 'Employees', 'daneshjooyar-manage-employees' ),
        esc_html__( 'Employees', 'daneshjooyar-manage-employees' ),
        'editor',
        'dyme_employees',
        'dyme_render_list'
    );

    add_action( 'load-' . $list_hook_suffix, 'dyme_proccess_deletion' );
    add_action( 'load-' . $list_hook_suffix, 'dyme_process_table_data' );

    add_submenu_page(
        'dyme_employees',
        esc_html__('New employee', 'daneshjooyar-manage-employees'),
        esc_html__('New employee', 'daneshjooyar-manage-employees'),
        'editor',
        'dyme_employees_create',
        'dyme_render_form'
    );

}

function dyme_process_table_data(){
    require( DANESHJOOYAR_MANAGE_EMPLOYEES_ADMIN_PATH . 'Employee_List_table.php' );
    $GLOBALS['employee_list_table'] = new Employee_List_Table();
    $GLOBALS['employee_list_table']->prepare_items();
}

function dyme_proccess_deletion(){
    if( isset( $_GET['action'] ) && $_GET['action'] == 'delete_employee' && isset( $_GET['id'] ) ){
        
        $employee_id = absint( $_GET['id'] );
        
        if( ! isset( $_GET['csrf'] ) || ! wp_verify_nonce( $_GET['csrf'], 'delete_employee' . $_GET['id'] )  ){
            wp_die('No correct nonce');
        }

        global $wpdb;
        $table_employees = $wpdb->prefix . 'dyme_employees';

        $deleted = $wpdb->delete(
            $table_employees,
            [
                'ID'            => $employee_id,
            ],
            [
                '%d'
            ]
        );

        if( $deleted ){

            wp_redirect(
                admin_url( 'admin.php?page=dyme_employees&employee_status=deleted' )
            );
            exit;

        }else{
            wp_redirect(
                admin_url( 'admin.php?page=dyme_employees&employee_status=deleted_error' )
            );
            exit;
        }

    }
}

function dyme_render_list(){
    include DANESHJOOYAR_MANAGE_EMPLOYEES_VIEW . 'list_employees.php';
}

function dyme_render_form(){

    global $wpdb;
    $employee       = false;
    if( isset( $_GET['employee_id'] ) ){
        $employee_id = absint( $_GET['employee_id'] );
        if( $employee_id ){
            
            $sql = $wpdb->prepare(
                "SELECT * FROM $wpdb->dyme_employees WHERE ID = %d",
                $employee_id
            );

            $employee = $wpdb->get_row( $sql );
        }
    }

    include DANESHJOOYAR_MANAGE_EMPLOYEES_VIEW . 'form_employees.php';
}

add_action( 'admin_init', 'dyme_form_submit' );
function dyme_form_submit(){

    global $pagenow;
    if( $pagenow == 'admin.php' && isset( $_GET['page'] ) && $_GET['page'] == 'dyme_employees_create' ){
        
        if( isset( $_POST['save_employee'] )  ){

            $employee_id     = absint( $_POST['ID'] );

            if( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'edit_employee' . $employee_id ) ){
                wp_die('nonce invalid');
            }

            if( ! check_admin_referer( 'edit_employee' . $employee_id ) ){
                wp_die('You should send data from form');
            }

        
            $data   = [
                'first_name'    => sanitize_text_field( $_POST['first_name'] ),
                'last_name'     => wp_kses_post( $_POST['last_name'] ),
                'birthdate'     => sanitize_text_field( $_POST['birthdate'] ),
                'avatar'        => sanitize_text_field( $_POST['avatar'] ),
                'mission'       => absint( $_POST['mission'] ),
                'weight'        => floatval( $_POST['weight'] ),
            ];

            global $wpdb;
            $table_employees = $wpdb->prefix . 'dyme_employees';

            if( $employee_id ){

                $updated_rows = $wpdb->update(
                    $table_employees,
                    $data,
                    [
                        'ID'    => $employee_id,
                    ],
                    [
                        '%s', '%s', '%s', '%s', '%d', '%f'
                    ],
                    [
                        '%d'
                    ]
                );

                if( $updated_rows === false ){
                    wp_redirect(
                        admin_url( 'admin.php?page=dyme_employees_create&employee_status=edited_error&employee_id=' . $employee_id )
                    );
                    exit;
                }else{
                    wp_redirect(
                        admin_url( 'admin.php?page=dyme_employees_create&employee_status=edited&employee_id=' . $employee_id )
                    );
                    exit;
                }

            }
            
            $data['created_at'] = current_time( 'mysql' );

            $inserted = $wpdb->insert(
                $table_employees,
                $data,
                [
                    '%s', '%s', '%s', '%s', '%d', '%f', '%s'
                ]
            );

            if( $inserted ){
                $employee_id = $wpdb->insert_id;
                wp_redirect(
                    admin_url( 'admin.php?page=dyme_employees_create&employee_status=inserted&employee_id=' . $employee_id )
                );
                exit;
                //Success
            }else{
                //Error
                wp_redirect(
                    admin_url( 'admin.php?page=dyme_employees_create&employee_status=inserted_error' )
                );
                exit;
            
            }


        }
        
    }

}

add_action( 'admin_notices', 'dyme_notices' );
function dyme_notices(){

    $type       = '';
    $message    = '';

    if( isset($_GET['employee_status'] ) ){
        $status = sanitize_text_field( $_GET['employee_status'] );
        if( $status == 'inserted' ){
            $message = 'employee added successfully';
            $type = 'success';
        }elseif( $status == 'inserted_error' ){
            $message = 'error in employee insert';
            $type = 'error';
        }elseif( $status == 'edited' ){
            $message = 'employee edited';
            $type = 'success';
        }elseif( $status == 'edited_error' ){
            $message = 'error in employee edit';
            $type = 'error';
        }elseif( $status == 'deleted_error' ){
            $message = 'error in employee delete';
            $type = 'error';
        }elseif( $status == 'deleted' ){
            $message = 'employee deleted';
            $type = 'success';
        }elseif( $status == 'bulk_deleted' ){
            $message = $_GET['deleted_count'] . ' کارمند حذف شدند';
            $type = 'success';
        }
    }

    if( $type && $message ){
        ?>
        <div class="notice notice-<?php echo $type;?> is-dismissible">
            <p><?php echo $message;?></p>
        </div>
        <?php
    }

}