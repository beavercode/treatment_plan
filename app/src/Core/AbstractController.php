<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Core;

use UTI\Lib\Data;
use UTI\Lib\Config\ConfigData;
use UTI\Lib\Config\Exceptions\ConfigException;
use UTI\Core\Exceptions\RoutingException;

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
     * @var ConfigData Class that stores configuration information
     */
    protected $conf;

    /**
     * @var \UTI\Lib\Data Stores view data
     */
    protected $data;

    /**
     * Init.
     *
     * @param Router $router Wrapper for Aura\Router
     *
     * @throws RoutingException
     */
    public function __construct($router)
    {
        try {
            $this->router = $router;
            $this->conf = $router->conf;
            $this->view = new View($this->conf->get('dir.tpl.view'), $this->conf->get('html_type'));

            //todo Find nice looking way to handle Data object.
            $data = new Data();
            $data('base', $this->conf->get('uri_base'));
            $this->data = $data;
        } catch (ConfigException $e) {
            // Catch if config option do not exists (wrong name, misspelling etc.)
            throw new RoutingException($e->getMessage(), null, $e);
        }
    }
}
