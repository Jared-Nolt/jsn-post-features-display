<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Function to display Features from the 'cabin_features' ACF repeater field as list items.
 */
function display_cabin_features() {
	if ( is_product() ) { // only runs on products
		$output = '';

		if( have_rows('cabin_features') ):
			$output .= '<div class="cabin-features">'; // Optional container div
			while( have_rows('cabin_features') ) : the_row();

				// Get parent value.
					$parent_title = get_sub_field('feature_title');
					$feature_section_style = get_sub_field('feature_section_style'); // Full width class
					$classes = ['cabin-feature-group']; // Start with basic classes

					if (!empty($feature_section_style)) { // Conditionally add classes if the fields return values
						$classes[] = sanitize_html_class($feature_section_style); // Always sanitize user-generated class names
					}
				$output .= '<div class="' . implode(' ', $classes) . '">';

				if ($parent_title) {
					$output .= '<h3 class="cabin-feature-title">' . esc_html($parent_title) . '</h3>';
				}

				// Loop over sub repeater rows.
				if( have_rows('feature_items') ):
					$output .= '<ul>';
					while( have_rows('feature_items') ) : the_row();

						// Get sub value.
						$child_title = get_sub_field('feature');

						if ($child_title) {
							$output .= '<li class="cabin-feature-item">' . esc_html($child_title) . '</li>';
						}

					endwhile;
					$output .= '</ul>' . '</div>';
				endif;

			endwhile;
			$output .= '</div>'; // Close optional container div
		endif;

		return $output;
	}
	return ''; // Return empty string if not a product page
}

/**
 * Shortcode to display the post features as list items. [cabin_features]
 */
function cabin_features_shortcode() {
	if ( is_product() ) {
		return display_cabin_features();
	}
	return ''; // Return empty string if not a product page
}
add_shortcode( 'cabin_features', 'cabin_features_shortcode' );