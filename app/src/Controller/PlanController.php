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
        $data('notify.success', '');
        $data('notify.error', '');
        // Set view templates
        $this->view->set('plan_template', $data, ['plan_form', 'plan_form_stage', 'plan_form_result']);
        // Make page
        $data('plan.logout', $this->router->generate('auth.logout'));
        $data('title', 'План лечеиня');

        // working with stages using ajax
        if (false === ($form = $this->model->processForm($data, $this->view, 5))) {
            return;
        }

        if ($this->model->isFormProcessed($form->getName())) {
            //echo print_r($_POST, 1);
            //breaks charset, use header('Content-Type: text/html; charset=utf-8');
            //var_dump($_POST);

            if ($hash = $this->model->processPdf($form)) {
                $data('notify.success', $this->router->generate('plan.get', ['hash' => $hash]));
                //System::redirect2Url($this->router->generate('plan.get', ['hash' => $hash]), $_SERVER);
            } else {
                //error somewhere above
                $data('notify.error', 'PDF not processed, retry...');
            }
        }

        /*// get pdf if ready
        if ($hash = $this->model->isPdfReady()) {
            System::redirect2Url($this->router->generate('plan.get', ['hash' => $hash]), $_SERVER);
        }*/

        $this->view->render();
    }

    public function get($params)
    {
        // Data::__call() doest work on $this-data
        $data = $this->data;
        // Set view templates
        $this->view->set('plan_pdf_result', $data);
        $data('title', 'План лечения');


        $this->view->render();
    }

    // todo
    public function main2()
    {
        $data = new Data(URI_BASE);
        $data('plan_form', $this->model->processForm());

        //processPDF

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
