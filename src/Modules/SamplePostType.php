<?php namespace Wp\Plugin\SamplePlugin\Modules;
/**
 * SamplePostType
 */
use Wp\Plugin\SamplePlugin as Plugin;

final class SamplePostType
{
    /**
     * Type
     *
     * @var   string
     */
    const TYPE = 'sample-post-type';

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

        add_action('init', array($this, '_register'), 10, 0);
        add_filter('post_updated_messages', [$this, '_filterUpdatedMessages'], PHP_INT_MAX);
    }


    /**
     * Register
     *
     * @internal    Used as a callback. DO NOT CALL THIS METHOD DIRECTLY!
     */
    function _register()
    {
        register_post_type(
            self::TYPE,
            array(
                'labels' => array(
                    'name'          => __('Sample Post Type', 'wp-plugin'),
                    'singular_name' => __('Sample Post Type', 'wp-plugin'),
                    'add_new'       => __('Add New', 'wp-plugin'),
                    'add_new_item'  => __('Add New', 'wp-plugin'),
                    'edit_item'     => __('Edit', 'wp-plugin'),
                    'new_item'      => __('New', 'wp-plugin'),
                    'view_item'     => __('Preview', 'wp-plugin'),
                    'view_items'    => __('Preview', 'wp-plugin'),
                    'search_items'  => __('Search', 'wp-plugin'),
                    'not_found'     => __('No Post Found', 'wp-plugin'),
                    'all_items'     => __('All Sample Posts', 'wp-plugin'),
                )
            )
        );
    }

    /**
     * Filter updated messages
     *
     * @internal    Used as a callback. DO NOT CALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/hooks/post_updated_messages/
     */
    function _filterUpdatedMessages($messages)
    {
        $messages[self::SLUG] = [
            0  => '',
            1  => __('Sample post updated.', 'wp-plugin'),
            2  => __('Custom field updated.', 'wp-plugin'),
            3  => __('Custom field deleted.', 'wp-plugin'),
            4  => __('Sample post updated.', 'wp-plugin'),
            5  => isset($_GET['revision']) ? __('Sample post restored to revision from', 'wp-plugin') . ' ' . wp_post_revision_title(absint($_GET['revision'])) : false,
            6  => __('Sample post published.', 'wp-plugin'),
            7  => __('Sample post saved.', 'wp-plugin'),
            8  => __('Sample post submitted.', 'wp-plugin'),
            9  => __('Sample post scheduled.', 'wp-plugin'),
            10 => __('Sample post draft updated.', 'wp-plugin')
        ];

        return $messages;
    }
}
