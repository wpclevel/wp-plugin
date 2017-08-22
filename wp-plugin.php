<?php
/**
 * Plugin Name: WP Plugin
 * Plugin URI:  https://github.com/i30/wp-plugin
 * Description: My simple WordPress plugin boilerplate.
 * Author:      i30
 * Version:     1.0.0
 * Text Domain: wp-plugin
 */
final class WPPlugin
{
    /**
     * Version
     *
     * @var    string
     */
    const VERSION = '1.0.0';

    /**
     * Option name
     *
     * @var    string
     */
    const SETTING_KEY = 'wp_plugin_settings';

    /**
     * Plugin settings
     *
     * @var    array
     */
    private $settings;

    /**
     * Constructor
     */
    function __construct($settings)
    {
        $this->settings = $settings ? (array)$settings : array();
        $this->settings['basedir']  = wp_normalize_path(__DIR__ . '/');
        $this->settings['baseuri']  = preg_replace('/^http(s)?:/', '', plugins_url('/', __FILE__));

        add_action('activate_wp-plugin/wp-plugin.php', array($this, '_activate'));
        add_action('deactivate_wp-plugin/wp-plugin.php', array($this, '_deactivate'));
        add_action('plugins_loaded', array($this, '_install'), 10, 0);
    }

    /**
     * Do activation
     *
     * @internal    Used as a callback. DO NOT CALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/functions/register_activation_hook/
     *
     * @param    bool    $network    Whether activating this plugin on network or a single site.
     */
    function _activate($network)
    {
        try {
            $this->preActivate();
        } catch (Exception $e) {
            exit($e->getMessage());
        }

        if (!$this->settings) {
            add_option(self::SETTING_KEY, array(

            ));
        }
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
        load_plugin_textdomain('wp-plugin', false, $this->settings['basedir'] . 'i18n');

    }

    /**
     * Do deactivation
     *
     * @internal    Used as a callback. DO NOT CALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/functions/register_deactivation_hook/
     *
     * @param    bool    $network    Whether deactivating this plugin on network or a single site.
     */
    function _deactivate($network)
    {

    }

    /**
     * Pre-activation check
     */
    private function preActivate()
    {
        if (version_compare(PHP_VERSION, '5.6', '<')) {
            throw new Exception('This plugin requires PHP version 5.6 at least. Please update to the latest version for better performance and security!');
        }

        if (version_compare($GLOBALS['wp_version'], '4.4', '<')) {
            throw new Exception('This plugin requires WordPress version 4.4 at least. Please update to the latest version for better performance and security!');
        }
    }
}

// Initialize plugin.
return new WPPlugin(get_option(WPPlugin::SETTING_KEY));
