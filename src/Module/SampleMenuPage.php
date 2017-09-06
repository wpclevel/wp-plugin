<?php namespace WP\Plugin\SamplePlugin\Module;
/**
 * SampleMenuPage
 */
use WP\Plugin\SamplePlugin as Plugin;

final class SampleMenuPage
{
    /**
     * Menu slug
     *
     * @var   string
     */
    const SLUG = 'sample-menu-page';

    /**
     * Plugin container
     *
     * @var    object
     */
    private $plugin;

    /**
     * Constructor
     */
    function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;

        add_action('admin_menu', array($this, '_add'));
    }

    /**
     * Add to admin menu
     *
     * @internal    Used as a callback. DO NOT CALL THIS METHOD DIRECTLY!
     *
     * @param    string    $context
     */
    function _add($context)
    {
        add_menu_page(
            __('Sample Menu Page', 'wpplugin'),
            __('Sample Menu Page', 'wpplugin'),
            'manage_options',
            self::SLUG,
            array($this, '_render'),
            'dashicons-admin-generic',
            89
        );
    }

    /**
     * Render
     */
    function _render($data)
    {
        // echo something...
    }
}
