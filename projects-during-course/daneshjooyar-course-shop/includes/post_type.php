<?php

defined('ABSPATH') || exit;

// Register Custom Post Type
function dcs_register_course_post_type() {

	$labels = array(
		'name'                  => _x( 'Courses', 'Post Type General Name', 'daneshjooyar-course-shop' ),
		'singular_name'         => _x( 'Course', 'Post Type Singular Name', 'daneshjooyar-course-shop' ),
		'menu_name'             => __( 'Courses', 'daneshjooyar-course-shop' ),
		'name_admin_bar'        => __( 'Course', 'daneshjooyar-course-shop' ),
		'archives'              => __( 'Course Archives', 'daneshjooyar-course-shop' ),
		'attributes'            => __( 'Course Attributes', 'daneshjooyar-course-shop' ),
		'parent_item_colon'     => __( 'Parent Course:', 'daneshjooyar-course-shop' ),
		'all_items'             => __( 'All Courses', 'daneshjooyar-course-shop' ),
		'add_new_item'          => __( 'Add New Course', 'daneshjooyar-course-shop' ),
		'add_new'               => __( 'Add Course', 'daneshjooyar-course-shop' ),
		'new_item'              => __( 'New Course', 'daneshjooyar-course-shop' ),
		'edit_item'             => __( 'Edit Course', 'daneshjooyar-course-shop' ),
		'update_item'           => __( 'Update Course', 'daneshjooyar-course-shop' ),
		'view_item'             => __( 'View Course', 'daneshjooyar-course-shop' ),
		'view_items'            => __( 'View Courses', 'daneshjooyar-course-shop' ),
		'search_items'          => __( 'Search Course', 'daneshjooyar-course-shop' ),
		'not_found'             => __( 'Not found', 'daneshjooyar-course-shop' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'daneshjooyar-course-shop' ),
		'featured_image'        => __( 'Featured Image', 'daneshjooyar-course-shop' ),
		'set_featured_image'    => __( 'Set featured image', 'daneshjooyar-course-shop' ),
		'remove_featured_image' => __( 'Remove featured image', 'daneshjooyar-course-shop' ),
		'use_featured_image'    => __( 'Use as featured image', 'daneshjooyar-course-shop' ),
		'insert_into_item'      => __( 'Insert into Course', 'daneshjooyar-course-shop' ),
		'uploaded_to_this_item' => __( 'Uploaded to this course', 'daneshjooyar-course-shop' ),
		'items_list'            => __( 'Course list', 'daneshjooyar-course-shop' ),
		'items_list_navigation' => __( 'Courses list navigation', 'daneshjooyar-course-shop' ),
		'filter_items_list'     => __( 'Filter Courses list', 'daneshjooyar-course-shop' ),
	);

	$args = array(
		'label'                 => __( 'Course', 'daneshjooyar-course-shop' ),
		'description'           => __( 'Courses of this site', 'daneshjooyar-course-shop' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'comments' ),
		'taxonomies'            => array( 'course_tag', ' course_cat' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-format-video',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'show_in_rest'          => true,
		'rest_base'             => 'course',
	);
	register_post_type( 'course', $args );


	$args = array(
		'label'                 => __( 'Playlist Item', 'daneshjooyar-course-shop' ),
		'description'           => __( 'Manage playlist items', 'daneshjooyar-course-shop' ),
		'supports'              => array( 'title', 'editor' ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => false,
		'show_in_menu'          => false,
		'menu_position'         => 5,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'playlist_item', $args );
	register_post_status( 'free' );
	register_post_status( 'premium' );

	$labels = array(
		'name'                  => _x( 'Course Registers', 'Post Type General Name', 'daneshjooyar-course-shop' ),
		'singular_name'         => _x( 'Course Register', 'Post Type Singular Name', 'daneshjooyar-course-shop' ),
		'menu_name'             => __( 'course Register', 'daneshjooyar-course-shop' ),
		//'name_admin_bar'        => __( 'Post Type', 'daneshjooyar-course-shop' ),
		'archives'              => __( 'Item Archives', 'daneshjooyar-course-shop' ),
		'attributes'            => __( 'Item Attributes', 'daneshjooyar-course-shop' ),
		'parent_item_colon'     => __( 'Parent Item:', 'daneshjooyar-course-shop' ),
		'all_items'             => __( 'Course reigster List', 'daneshjooyar-course-shop' ),
		'add_new_item'          => __( 'Add New Item', 'daneshjooyar-course-shop' ),
		'add_new'               => __( 'Add New', 'daneshjooyar-course-shop' ),
		'new_item'              => __( 'New Item', 'daneshjooyar-course-shop' ),
		'edit_item'             => __( 'Edit course register', 'daneshjooyar-course-shop' ),
		'update_item'           => __( 'Update Item', 'daneshjooyar-course-shop' ),
		'view_item'             => __( 'View Item', 'daneshjooyar-course-shop' ),
		'view_items'            => __( 'View Items', 'daneshjooyar-course-shop' ),
		'search_items'          => __( 'Search Item', 'daneshjooyar-course-shop' ),
		'not_found'             => __( 'Not found', 'daneshjooyar-course-shop' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'daneshjooyar-course-shop' ),
		'featured_image'        => __( 'Featured Image', 'daneshjooyar-course-shop' ),
		'set_featured_image'    => __( 'Set featured image', 'daneshjooyar-course-shop' ),
		'remove_featured_image' => __( 'Remove featured image', 'daneshjooyar-course-shop' ),
		'use_featured_image'    => __( 'Use as featured image', 'daneshjooyar-course-shop' ),
		'insert_into_item'      => __( 'Insert into item', 'daneshjooyar-course-shop' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'daneshjooyar-course-shop' ),
		'items_list'            => __( 'Items list', 'daneshjooyar-course-shop' ),
		'items_list_navigation' => __( 'Items list navigation', 'daneshjooyar-course-shop' ),
		'filter_items_list'     => __( 'Filter items list', 'daneshjooyar-course-shop' ),
	);
	$args = array(
		'label'                 => __( 'Course Register', 'daneshjooyar-course-shop' ),
		'description'           => __( 'This post type manage course Registers', 'daneshjooyar-course-shop' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'comments', 'author' ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => 'edit.php?post_type=course',
		'menu_position'         => 5,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'rewrite'               => false,
		'capability_type'       => 'page',
		'show_in_rest'          => false,
	);
	register_post_type( 'course_register', $args );

	register_post_status( 'failed',[
		'label'						=> _x( 'Failed', 'course_regsiter', 'daneshjooyar-course-shop' ),
		'show_in_admin_all_list'	=> true,
		'show_in_admin_status_list' => true,
		'public'					=> true,
		'label_count'				=> _n_noop( 'Failed <span class="count">(%s)</span>', 'Failed <span class="count">(%s)</span>', 'daneshjooyar-course-shop' ),
	] );


}
add_action( 'init', 'dcs_register_course_post_type', 0 );

add_filter( 'manage_course_posts_columns', 'dcs_course_table_cols' );
function dcs_course_table_cols( $columns ){
	$columns['price'] 		= __( 'Price', 'daneshjooyar-course-shop' );
	$columns['students'] 	= __( 'Studnets', 'daneshjooyar-course-shop' );
	$columns['sales'] 		= __( 'Sales', 'daneshjooyar-course-shop' );
	return $columns;
}

add_action( 'manage_course_posts_custom_column', 'dcs_course_table_cols_data', 10, 2 );
function dcs_course_table_cols_data( $column, $post_id ){
	if( $column == 'price' ){
		$price          = get_post_meta( $post_id, '_dcs_price', true );
		$final_price    = dcs_get_course_final_price( $post_id );
		$percent        = dcs_get_final_discount_percent( $post_id );
		if( $percent ){
			printf( '<del>%s</del>', number_format( $price / 10 ));
		}
		printf( '<ins>%s</ins> %s', number_format( $final_price / 10 ), __( 'Tooman', 'daneshjooyar-course-shop' ) );
		if( $percent ){
			printf( '<span class="course-discount-percent">%d%%</span>', $percent );
		}
	}elseif( $column == 'students' ){
		printf( '<a href="%s">%d</a>', admin_url( 'edit.php?post_type=course_register&post_parent=' . $post_id ), dcs_get_student_count( $post_id ) );
	}elseif( $column == 'sales' ){
		echo number_format( dcs_get_course_sales( $post_id ) / 10 ) . ' ' . __( 'Tooman', 'daneshjooyar-course-shop' ) ;
	}
}

add_filter( 'manage_course_register_posts_columns', 'dcs_course_register_table_cols', 1 );
function dcs_course_register_table_cols( $columns ){
	
	$columns['author'] 			= __( 'Student', 'daneshjooyar-course-shop' );
	$columns['status'] 			= __( 'Status', 'daneshjooyar-course-shop' );
	$columns['price'] 			= __( 'Price', 'daneshjooyar-course-shop' );
	$columns['title']			= __( 'ID', 'daneshjooyar-course-shop' );
	$new_columns 				= [];
	foreach( $columns as $col => $label ){
		$new_columns[$col] = $label;
		if( $col == 'title' ){
			$new_columns['course_title'] = __( 'Course Title', 'daneshjooyar-course-shop' );
		}
	}
	return $new_columns;
}

add_action( 'manage_course_register_posts_custom_column', 'dcs_course_register_table_cols_data', 10, 2 );
function dcs_course_register_table_cols_data( $column, $post_id ){
	$course_register = get_post( $post_id );
	
	if( $column == 'price' ){
		$price          = get_post_meta( $post_id, '_price', true );
		$final_price    = $course_register->menu_order;
		$percent        = get_post_meta( $post_id, '_discount_percent', true );
		if( $percent ){
			printf( '<del>%s</del>', number_format( $price / 10 ));
		}
		printf( '<ins>%s</ins> %s', number_format( $final_price / 10 ), __( 'Tooman', 'daneshjooyar-course-shop' ) );
		if( $percent ){
			printf( '<span class="course-discount-percent">%d%%</span>', $percent );
		}
	}elseif( $column == 'status' ){
		$statues = [
			'publish'	=> __( 'Complete', 'daneshjooyar-course-shop' ),
			'pending'	=> __( 'pending', 'daneshjooyar-course-shop' ),
			'failed'	=> __( 'Failed', 'daneshjooyar-course-shop' ),
			'trash'		=> __( 'Deleted', 'daneshjooyar-course-shop' ),
			'refund'	=> __( 'Refund', 'daneshjooyar-course-shop' )
		];
		printf(
			'<a href="%s" class="dcs-badge dcs-badge-%s">%s</a>',
			admin_url( 'edit.php?post_status=' . $course_register->post_status . '&post_type=course_register' ),
			$course_register->post_status,
			$statues[$course_register->post_status]
		);
	}elseif( $column == 'course_title' ){
		echo get_the_title( $course_register->post_parent );
	}
}

add_filter( 'manage_edit-course_register_sortable_columns', 'dcs_register_cousre_sortable' );
function dcs_register_cousre_sortable( $sortable ){
	$sortable['price'] = 'menu_order';
	return $sortable;
}

add_action( 'restrict_manage_posts', 'dcs_register_course_filter_table' );
function dcs_register_course_filter_table(){
	include DANESHJOOYAR_COURSE_SHOP_VIEW_ADMIN . 'filter.php';
}