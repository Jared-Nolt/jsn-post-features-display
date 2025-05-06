<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Function to display FAQs from the 'faqs' ACF repeater field as accordions.
 */
function display_post_faqs() {
	if ( is_product() ) {
		$faqs = get_field( 'faqs' );
		$post_title = get_the_title();

		if ( $faqs ) {
			$output = '<div class="post-faqs accordion-container">';
			$output .= '<h2>' . esc_html( $post_title ) . ' FAQ\'s</h2>'; // Customize heading if needed
			$output .= '<dl class="accordion">';
			$accordion_id = 0;
			foreach ( $faqs as $faq ) {
				$question = isset( $faq['faq_question'] ) ? $faq['faq_question'] : '';
				$answer   = isset( $faq['faq_answer'] ) ? $faq['faq_answer'] : '';
				$unique_id = 'faq-' . $accordion_id++;

				if ( $question && $answer ) {
					$output .= '<dt>';
					$output .= '<button for="' . $unique_id . '-content" class="accordion-toggle" aria-expanded="false" aria-controls="' . $unique_id . '-content">';
					$output .= esc_html( $question );
					$output .= '</button>';
					$output .= '</dt>';
					$output .= '<dd id="' . $unique_id . '-content" class="accordion-content" aria-hidden="true">';
					$output .= wp_kses_post( wpautop( $answer ) );
					$output .= '</dd>';
				}
			}
			$output .= '</dl>';
			$output .= '</div>';

			// Basic JavaScript for accordion functionality (CLOSE OTHERS)
			$output .= '<script type="text/javascript">
				document.addEventListener("DOMContentLoaded", function() {
					const accordionToggles = document.querySelectorAll(".accordion-toggle");

					accordionToggles.forEach(toggle => {
						toggle.addEventListener("click", function() {
							const content = this.parentNode.nextElementSibling; // Get the next sibling <dd>
							const expanded = this.getAttribute("aria-expanded") === "true" || false;

							// Close all other open accordions
							accordionToggles.forEach(otherToggle => {
								if (otherToggle !== this && otherToggle.getAttribute("aria-expanded") === "true") {
									otherToggle.setAttribute("aria-expanded", false);
									otherToggle.parentNode.nextElementSibling.setAttribute("aria-hidden", true);
								}
							});

							// Toggle the current accordion
							this.setAttribute("aria-expanded", !expanded);
							content.setAttribute("aria-hidden", expanded);
						});
					});
				});
			</script>';

			// Basic CSS for initial closed state and styling (you might want to move this to your theme's stylesheet)
			$output .= '<style type="text/css">
				.accordion-content {
					display: none; /* Initially hide all content */
				}
				.accordion-content[aria-hidden="false"] {
					display: block;
				}
				
			</style>';

			return $output;
		}
		return '';
	}
	return ''; // Return empty string if not a product page
}

/**
 * Shortcode to display the post FAQs as accordions. [post_faqs]
 */
function post_faqs_shortcode() {
	if ( is_product() ) {
		return display_post_faqs();
	}
	return ''; // Return empty string if not a product page
}
add_shortcode( 'post_faqs', 'post_faqs_shortcode' );

/**
 * Function to directly output the post FAQs as accordions in a template.
 */
function the_post_faqs() {
	if ( is_product() ) {
		echo display_post_faqs();
	}
}