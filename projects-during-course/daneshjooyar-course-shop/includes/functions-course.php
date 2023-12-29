<?php
defined('ABSPATH') || exit;

function dcs_get_course_final_price( $course_id ){

    $price          = get_post_meta( $course_id, '_dcs_price', true );
    $sale_price     = get_post_meta( $course_id, '_dcs_sale_price', true );
    $expire         = get_post_meta( $course_id, '_dcs_expire', true );
    $has_discount   = get_post_meta( $course_id, '_dcs_has_discount', true );

    $final_price    = $price;
    if(
        $has_discount
        &&
        $sale_price
        &&
        $sale_price < $price
    ){
        
        if( $expire ){
            if( strtotime( $expire ) > current_time('timestamp') ){
                $final_price = $sale_price;
            }
        }else{
            $final_price = $sale_price;
        }

    }

    return $final_price;

}

function dcs_get_course_price( $course_id ){
    return get_post_meta( $course_id, '_dcs_price', true );
}

function dcs_get_final_discount_percent( $course_id ){
    return 100 - round( dcs_get_course_final_price( $course_id ) / dcs_get_course_price( $course_id ) * 100 );
}