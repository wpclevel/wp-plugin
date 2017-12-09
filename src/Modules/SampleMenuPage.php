<?php namespace Wp\Plugins\SamplePlugin;

use Wp\Plugins\SamplePlugin\Core;

/**
 * SampleMenuPage
 */
final class SampleMenuPage
{
    /**
     * Menu slug
     *
     * @var  string
     */
    const SLUG = 'sample-menu-page';

    /**
     * Plugin container
     *
     * @var  object
     */
    private $container;

    /**
     * Constructor
     */
    function __construct(Core $container)
    {
        $this->container = $container;

        add_action('admin_menu', [$this, '_add']);
    }

    /**
     * Add to admin menu
     *
     * @internal  Used as a callback.
     *
     * @param  string  $context
     */
    function _add($context)
    {
        add_menu_page(
            __('Sample Menu Page', 'wp-plugin'),
            __('Sample Menu Page', 'wp-plugin'),
            'manage_options',
            self::SLUG,
            [$this, '_render'],
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
