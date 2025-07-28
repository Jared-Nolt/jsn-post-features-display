<?php

/**
 * Plugin Name: JSN Product Features Display
 * Plugin URI: https://github.com/Jared-Nolt/jsn-post-features-display
 * Description: Displays nested ACF Repeater fields associated with the current product.
 * Version: 2.5.2
 * Author: Jared Nolt
 * Author URI: https://github.com/Jared-Nolt
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Include the FAQ display file
require_once plugin_dir_path( __FILE__ ) . 'includes/faq-display.php';

// Include the post features file
require_once plugin_dir_path( __FILE__ ) . 'includes/post-features.php';