<?php namespace Wp\Plugins\SamplePlugin;

/**
 * Plugin Name: Sample Plugin
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
 * Core
 *
 * Plugin container.
 */
final class Core
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
     * Base DIR
     *
     * @var  string
     */
    private $basedir;

    /**
     * Base URI
     *
     * @var  string
     */
    private $baseuri;

    /**
     * Modules
     *
     * @var  array
     */
    private $modules;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->modules  = [];
        $this->basedir  = __DIR__ . '/';
        $this->baseuri  = str_replace(['http:', 'https:'], '', plugins_url('/', __FILE__));

        add_filter('plugins_loaded', [$this, '_install'], 10, 0);
        add_filter('activate_wp-plugin/wp-plugin.php', [$this, '_activate']);
        add_filter('deactivate_wp-plugin/wp-plugin.php', [$this, '_deactivate']);
    }

    /**
     * Getter
     *
     * A shortcut to find an entry by its identifier and returns it.
     *
     * @throws  InvalidArgumentException
     *
     * @return  object
     */
    function __get($id)
    {
        if (isset($this->$prop)) {
            return $this->$prop;
        } else {
            throw new InvalidArgumentException(__('Invalid property!', 'wp-plugin'));
        }
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
            exit($e->getMessage());
        }

        update_option(self::OPTION_NAME, [

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
        // Make sure translation is available.
        load_plugin_textdomain('wp-plugin', false, $this->basedir . 'i18n');

        // Load resources.
        require $this->basedir . 'src/Modules/Options.php';

        // Initialize modules.
        $this->modules['options'] = new Options(get_option(self::OPTION_NAME), ArrayObject::ARRAY_AS_PROPS);
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
            throw new Exception(sprintf('This plugin requires PHP version %s at least!', '5.6'));
        }

        if (version_compare($GLOBALS['wp_version'], '4.6', '<')) {
            throw new Exception(sprintf('This plugin requires WordPress version %s at least!', '4.6'));
        }

        // if (!is_writable(WP_CONTENT_DIR)) {
        //     throw new Exception('WordPress content directory is not writable. Please correct permission of the directory before activating this plugin!');
        // }
    }
}

// Initialize plugin.
return new Core();
