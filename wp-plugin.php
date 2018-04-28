<?php namespace WP\Plugins\SamplePlugin;

/**
 * Plugin Name: Sample Plugin
 * Plugin URI:  https://github.com/i30/wp-plugin
 * Description: My WordPress plugin boilerplate.
 * Author:      i30
 * Version:     1.0.0
 * Text Domain: wp-plugin
 * Requires PHP: 5.6
 */

// Load plugin container.
require __DIR__ . '/src/Plugin.php';

// Initialize plugin.
return new Plugin(get_option(Plugin::OPTION_NAME, []));
