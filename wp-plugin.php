<?php
/**
 * Plugin Name: WP Plugin
 * Plugin URI: https://github.com/wpcleveldotcom/wp-plugin
 * Description: A WordPress plugin boilerplate.
 * Author: WP Clevel
 * Author URI: https://wpclevel.com
 * Version: 1.0.0
 * Text Domain: wp-plugin
 * Tested up to: 5.7
 */

// Useful constants.
define('WP_PLUGIN_DIR', __DIR__ . '/');
define('WP_PLUGIN_URI', plugins_url('/', __FILE__));
define('WP_PLUGIN_VER', '1.0.0');

/**
 * Do activation
 *
 * @see https://developer.wordpress.org/reference/functions/register_activation_hook/
 */
function wp_plugin_activate($network)
{
    try {
        if (version_compare(PHP_VERSION, '7.2', '<')) {
            throw new Exception(__('This plugin requires PHP version 7.2 at least!', 'textdomain'));
        }

        if (version_compare($GLOBALS['wp_version'], '5.2', '<')) {
            throw new Exception(__('This plugin requires WordPress version 5.2 at least!', 'textdomain'));
        }

        if (!defined('WP_CONTENT_DIR') || !is_writable(WP_CONTENT_DIR)) {
            throw new Exception(__('WordPress content directory is inaccessible.', 'textdomain'));
        }
    } catch (Exception $e) {
        if (defined('DOING_AJAX') && DOING_AJAX) {
            header('Content-Type: application/json; charset=' . get_option('blog_charset'));
            status_header(500);
            exit(json_encode([
                'success' => false,
                'name'    => __('Plugin Activation Error', 'textdomain'),
                'message' => $e->getMessage()
            ]));
        } else {
            exit($e->getMessage());
        }
    }

    // Maybe add default settings.
    // add_option({$settings_key}, [
    //
    // ]);
}
register_activation_hook(__FILE__, 'wp_plugin_activate');

/**
 * Do installation
 *
 * @see https://developer.wordpress.org/reference/hooks/plugins_loaded/
 */
function wp_plugin_install()
{
    // Make sure translation is available.
    load_plugin_textdomain('textdomain', false, 'wp-plugin/languages');

    // Load resources
}
add_action('plugins_loaded', 'wp_plugin_install', 10, 0);

/**
 * Do deactivation
 *
 * @see https://developer.wordpress.org/reference/functions/register_deactivation_hook/
 */
function wp_plugin_deactivate($network)
{
    // Do something
}
register_activation_hook(__FILE__, 'wp_plugin_deactivate');
