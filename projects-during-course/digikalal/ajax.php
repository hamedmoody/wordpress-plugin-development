<?php
add_action( 'wp_ajax_nopriv_wp_dk_get_product', 'wp_dk_get_product' );
add_action( 'wp_ajax_wp_dk_get_product', 'wp_dk_get_product' );
function wp_dk_get_product() {
	$result = [
		'success' => false,
		'message' => 'خطای رخ داده است'
	];
	if ( ! isset( $_GET['id'] ) ) {
		$result['message'] = 'شناسه محصول ارسال نشده است';
		wp_send_json( $result, 400 );
	}
	$product_id = absint( $_GET['id'] );
	$html       = wp_dk_product_render( $product_id );
	wp_send_json( [
		'success' => 1,
		'html'    => $html
	], 200 );
}
