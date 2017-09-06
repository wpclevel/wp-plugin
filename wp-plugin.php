<?php namespace WP\Plugin;
/**
 * Plugin Name: Sample Plugin
 * Plugin URI:  https://github.com/i30/wp-plugin
 * Description: My simple WordPress plugin boilerplate.
 * Author:      i30
 * Version:     1.0.0
 * Text Domain: wp-plugin
 */
use Exception;
use ArrayObject;
use InvalidArgumentException;

final class SamplePlugin
{
    /**
     * Version
     *
     * @var    string
     */
    const VERSION = '1.0.0';

    /**
     * Option key
     *
     * @var    string
     */
    const OPTION_NAME = 'sample_plugin_options';

    /**
     * Base DIR
     *
     * @var    string
     */
    private $basedir;

    /**
     * Base URI
     *
     * @var    string
     */
    private $baseuri;

    /**
     * Modules
     *
     * @var    array
     */
    private $modules;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->modules = array();
        $this->basedir = __DIR__ . '/';
        $this->baseuri = preg_replace('/^http(s)?:/', '', plugins_url('/', __FILE__));

        add_action('plugins_loaded', array($this, '_install'), 10, 0);
        add_action('activate_wp-plugin/wp-plugin.php', array($this, '_activate'));
        add_action('deactivate_wp-plugin/wp-plugin.php', array($this, '_deactivate'));
    }

    /**
     * Getter
     *
     * A shortcut to find an entry by its identifier and returns it.
     *
     * @throws    InvalidArgumentException
     *
     * @return    object
     */
    function __get($id)
    {
        if (!is_string($id)) {
            throw new InvalidArgumentException(__('Invalid module identifier!', 'wp-plugin'));
        }

        if ('basedir' === $id) {
            return $this->basedir;
        } elseif ('baseuri' === $id) {
            return $this->baseuri;
        } else {
            if (!isset($this->modules[$id])) {
                throw new InvalidArgumentException(sprintf(__('Module "%s" not found!', 'wp-plugin'), $id));
            }
            return $this->modules[$id];
        }
    }

    /**
     * Do activation
     *
     * @internal    Used as a callback. DO NOT CALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/functions/register_activation_hook/
     *
     * @param    bool    $network    Whether to activate this plugin on network or a single site.
     */
    function _activate($network)
    {
        try {
            $this->preActivate();
        } catch (Exception $e) {
            exit($e->getMessage());
        }

        update_option(self::OPTION_NAME, array(

        ));
    }

    /**
     * Do installation
     *
     * @internal    Used as a callback. DO NOT CALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/hooks/plugins_loaded/
     */
    function _install()
    {
        // Make sure translation is available.
        load_plugin_textdomain('wp-plugin', false, $this->basedir . 'i18n');

        // Load resources.
        // require $this->basedir . 'src/Modules/Something.php';

        // Initialize modules.
        $this->modules['options'] = new ArrayObject(get_option(self::OPTION_NAME), ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Do deactivation
     *
     * @internal    Used as a callback. DO NOT CALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/functions/register_deactivation_hook/
     *
     * @param    bool    $network    Whether to deactivate this plugin on network or a single site.
     */
    function _deactivate($network)
    {

    }

    /**
     * Pre-activation check
     *
     * @throws    Exception
     */
    private function preActivate()
    {
        if (version_compare(PHP_VERSION, '5.6', '<')) {
            throw new Exception('This plugin requires PHP version 5.6 at least. Please update to the latest version for better performance and security!');
        }

        if (version_compare($GLOBALS['wp_version'], '4.6', '<')) {
            throw new Exception('This plugin requires WordPress version 4.6 at least. Please update to the latest version for better performance and security!');
        }

        // if (!is_writable(WP_CONTENT_DIR)) {
        //     throw new Exception('WordPress content directory is not writable. Please correct permission of the directory before activating this plugin!');
        // }
    }
}

// Initialize plugin.
return new SamplePlugin();
