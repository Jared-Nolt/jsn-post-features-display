<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Shortcode to display the post features. [post_features]
 */
function post_features_shortcode() {
	return display_post_features();
}
add_shortcode( 'post_features', 'post_features_shortcode' );