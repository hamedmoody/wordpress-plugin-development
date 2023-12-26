<?php
defined( 'ABSPATH' ) || exit;


add_shortcode( 'digikala', 'wp_dk_render' );
function wp_dk_render( $atts = [], $content = null ) {

	$atts = shortcode_atts( [
		'id' => 0
	], $atts );
	if ( ! $atts['id'] ) {
		return;
	}
	$product_id = $atts['id'];

	return sprintf(
		'<img src="%sPreloader.png" class="dk-preloader" data-dk="%d" alt="Preloader" loading="lazy" width="1032" height="360"/> '
		, PLUGIN_DIGIKALA_IMAGES_URL, $product_id
	);

}