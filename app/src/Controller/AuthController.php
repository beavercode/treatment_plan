<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Controller;

use UTI\Core\AbstractController;
use UTI\Core\Exceptions;
use UTI\Model\AuthModel\AuthModel;

/**
 * Used to handle auth page actions.
 *
 * @package UTI
 */
class AuthController extends AbstractController
{
    /**
     * Uses parent ctor.
     *
     * @inheritdoc
     *
     * @throws Exceptions\RoutingException
     * @throws Exceptions\ModelException
     */
    public function __construct($router)
    {
        parent::__construct($router);

        $this->model = new AuthModel($this->conf);
    }

    /**
     * Log in into the system and redirect to "plan.index".
     *
     * @throws Exceptions\RoutingException
     * @throws Exceptions\ViewException
     */
    public function login()
    {
        //todo Data::__call() doesn't work on $this-data
        $data = $this->data;

        // Set view templates.
        $this->view->set('login_template', $data, ['login_form']);

        // Get page's specific data.
        $data('title', 'Авторизация');
        $data('login.form', $this->model->processForm());

        //todo Better session handling. Move to router, create method redirect; for generate too?
        if ($this->model->isLogged()) {
            $this->router->redirect('plan.index');
        }

        $this->view->render();
    }

    /**
     * Log out of the system and redirect to "auth.login".
     *
     * @throws Exceptions\RoutingException
     */
    public function logout()
    {
        if ($this->model->isLogged()) {
            $this->model->logOut();
        }
        $this->router->redirect('auth.login');
    }
}
