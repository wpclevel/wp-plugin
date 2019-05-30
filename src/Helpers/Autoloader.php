<?php namespace WP\Plugins\Foo\Helpers;

use InvalidArgumentException;

/**
 * Autoloader
 *
 * !WARNING: Only works with PSR-4 and unqualified classes.
 */
final class Autoloader
{
    /**
     * Namespace
     *
     * @var  string
     */
    private $ns;

    /**
     * Base directory
     *
     * @var  string
     */
    private $dir;

    /**
     * Nope constructor
     */
    private function __construct()
    {

    }

    /**
     * Singleton
     */
    static function init()
    {
        static $self = null;

        if (null === $self) {
            $self = new self;
        }

        return $self;
    }

    /**
     * Autoload PSR-4 classes inside a directory.
     *
     * @param  string  $ns   Namespace prefix.
     * @param  string  $dir  Base directory.
     */
    function load($ns, $dir)
    {
        $this->ns = trim($ns, '\\');
        $this->dir = rtrim($dir, '/');

        spl_autoload_register([$this, 'loadClass'], true, false);
    }

    /**
     * Load file for the given class name
     *
     * @param  string  $class
     */
    private function loadClass($class)
    {
        if (0 !== strpos($class, $this->ns)) {
            return;
        }

        $path = str_replace($this->ns, $this->dir, $class);
        $file = str_replace('\\', '/', $path) . '.php';

        if (file_exists($file)) {
            require $file;
        } else {
            throw new InvalidArgumentException(sprintf(__('Class %s not found.', 'wp-plugin'), $class));
        }
    }
}
