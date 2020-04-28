<?php
/**
 * Plugin Name: WP Plugin
 * Plugin URI:  https://github.com/i30/wp-plugin
 * Description: My WordPress plugin boilerplate.
 * Author:      i30
 * Version:     1.0.0
 * Text Domain: wp-plugin
 * Requires PHP: 7.2
 * Requires at least: 5.2
 * Tested up to: 5.4
 */

/**
 * Pre-activation check
 */
function wp_plugin_pre_activation()
{
    if (version_compare(PHP_VERSION, '7.2', '<')) {
        throw new Exception(__('This plugin requires PHP version 7.2 at least!', 'textdomain'));
    }

    if (version_compare($GLOBALS['wp_version'], '5.2', '<')) {
        throw new Exception(__('This plugin requires WordPress version 5.2 at least!', 'textdomain'));
    }

    if (!defined('WP_CONTENT_DIR') || !is_writable(WP_CONTENT_DIR)) {
        throw new Exception(__('WordPress content directory is inaccessible.', 'textdomain'));
    }
}

/**
 * Do activation
 *
 * @see https://developer.wordpress.org/reference/functions/register_activation_hook/
 */
function wp_plugin_activate($network)
{
    try {
        wp_plugin_pre_activation();
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
    // add_option(self::SETTINGS_KEY, [
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
    // Useful constants.
    define('WP_PLUGIN_DIR', __DIR__ . '/');
    define('WP_PLUGIN_URI', plugins_url('/', __FILE__));
    define('WP_PLUGIN_VERSION', '1.0.0');

    // Make sure translation is available.
    load_plugin_textdomain('textdomain', false, basename(__DIR__) . '/languages');
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
