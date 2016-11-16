<?php
/**
 * Plugin Name: WP Plugin
 * Plugin URI:  https://github.com/i30/wp-plugin
 * Description: My simple WordPress plugin boilerplate.
 * Author:      i30
 * Version:     1.0.0
 * Author URI:  https://i30.github.com/
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
    const OPTION_NAME = 'wp_plugin_settings';

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
        $this->settings['basedir']  = __DIR__ . '/';
        $this->settings['baseuri']  = plugins_url( '/', __FILE__ );
    }

    /**
     * Do activation
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/functions/register_activation_hook/
     *
     * @param    bool    $network    Whether activating this plugin on network or a single site.
     */
    function activate($network)
    {
        add_option(self::OPTION_NAME, array(

        ));
    }

    /**
     * Do installation
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/hooks/plugins_loaded/
     */
    function install()
    {
        load_plugin_textdomain('wp-plugin', false, $this->settings['basedir'] . 'languages');

    }

    /**
     * Do deactivation
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/functions/register_deactivation_hook/
     *
     * @param    bool    $network    Whether deactivating this plugin on network or a single site.
     */
    function deactivate($network)
    {

    }

    /**
     * Do uninstallation
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/functions/register_uninstall_hook/
     */
    static function uninstall()
    {

    }
}

// Get CleverMegaMenu instance.
$plugin = new WPPlugin(get_option(WPPlugin::OPTION_NAME));

// Register activation hook.
register_activation_hook(__FILE__, array($plugin, 'activate'));

// Register deactivation hook.
register_deactivation_hook(__FILE__, array($plugin, 'deactivate'));

// Register installation hook.
add_action('plugins_loaded', array($plugin, 'install'), 10, 0);

// Register uninstallation hook.
register_uninstall_hook(__FILE__, 'WPPlugin::uninstall');

// Unset plugin instance from global space.
unset($plugin);
