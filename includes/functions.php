<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Function to display the nested features for the current post.
 */
function display_post_features() {
	$taxonomy = 'cabin-feature'; // Updated taxonomy name
	$terms    = get_the_terms( get_the_ID(), $taxonomy );

	if ( $terms && ! is_wp_error( $terms ) ) {
		$output            = '<div class="cabin-feature">'; // Updated class name
		$displayed_parents = array();
		foreach ( $terms as $term ) {
			$ancestors = get_ancestors( $term->term_id, $taxonomy );
			$parent_id = ( ! empty( $ancestors ) ) ? end( $ancestors ) : 0;

			if ( $parent_id == 0 && ! in_array( $term->term_id, $displayed_parents ) ) {
				// Display parent term
				$parent_term = get_term( $term->term_id, $taxonomy );
				if ( $parent_term && ! is_wp_error( $parent_term ) ) {
					$parent_slug = esc_attr( $parent_term->slug );
					$term_link   = get_term_link( $parent_term, $taxonomy );

					$output .= '<ul class="' . $parent_slug . '" for="' . $parent_slug . '"><h2>';
					if ( ! is_wp_error( $term_link ) ) {
						$output .= '<span href="' . esc_url( $term_link ) . '">' . esc_html( $parent_term->name ) . '</span>';
					} else {
						$output .= esc_html( $parent_term->name );
					}
					$output .= '</h2>';

					// Display child terms of this parent that are also associated with the current post
					foreach ( $terms as $child_term ) {
						if ( in_array( $parent_term->term_id, get_ancestors( $child_term->term_id, $taxonomy ) ) ) {
							$child_term_link = get_term_link( $child_term, $taxonomy );
							$output          .= '<li>';
							if ( ! is_wp_error( $child_term_link ) ) {
								$output .= '<a href="' . esc_url( $child_term_link ) . '">' . esc_html( $child_term->name ) . '</a>';
							} else {
								$output .= esc_html( $child_term->name );
							}
							$output .= '</li>';
						}
					}
					$output .= '</ul>';
					$displayed_parents[] = $term->term_id;
				}
			} elseif ( ! empty( $ancestors ) && ! in_array( end( $ancestors ), $displayed_parents ) ) {
				// Display parent term for a child term
				$parent_id   = end( $ancestors );
				$parent_term = get_term( $parent_id, $taxonomy );
				if ( $parent_term && ! is_wp_error( $parent_term ) ) {
					$parent_slug = esc_attr( $parent_term->slug );
					$term_link   = get_term_link( $parent_term, $taxonomy );

					$output .= '<h2 class="' . $parent_slug . '" for="' . $parent_slug . '">';
					if ( ! is_wp_error( $term_link ) ) {
						$output .= '<span href="' . esc_url( $term_link ) . '">' . esc_html( $parent_term->name ) . '</span>';
					} else {
						$output .= esc_html( $parent_term->name );
					}
					$output .= '</h2><ul class="' . $parent_slug . '" id="' . $parent_slug . '">';

					// Display child terms of this parent that are also associated with the current post
					foreach ( $terms as $child_term ) {
						if ( in_array( $parent_term->term_id, get_ancestors( $child_term->term_id, $taxonomy ) ) ) {
							$child_term_link = get_term_link( $child_term, $taxonomy );
							$output          .= '<li>';
							if ( ! is_wp_error( $child_term_link ) ) {
								$output .= '<a href="' . esc_url( $child_term_link ) . '">' . esc_html( $child_term->name ) . '</a>';
							} else {
								$output .= esc_html( $child_term->name );
							}
							$output .= '</li>';
						}
					}
					$output .= '</ul>';
					$displayed_parents[] = $parent_id;
				}
			}
		}
		$output .= '</div>';
		return $output;
	}
	return '';
}

/**
 * Function to directly output the post features in a template. You can directly call the function the_post_features() within your theme's template file
 */
function the_post_features() {
	echo display_post_features();
}