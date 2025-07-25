<?php

/**
 * Plugin Name: JSN Post Features Display
 * Plugin URI: https://github.com/Jared-Nolt/jsn-post-features-display
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

// Include the FAQ display file
require_once plugin_dir_path( __FILE__ ) . 'includes/faq-display.php';

// Include the post features file
require_once plugin_dir_path( __FILE__ ) . 'includes/post-features.php';




/**
 * GitHub Plugin Updater
 *
 * This code enables automatic updates for your WordPress plugin from a public GitHub repository.
 */

add_filter('site_transient_update_plugins', 'my_plugin_check_for_updates');
function my_plugin_check_for_updates($transient) {
    if (empty($transient->checked)) {
        return $transient;
    }

    // --- CONFIGURATION ---
    $plugin_slug    = basename(dirname(__FILE__)); // Your plugin's folder name
    $plugin_basename = plugin_basename(__FILE__); // "your-folder/your-plugin-file.php"
    $github_user    = 'Jared-Nolt';      // Your GitHub username
    $github_repo    = 'jsn-post-features-display';  // Your GitHub repository name
    // ---------------------

    // Get the latest release information from GitHub
    $request_uri = sprintf('https://api.github.com/repos/%s/%s/releases/latest', $github_user, $github_repo);
    $response = wp_remote_get($request_uri);

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        return $transient;
    }

    $release_data = json_decode(wp_remote_retrieve_body($response));

    if (empty($release_data) || !isset($release_data->tag_name)) {
        return $transient;
    }

    // Compare the latest GitHub version with the current plugin version
    $current_version = $transient->checked[$plugin_basename];
    $github_version = ltrim($release_data->tag_name, 'v'); // Remove 'v' prefix if present

    if (version_compare($github_version, $current_version, '>')) {
        // A new version is available, prepare the update data
        $update_data = new stdClass();
        $update_data->slug = $plugin_slug;
        $update_data->plugin = $plugin_basename;
        $update_data->new_version = $github_version;
        $update_data->url = "https://github.com/{$github_user}/{$github_repo}";
        $update_data->package = $release_data->zipball_url; // The download URL
        $update_data->icons = [
            'default' => 'https://raw.githubusercontent.com/' . $github_user . '/' . $github_repo . '/master/assets/icon-128x128.png' // Optional: path to an icon
        ];

        $transient->response[$plugin_basename] = $update_data;
    }

    return $transient;
}

add_filter('plugins_api', 'my_plugin_api_call', 10, 3);
function my_plugin_api_call($res, $action, $args) {
    // --- CONFIGURATION ---
    $plugin_slug    = basename(dirname(__FILE__)); // Your plugin's folder name
    $github_user    = 'Jared-Nolt';      // Your GitHub username
    $github_repo    = 'jsn-post-features-display';  // Your GitHub repository name
    // ---------------------

    // Check if the request is for our plugin
    if (!isset($args->slug) || $args->slug != $plugin_slug) {
        return $res;
    }

    // Get the release information from GitHub
    $request_uri = sprintf('https://api.github.com/repos/%s/%s/releases/latest', $github_user, $github_repo);
    $response = wp_remote_get($request_uri);

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        return $res;
    }

    $release_data = json_decode(wp_remote_retrieve_body($response));

    if (empty($release_data)) {
        return $res;
    }
    
    // Populate the plugin information object
    $res = new stdClass();
    $res->name = 'Post Features Display'; // Your plugin's display name
    $res->slug = $plugin_slug;
    $res->version = ltrim($release_data->tag_name, 'v');
    $res->author = '<a href="https://github.com/' . $github_user . '">' . $github_user . '</a>'; // Your name/link
    $res->homepage = "https://github.com/{$github_user}/{$github_repo}";
    $res->download_link = $release_data->zipball_url;
    $res->last_updated = $release_data->published_at;

    // Add changelog/description from the GitHub release body
    require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
    $res->sections = [
        'description' => 'Displays the nested categories associated with the current post.',
        'changelog'   => class_exists('Parsedown') ? Parsedown::instance()->text($release_data->body) : strip_tags($release_data->body, '<p><ul><ol><li><strong><em>'),
    ];

    return $res;
}