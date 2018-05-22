<?php namespace WP\Plugins\SamplePlugin;

use Exception;
use ArrayObject;
use InvalidArgumentException;

/**
 * Plugin
 *
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
    function __construct(array $settings = [])
    {
        $this->modules = ['settings' => new ArrayObject($settings)];
        $this->basedir = dirname(__DIR__) . '/';
        $this->baseuri = str_replace(['http:', 'https:'], '', plugins_url('/', dirname(__FILE__)));

        add_action('plugins_loaded', [$this, '_install'], 10, 0);
        add_action('activate_wp-plugin/wp-plugin.php', [$this, '_activate']);
        add_action('deactivate_wp-plugin/wp-plugin.php', [$this, '_deactivate']);
    }

    /**
     * Getter
     *
     * @throws  InvalidArgumentException
     *
     * @return  object
     */
    function __get($prop)
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
            if (defined('DOING_AJAX') && DOING_AJAX) {
                header('Content-Type: application/json; charset=' . get_option('blog_charset'));
                status_header(500);
                exit(json_encode([
                    'error' => [
                        'name'    => 'Plugin Activation Error',
                        'code'    => $e->getCode(),
                        'message' => $e->getMessage(),
                    ]
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
        // Make sure translation is available.
        load_plugin_textdomain('wp-plugin', false, $this->basedir . 'languages');

        var_dump($this->baseuri);

        // Load autoloader.
        require $this->basedir . 'src/Helpers/Autoloader.php';

        // Register autoloading.
        Helpers\Autoloader::init()->load(__NAMESPACE__, __DIR__);
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
            throw new Exception('This plugin requires PHP version 5.6 at least!');
        }

        if (version_compare($GLOBALS['wp_version'], '4.7', '<')) {
            throw new Exception('This plugin requires WordPress version 4.7 at least!');
        }
    }
}
