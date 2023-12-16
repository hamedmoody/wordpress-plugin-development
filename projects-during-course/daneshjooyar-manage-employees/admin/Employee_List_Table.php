<?php

if( ! class_exists( 'WP_List_Table' ) ){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Employee_List_Table extends WP_List_Table{

    public function get_columns(){
        return [
            'cb'            => '<input type="checkbox"/>',
            'ID'            => __('User id', 'daneshjooyar-manage-employees'),
            'first_name'    => __('Name ', 'daneshjooyar-manage-employees'),
            'last_name'     => __('Family', 'daneshjooyar-manage-employees'),
            'birthdate'     => __('Birthdate', 'daneshjooyar-manage-employees'),
            'avatar'        => __('Avatar', 'daneshjooyar-manage-employees'),
            'weight'        => __('Weight', 'daneshjooyar-manage-employees'),
            'mission'       => __('Mission', 'daneshjooyar-manage-employees'),
            'date'          => __('Date', 'daneshjooyar-manage-employees')
        ];
    }

    public function column_default( $item, $column_name ){
        if( isset( $item[$column_name] ) ){
            return wp_kses( $item[$column_name], [
                'span'  => []
            ] );
        }
        return '-';
    }

    public function column_cb( $item ){
        return '<input type="checkbox" name="employee[]" value="' . $item['ID'] . '"/>';
    }

    public function column_first_name( $item ){

        $csrf       = wp_create_nonce( 'delete_employee' . $item['ID'] );

        $actions    = [
            'edit'      => '<a href="' . admin_url( 'admin.php?page=dyme_employees_create&employee_id=' . $item['ID'] ) . '">Edit</a>',
            //'delete'    => '<a href="' . admin_url( '/admin.php?page=dyme_employees&action=delete_employee&id=' . $item['ID'] ) . '&csrf=' . $csrf . '" onclick="return confirm( \'Sure?\' );">Delete</a>',
        ];

        if( current_user_can( 'manage_options' ) ){
            $actions['delete'] = '<a href="' . admin_url( '/admin.php?page=dyme_employees&action=delete_employee&id=' . $item['ID'] ) . '&csrf=' . $csrf . '" onclick="return confirm( \'Sure?\' );">Delete</a>';
        }

        return $item['first_name'] . $this->row_actions( $actions );
        
    }

    public function column_avatar( $item ){
        if( $item['avatar'] ){
            return sprintf( "<img src='%s' width='24' height='24'>", $item['avatar'] );
        }
    }

    public function column_date( $item ){
        return date_i18n( 'Y-m-d', strtotime( $item['created_at'] ) );
    }

    public function no_items(){
        echo 'No employee found';
    }

    public function get_bulk_actions(){
        $action = [
            //'delete'        => 'Delete',
            'send_message'  => 'Send Message'
        ];
        if( current_user_can( 'manage_options' ) ){
            $action['delete'] = 'Delete';
        }
        return $action;
    }

    private function create_view( $key, $label, $url, $count = 0 ){
        $current_status = isset( $_GET['employee_status'] ) ? $_GET['employee_status'] : 'all';
        $view_tag       = sprintf( '<a href="%s" %s>%s</a>', $url, $current_status == $key ? 'class="current"' : '', $label );
        if( $count ){
            $view_tag.= sprintf( '<span class="count">(%d)</span>', $count );
        }
        return $view_tag;
    }

    protected function get_views(){
        global $wpdb;

        $where = '';
        if( isset( $_GET['s'] ) ){
            $where = $wpdb->prepare( " AND last_name LIKE %s", '%' . $wpdb->esc_like( $_GET['s'] ) .'%' );
        }

        $all        = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->dyme_employees} WHERE 1 = 1 $where" );
        $has_photo  = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->dyme_employees} WHERE avatar != '' $where " );
        $no_photo   = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->dyme_employees} WHERE avatar = '' $where " );

        return [
            'all'           => $this->create_view( 'all', 'All', admin_url( 'admin.php?page=dyme_employees&employee_status=all' ), $all ),
            'has_photo'     => $this->create_view( 'has_photo', 'Has avatar', admin_url( 'admin.php?page=dyme_employees&employee_status=has_photo' ), $has_photo ),
            'no_photo'      => $this->create_view( 'no_photo', 'No avatar', admin_url( 'admin.php?page=dyme_employees&employee_status=no_photo' ), $no_photo ),
        ];
    }

    public function get_sortable_columns(){
        return [
            'weight'        => ['weight', 'desc' ],
            'mission'       => ['mission', 'desc' ],
            'date'          =>  ['date', 'asc']
        ];
    }

    public function process_bulk_action(){

        //Check security
        
        if( $this->current_action() == 'delete' && current_user_can( 'manage_options' ) ){
            $employees      = $_GET['employee'];
            $record_count   = count( $employees );
            global $wpdb;
            foreach( $employees as $employee_id ){
                $wpdb->delete(
                    $wpdb->dyme_employees,
                    [
                        'ID'    => $employee_id,
                    ]
                );
            }
            wp_redirect(
                admin_url( 'admin.php?page=dyme_employees&employee_status=bulk_deleted&deleted_count=' . $record_count )
            );
            exit;
        }

    }

    public function get_hidden_columns(){
        return get_hidden_columns( get_current_screen() );
    }

    public function prepare_items(){

        global $wpdb;

        $this->process_bulk_action();

        $per_page       = 2;
        $current_page   = $this->get_pagenum();
        $offset         = ( $current_page - 1 ) * $per_page;

        $orderby        = isset( $_GET['orderby'] ) ? $_GET['orderby'] : false;
        $order          = isset( $_GET['order'] ) ? $_GET['order'] : false;
        $orderClause    = "ORDER BY created_at DESC";

        if( $orderby == 'date' ){
            $orderby = 'created_at';
        }

        if( $order && $orderby ){
            $orderClause = "ORDER BY $orderby $order";
        }

        $where = ' WHERE 1 = 1 ';
        if( isset( $_GET['employee_status'] ) && $_GET['employee_status'] != 'all' ){
            if( $_GET['employee_status'] == 'has_photo' ){
                $where.= " AND avatar != '' ";
            }elseif( $_GET['employee_status'] == 'no_photo' ){
                $where.= " AND avatar = '' ";
            }
        }

        if( isset( $_GET['s'] ) ){
            $where.= $wpdb->prepare( " AND last_name LIKE %s", '%' . $wpdb->esc_like( $_GET['s'] ) .'%' );
        }

        $results        = $wpdb->get_results(
            "SELECT SQL_CALC_FOUND_ROWS * FROM {$wpdb->dyme_employees} $where $orderClause LIMIT $per_page OFFSET $offset"
            , ARRAY_A
        );

        $this->_column_headers  = array( $this->get_columns(), $this->get_hidden_columns(), $this->get_sortable_columns(), 'name' );

        $this->set_pagination_args([
            'total_items'   => $wpdb->get_var( "SELECT FOUND_ROWS()" ),
            'per_page'      => $per_page,
        ]);

        $this->items            = $results;

    }

}