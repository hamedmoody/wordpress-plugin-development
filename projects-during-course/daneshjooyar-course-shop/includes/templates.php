<?php
defined('ABSPATH') || exit;

add_filter( 'template_include', 'dcs_template_management' );
function dcs_template_management( $template ){
    if( is_singular( 'course' ) ){
        $tmp = locate_template( 'dcs/single-course.php' );
        if( ! $tmp ){
            $template = DANESHJOOYAR_COURSE_SHOP_TEMPLATES . 'single-course.php';
        }else{
            $template = $tmp;
        }
    }elseif( is_post_type_archive( 'course' ) || is_tax( 'course_cat' ) || is_tax( 'course_tag' ) ){
        $tmp = locate_template( 'dcs/archive-course.php' );
        if( ! $tmp ){
            $template = DANESHJOOYAR_COURSE_SHOP_TEMPLATES . 'archive-course.php';
        }else{
            $template = $tmp;
        }
    }
    return $template;
}

add_filter( 'body_class', 'dcs_body_class' );
function dcs_body_class( $classes ){
    if(
        is_singular('course') || is_post_type_archive('course')  || is_tax( ['course_cat', 'course_tag'] )
    ){
        $theme_class = str_replace( ' ', '-', strtolower( wp_get_theme()->get('Name') ) );
        if( ! in_array( $theme_class, $classes ) ){
            $classes[] = $theme_class;
        }
    }
    return $classes;
}