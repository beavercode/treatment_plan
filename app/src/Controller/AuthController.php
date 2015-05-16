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
        $data = $this->data;
        $this->view->set('login_template.php', $data, ['login_form']);

        $data('title', 'Авторизация');
        $data('login.form', $this->model->processForm());

        if ($this->model->isLogged()) {
            System::redirect2Url($this->router->generate('plan.main'), $_SERVER);
        }

        $this->view->render();
    }

    /**
     * Log out of the system and redirect to "auth.login"
     */
    public function logout()
    {
        if ($this->model->isLogged()) {
            $this->model->logOut();
            System::redirect2Url($this->router->generate('auth.login'), $_SERVER);
        }
    }
}
