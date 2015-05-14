<?php
namespace UTI\Controller;

use UTI\Core\Controller;
use UTI\Core\System;
use UTI\Lib\Data;
use UTI\Model\AuthModel;

/**
 * Class LoginController
 * @package UTI\Controller
 */
class AuthController extends Controller
{
    /**
     * Constructor.
     * Uses parent one
     *
     * @param $router
     */
    public function __construct($router)
    {
        parent::__construct($router);
        $this->model = new AuthModel();
    }

    /**
     * Log in into the system and redirect to "plan.main"
     */
    public function login()
    {
        $data = new Data(URI_BASE);
        $data('form', $this->model->processForm());

        if ($this->model->isLogged()) {
            System::redirect2Url($this->router->generate('plan.main'), $_SERVER);
        }
        $this->view->render('login_form.php', 'login_template.php', $data);
    }

    /**
     * Log out of the system adn redirect to "auth.login"
     */
    public function logout()
    {
        if ($this->model->isLogged()) {
            $this->model->logOut();
            System::redirect2Url($this->router->generate('auth.login'), $_SERVER);
        }
    }
}
