<?php namespace WP\Plugins\Foo\Admin\MenuPages;

use InvalidArgumentException;

/**
 * AbstractMenuPage
 */
abstract class AbstractMenuPage
{
    /**
     * Page title
     *
     * @var  string
     */
    protected $page_title = 'Abstract Menu Page';

    /**
     * Menu title
     *
     * @var  string
     */
    protected $menu_title = 'Abstract Menu Page';

    /**
     * Required user's capability
     *
     * @var  string
     */
    protected $capability = 'read';

    /**
     * Menu slug
     *
     * @var  string
     */
    protected $menu_slug = 'abstract-menu-page';

    /**
     * Menu icon
     *
     * @var  string
     */
    protected $icon_url = 'dashicons-admin-generic';

    /**
     * Menu position
     *
     * @var  string
     */
    protected $position = null;

    /**
     * Page hook
     *
     * @var  string
     */
    protected $hook_name = '';

     /**
      * Property getter
      *
      * @throws  InvalidArgumentException
      *
      * @return  string
      */
     function __get($prop)
     {
        if (!isset($this->$prop)) {
            throw new InvalidArgumentException(sprintf(__('Undefined property: "%s"!', 'wp-plugin'), $prop));
        }

        return $this->$prop;
     }

     /**
      * Add to admin menu
      *
      * @internal  Callback
      */
     function _add($context)
     {
         $this->hook_name = add_menu_page(
             $this->page_title,
             $this->menu_title,
             $this->capability,
             $this->menu_slug,
             [$this, '_render'],
             $this->icon_url,
             $this->position
         );
     }

     /**
      * Render
      *
      * @internal  Callback
      */
     abstract public function _render($page_data);
}
