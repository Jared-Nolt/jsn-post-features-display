<?php

/**
 * Plugin Name: Post Features Display
 * Plugin URI: https://github.com/Jared-Nolt
 * Description: Displays the nested categories associated with the current post.
 * Version: 1.0.0
 * Author: Jared Nolt
 * Author URI: https://github.com/Jared-Nolt
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Include the main functions file
require_once plugin_dir_path( __FILE__ ) . 'includes/functions.php';

// Include the shortcodes file
require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes.php';

// Include the FAQ display file
require_once plugin_dir_path( __FILE__ ) . 'includes/faq-display.php';