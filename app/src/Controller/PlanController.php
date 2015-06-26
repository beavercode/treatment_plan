<?php

namespace UTI\Controller;

use UTI\Core\Controller;
use UTI\Core\System;
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
        if (false === ($form = $this->model->processForm($data, $this->view, APP_STAGES_MAX, APP_STAGES_MIN))) {
            return;
        }
        // if form processed: all field are right, and files(docx) are loaded
        if ($this->model->isFormProcessed($form)) {
            if ($hash = $this->model->processPdf($form)) {
                $data('notify.success', $this->router->generate('plan.get', ['pdf' => $hash]));

                // redirect resets all stages ... =(
                //System::redirect2Url($this->router->generate('plan.main', ['time' => md5(time())]), $_SERVER);
            } else {
                //error somewhere above
                $data('notify.error', 'PDF not processed, retry...');
            }
            //todo stub for real load
            //sleep(1);
        }

        $this->view->render();
        var_dump($_POST);
    }

    /**
     * http://mozilla.github.io/pdf.js/
     *
     * @param $params
     */
    public function get($params)
    {
        // Data::__call() doest work on $this-data
        $data = $this->data;
        // Set view templates
        $this->view->set('plan_pdf_result', $data);

        $data('pdf', $this->model->showPdf($params['pdf'], 'show'));

        // DO NOT USE COMPRESSION FOR PDF!!!!!
        $this->view->render(['minify' => false]);
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
