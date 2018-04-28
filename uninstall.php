<?php namespace WP\Plugins\SamplePlugin;

/**
 * Uninstaller
 *
 * @author   i30  <minhtri.contact@gmail.com>
 * @license  GPL v3+
 */

if (!class_exists('WP\Plugins\SamplePlugin\Plugin', false)) {
    require __DIR__ . '/src/Plugin.php';
}

delete_option(Plugin::OPTION_NAME);
