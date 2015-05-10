<?php
namespace UTI\Core;

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
     * Constructor.
     *
     * @param $router
     */
    public function __construct($router)
    {
        $this->router = $router;
        $this->view = new View();
    }
}
