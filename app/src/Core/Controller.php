<?php
namespace UTI\Core;

use UTI\Lib\Data;

abstract class Controller
{
    /**
     * @var \Aura\Router\Router
     */
    protected $router;
    /**
     * @var \UTI\Core\View
     */
    protected $view;

    /**
     * @var \UTI\Core\Model
     */
    protected $model;

    /**
     * @var \UTI\Lib\Data
     */
    protected $data;

    /**
     * Constructor.
     *
     * @param $router
     */
    public function __construct($router)
    {
        $this->router = $router;
        $this->view = new View(APP_TPL, HTML_TYPE);
        $this->data = new Data(URI_BASE);
    }
}
