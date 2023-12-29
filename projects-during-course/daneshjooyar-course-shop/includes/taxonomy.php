<?php
defined('ABSPATH') || exit;

// Register Custom Taxonomy
function dcs_taxonomies() {

	$labels = array(
		'name'                       => _x( 'Course Categories', 'Taxonomy General Name', 'daneshjooyar-course-shop' ),
		'singular_name'              => _x( 'Course Category', 'Taxonomy Singular Name', 'daneshjooyar-course-shop' ),
		'menu_name'                  => __( 'Course Category', 'daneshjooyar-course-shop' ),
		'all_items'                  => __( 'All Course Categories', 'daneshjooyar-course-shop' ),
		'parent_item'                => __( 'Parent Course Category', 'daneshjooyar-course-shop' ),
		'parent_item_colon'          => __( 'Parent Course Category:', 'daneshjooyar-course-shop' ),
		'new_item_name'              => __( 'New Course Category Name', 'daneshjooyar-course-shop' ),
		'add_new_item'               => __( 'Add New Course Category', 'daneshjooyar-course-shop' ),
		'edit_item'                  => __( 'Edit Course Category', 'daneshjooyar-course-shop' ),
		'update_item'                => __( 'Update Course Category', 'daneshjooyar-course-shop' ),
		'view_item'                  => __( 'View Course Category', 'daneshjooyar-course-shop' ),
		'separate_items_with_commas' => __( 'Separate Course Categories with commas', 'daneshjooyar-course-shop' ),
		'add_or_remove_items'        => __( 'Add or remove Course Categories', 'daneshjooyar-course-shop' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'daneshjooyar-course-shop' ),
		'popular_items'              => __( 'Popular Course Categories', 'daneshjooyar-course-shop' ),
		'search_items'               => __( 'Search Course Categories', 'daneshjooyar-course-shop' ),
		'not_found'                  => __( 'Not Found', 'daneshjooyar-course-shop' ),
		'no_terms'                   => __( 'No items', 'daneshjooyar-course-shop' ),
		'items_list'                 => __( 'Course Categories list', 'daneshjooyar-course-shop' ),
		'items_list_navigation'      => __( 'Course Categories list navigation', 'daneshjooyar-course-shop' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest'               => false,
	);
	register_taxonomy( 'course_cat', array( 'course' ), $args );


    $labels = array(
		'name'                       => _x( 'Course Tags', 'Taxonomy General Name', 'daneshjooyar-course-shop' ),
		'singular_name'              => _x( 'Course Tag', 'Taxonomy Singular Name', 'daneshjooyar-course-shop' ),
		'menu_name'                  => __( 'Course Tag', 'daneshjooyar-course-shop' ),
		'all_items'                  => __( 'All Course Tags', 'daneshjooyar-course-shop' ),
		'parent_item'                => __( 'Parent Course Tag', 'daneshjooyar-course-shop' ),
		'parent_item_colon'          => __( 'Parent Course Tag:', 'daneshjooyar-course-shop' ),
		'new_item_name'              => __( 'New Course Tag Name', 'daneshjooyar-course-shop' ),
		'add_new_item'               => __( 'Add New Course Tag', 'daneshjooyar-course-shop' ),
		'edit_item'                  => __( 'Edit Course Tag', 'daneshjooyar-course-shop' ),
		'update_item'                => __( 'Update Course Tag', 'daneshjooyar-course-shop' ),
		'view_item'                  => __( 'View Course Tag', 'daneshjooyar-course-shop' ),
		'separate_items_with_commas' => __( 'Separate Course Tags with commas', 'daneshjooyar-course-shop' ),
		'add_or_remove_items'        => __( 'Add or remove Course Tag', 'daneshjooyar-course-shop' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'daneshjooyar-course-shop' ),
		'popular_items'              => __( 'Popular Course Tags', 'daneshjooyar-course-shop' ),
		'search_items'               => __( 'Search Course Tags', 'daneshjooyar-course-shop' ),
		'not_found'                  => __( 'Not Found', 'daneshjooyar-course-shop' ),
		'no_terms'                   => __( 'No items', 'daneshjooyar-course-shop' ),
		'items_list'                 => __( 'Course Tags list', 'daneshjooyar-course-shop' ),
		'items_list_navigation'      => __( 'Course Tags list navigation', 'daneshjooyar-course-shop' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest'               => false,
	);
	register_taxonomy( 'course_tag', array( 'course' ), $args );


}
add_action( 'init', 'dcs_taxonomies', 0 );
