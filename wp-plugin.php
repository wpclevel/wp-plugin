<?php namespace WP\Plugins\Foo;

/**
 * Plugin Name: Foo Plugin
 * Plugin URI:  https://github.com/i30/wp-plugin
 * Description: My WordPress plugin boilerplate.
 * Author:      i30
 * Version:     1.0.0
 * Text Domain: wp-plugin
 * Requires PHP: 5.6
 */

use Exception;
use ArrayObject;
use InvalidArgumentException;

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
    const OPTION_NAME = 'foo_plugin_settings';

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
        $this->settings = array_merge(
            [], // Maybe merge with default values
            $settings
        );

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
        define('FOO_PLUGIN_DIR', __DIR__ . '/');
        define('FOO_PLUGIN_URI', str_replace(['http:', 'https:'], '', plugins_url('/', __FILE__)));

        // Make sure translation is available.
        load_plugin_textdomain('wp-plugin', false, __DIR__ . '/languages');

        // Load autoloader.
        require __DIR__ . '/src/Helpers/Autoloader.php';

        // Register autoloading.
        Helpers\Autoloader::init()->load(__NAMESPACE__, __DIR__ . '/src');
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
     * Pre-activation check
     *
     * @throws  Exception
     */
    private function preActivate()
    {
        if (version_compare(PHP_VERSION, '5.6', '<')) {
            throw new Exception(__('This plugin requires PHP version 5.6 at least!', 'wp-plugin'));
        }

        if (version_compare($GLOBALS['wp_version'], '4.7', '<')) {
            throw new Exception(__('This plugin requires WordPress version 4.7 at least!', 'wp-plugin'));
        }

        // if (!defined('WP_CONTENT_DIR') || !is_writable(WP_CONTENT_DIR)) {
        //     throw new Exception(__('WordPress content directory is undefined or not writable.', 'wp-plugin'));
        // }
    }
}

// Initialize plugin.
return new Plugin(get_option(Plugin::OPTION_NAME, []));
