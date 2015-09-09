<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Core;

use UTI\Lib\Config\Config;
use UTI\Lib\Data;

/**
 * Takes a decision about what model and view would be used.
 *
 * Makes possible redirects and route-based URI generation.
 *
 * @package UTI
 */
abstract class AbstractController
{
    /**
     * @var Router
     */
    protected $router;
    /**
     * @var View
     */
    protected $view;

    /**
     * @var AbstractModel
     */
    protected $model;

    /**
     * @var \UTI\Lib\Data
     */
    protected $data;

    /**
     * Init.
     *
     * @param Router $router Wrapper for Aura\Router
     */
    public function __construct($router)
    {
        $this->router = $router;
        $this->view = new View(Config::$APP_TPL_VIEW, Config::$HTML_TYPE);

        //todo Find nice looking way to handle Data object.
        $data = new Data();
        $data('base', Config::$URI_BASE);
        $this->data = $data;
    }
}
