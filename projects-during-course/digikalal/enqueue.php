<?php
defined( 'ABSPATH' ) || exit;

function dk_assets_register() {
	global $post;
	if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'digikala' ) ) {
		wp_enqueue_style( 'dk-style', PLUGIN_DIGIKALA_CSS_URL . 'style.css', '', '1.0.0', '' );
		wp_enqueue_script( 'dk-script', PLUGIN_DIGIKALA_JS_URL . 'script.js', [ 'jquery' ], '1.0.0', true );
		wp_localize_script( 'dk-script', '_dk', [
			'ajax_url' => admin_url( 'admin-ajax.php' )
		] );
	}
}

add_action( 'wp_enqueue_scripts', 'dk_assets_register' );