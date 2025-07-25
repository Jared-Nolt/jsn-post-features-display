<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Function to display FAQs from the 'faqs' ACF repeater field as accordions.
 */
function display_post_faqs() {
	if ( is_product() ) { // only runs on products
		$faqs = get_field( 'faqs' );
		$post_title = get_the_title();

		if ( $faqs ) {
			$output = '<div class="post-faqs accordion-container">';
			$output .= '<h2 class="accordion-title">' . esc_html( $post_title ) . ' FAQ\'s</h2>'; // Customize heading if needed
			$output .= '<dl class="accordion elementor-widget-n-accordion">';
			$accordion_id = 0;
			foreach ( $faqs as $faq ) {
				$question = isset( $faq['faq_question'] ) ? $faq['faq_question'] : '';
				$answer   = isset( $faq['faq_answer'] ) ? $faq['faq_answer'] : '';
				$unique_id = 'faq-' . $accordion_id++;

				if ( $question && $answer ) {
					$output .= '<dt>';
					$output .= '<button for="' . $unique_id . '-content" class="accordion-toggle e-n-accordion-item-title" aria-expanded="false" aria-controls="' . $unique_id . '-content">';
					$output .= esc_html( $question );
					$output .= '<span class="e-n-accordion-item-title-icon">
<span class="e-opened"><svg aria-hidden="true" class="e-font-icon-svg e-fas-minus" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M416 208H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg></span>
<span class="e-closed"><svg aria-hidden="true" class="e-font-icon-svg e-fas-plus" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg></span>
</span></button>';
					$output .= '</dt>';
					$output .= '<dd id="' . $unique_id . '-content" class="accordion-content" aria-hidden="true"><div>';
					$output .= wp_kses_post( wpautop( $answer ) );
					$output .= '</div></dd>';
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
									// Use setTimeout to ensure the transition plays out for closing
									otherToggle.parentNode.nextElementSibling.style.maxHeight = "0";
									otherToggle.parentNode.nextElementSibling.setAttribute("aria-hidden", true);
								}
							});

							// Toggle the current accordion
							this.setAttribute("aria-expanded", !expanded);
							if (!expanded) {
								content.style.maxHeight = content.scrollHeight + "px"; // Set max-height to scrollHeight for smooth opening
								content.setAttribute("aria-hidden", false);
							} else {
								content.style.maxHeight = "0"; // Set max-height to 0 for smooth closing
								content.setAttribute("aria-hidden", true);
							}
						});
					});
				});
			</script>';

			// Basic CSS for initial closed state and styling (you might want to move this to your theme's stylesheet)
			$output .= '<style type="text/css">
				.accordion-content {
					max-height: 0; /* Changed from height: 0; */
					overflow: hidden; /* Hide overflowing content */
					transition: max-height 0.5s ease-in-out; /* Add transition for max-height */
					margin: 0;
				}
				.accordion-content div{
					padding: 1em 2em;
				}
				.accordion-content[aria-hidden="false"] {
					/* max-height will be set by JavaScript when opening */
					visibility: visible;
				}
				/* Ensure the content is hidden when aria-hidden is true, and visible when false */
				.accordion-content[aria-hidden="true"] {
					visibility: hidden;
				}
				.accordion-content[aria-hidden="false"] {
					visibility: visible;
				}
				.post-faqs .e-n-accordion-item-title-icon > span{
					visibility: hidden;
					display: none;
				}
				.post-faqs .accordion-toggle[aria-expanded="true"] span.e-opened {
					visibility: visible;
					display: block;
				}
				.post-faqs .accordion-toggle[aria-expanded="false"] span.e-closed {
					visibility: visible;
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