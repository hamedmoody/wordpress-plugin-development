<?php
function wp_dk_product_render( $product_id ) {
	$html   = '';
	$result = wp_remote_get( sprintf( 'https://api.digikala.com/v1/product/%d/', $product_id ) );

	if ( is_wp_error( $result ) || wp_remote_retrieve_response_code( $result ) != 200 ) {
		return $html;
	}
	$data    = json_decode( wp_remote_retrieve_body( $result ) );
	$product = $data->data->product;

	ob_start();
	require_once PLUGIN_DIGIKALA_VIEW_PATH . 'product-view.php';
	return ob_get_clean();

}