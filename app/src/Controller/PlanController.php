<?php
namespace UTI\Controller;

use UTI\Core\Controller;
use UTI\Core\System;
use UTI\Lib\Data;
use UTI\Model\PlanModel;

/**
 * Class Plan
 * @package UTI\Controller
 */
class PlanController extends Controller
{
    /**
     * @param $router
     */
    public function __construct($router)
    {
        parent::__construct($router);
        $this->model = new PlanModel();

        if (! $this->model->isLogged()) {
            System::redirect2Url($this->router->generate('auth.login'), $_SERVER);
        }
    }

    public function index()
    {
        // Data::__call() doest work on $this-data
        $data = $this->data;
        // Set view templates
        $this->view->set('plan_template.php', $data, ['plan_form', 'plan_form_stage']);
        // Links
        $data('plan.logout', $this->router->generate('auth.logout'));
        $data('title', 'План лечеиня');

        // Initialize form with data and handle ajax requests
        if ($this->model->makeForm($this->view, $data, 5)) {
            //return true;
        }
        $this->model->processForm($data);
        // if ok go to data processing, else save previous values and emit form again
        if ($this->model->isFormPassed()) {
            var_dump($_POST);
        }



        /*// get pdf if ready
        if ($hash = $this->model->isPdfReady()) {
            System::redirect2Url($this->router->generate('plan.get', ['hash' => $hash]), $_SERVER);
        }*/

        $this->view->render();
    }

    public function get($params)
    {
    }

    // todo
    public function main2()
    {
        $data = new Data(URI_BASE);
        $data('plan_form', $this->model->processForm());

        if ($this->model->isPdfReady()) {
            $hash = $this->model->getPdfName();
            System::redirect2Url($this->router->generate('plan.get', ['hash' => $hash]), $_SERVER);
        }
        $this->view->render('plan_result.php', 'login_template.php', $data);
    }

    // todo
    public function get2($params)
    {
        $data = new Data(URI_BASE);
        $data('pdf', $this->model->getPdf($params['hash']));

        $this->view->render('plan_result.php', 'plan_template.php', $data);
    }

    // -------------- NEXT ACTIONS IS OPTIONAL -------------- //

    public function show($params)
    {
        //generating links based on controller and action using aura/router
        $path = $this->router->generate('plan.show.name');

        echo htmlspecialchars($path, ENT_QUOTES, 'UTF-8');
    }

    public function showByName($params)
    {
        echo __METHOD__;
        var_dump($params);
    }
}
