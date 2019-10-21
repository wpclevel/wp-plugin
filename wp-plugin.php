<?php namespace SamplePlugin;

/**
 * Plugin Name: Sample Plugin
 * Plugin URI:  https://sarahcoding.com/wp-plugin-sample
 * Description: My WordPress plugin boilerplate.
 * Author:      SarahCoding
 * Version:     1.0.0
 * Text Domain: wp-plugin
 * Requires PHP: 7.2
 */

use Exception;

/**
 * Plugin container.
 */
final class Plugin
{
    /**
     * Version
     *
     * @var  string
     */
    const VERSION = '1.0.0';

    /**
     * Option key
     *
     * @var  string
     */
    const OPTION_NAME = 'sample_plugin_settings';

    /**
     * Settings
     *
     * @var array
     */
    private $settings;

    /**
     * Constructor
     */
    function __construct(array $settings = [])
    {
        $this->settings = array_merge([
            'flushed_rewrite_rules' => false
        ], $settings);

        add_action('plugins_loaded', [$this, '_install'], 10, 0);
        add_action('activate_wp-plugin/wp-plugin.php', [$this, '_activate']);
        add_action('deactivate_wp-plugin/wp-plugin.php', [$this, '_deactivate']);
    }

    /**
     * Do activation
     *
     * @internal  Used as a callback.
     *
     * @see  https://developer.wordpress.org/reference/functions/register_activation_hook/
     *
     * @param  bool  $network  Whether to activate this plugin on network or a single site.
     */
    function _activate($network)
    {
        try {
            $this->preActivate();
        } catch (Exception $e) {
            if (defined('DOING_AJAX') && DOING_AJAX) {
                header('Content-Type: application/json; charset=' . get_option('blog_charset'));
                status_header(500);
                exit(json_encode([
                    'success' => false,
                    'name'    => __('Plugin Activation Error', 'wp-plugin'),
                    'message' => $e->getMessage()
                ]));
            } else {
                exit($e->getMessage());
            }
        }

        add_option(self::OPTION_NAME, [
            'flushed_rewrite_rules' => false
        ]);
    }

    /**
     * Do installation
     *
     * @internal  Used as a callback.
     *
     * @see  https://developer.wordpress.org/reference/hooks/plugins_loaded/
     */
    function _install()
    {
        // Define useful constants.
        define('SAMPLE_PLUGIN_DIR', __DIR__ . '/');
        define('SAMPLE_PLUGIN_URI', str_replace(['http:', 'https:'], '', plugins_url('/', __FILE__)));

        // Make sure translation is available.
        load_plugin_textdomain('wp-plugin', false, __DIR__ . '/languages');

        // Register autoloading.
        $this->registerAutoloading();
    }

    /**
     * Do deactivation
     *
     * @internal  Used as a callback.
     *
     * @see  https://developer.wordpress.org/reference/functions/register_deactivation_hook/
     *
     * @param  bool  $network  Whether to deactivate this plugin on network or a single site.
     */
    function _deactivate($network)
    {

    }

    /**
     * Register autoloading
     *
     * Register PSR4 classes autoloading base on current namespace and `src` directory as bases.
     */
    private function registerAutoloading()
    {
        spl_autoload_register(function($class) {
            if (0 !== strpos($class, __NAMESPACE__)) {
                return; // Not in my job description :)
            }

            $path = str_replace(__NAMESPACE__, __DIR__ . '/src', $class);
            $file = str_replace('\\', '/', $path) . '.php';

            if (file_exists($file)) {
                require $file;
            } else {
                throw new Exception(sprintf(__('Autoloading failed. Class "%s" not found.', 'wp-plugin'), $class));
            }
        }, true, false);
    }

    /**
     * Pre-activation check
     *
     * @throws  Exception
     */
    private function preActivate()
    {
        if (version_compare(PHP_VERSION, '7.2', '<')) {
            throw new Exception(__('This plugin requires PHP version 7.2 at least!', 'wp-plugin'));
        }

        if (version_compare($GLOBALS['wp_version'], '5.2', '<')) {
            throw new Exception(__('This plugin requires WordPress version 5.2 at least!', 'wp-plugin'));
        }

        if (!defined('WP_CONTENT_DIR') || !is_writable(WP_CONTENT_DIR)) {
            throw new Exception(__('WordPress content directory is inaccessible.', 'wp-plugin'));
        }
    }
}

// Initialize plugin.
return new Plugin(get_option(Plugin::OPTION_NAME, []));
