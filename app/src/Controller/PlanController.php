<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Controller;

use UTI\Core\AppException;
use UTI\Core\Controller;
use UTI\Model\PlanModel;

/**
 * Used to handle plan page actions.
 *
 * @package UTI
 */
class PlanController extends Controller
{
    /**
     * Init.
     *
     * Uses parent ctor.
     *
     * @param $router {@inherit}
     *
     * @throws AppException
     */
    public function __construct($router)
    {
        parent::__construct($router);
        $this->model = new PlanModel();

        if (! $this->model->isLogged()) {
            $this->router->redirect('auth.login');
        }
    }

    /**
     * Process index (default) action.
     *
     * @throws AppException
     */
    public function index()
    {
        //todo Data::__call() doesn't work on $this-data
        $data = $this->data;

        // Set view templates.
        $this->view->set('plan_template', $data, ['plan_form', 'plan_form_stage', 'plan_form_result']);

        // Get page's specific data.
        $data('plan.logout', $this->router->getUri('auth.logout'));
        $data('title', 'План лечения');
        $data('notify.success', '');
        $data('notify.error', '');

        // Working with stages using ajax.
        if (false === ($form = $this->model->processForm($data, $this->view, APP_STAGES_MAX, APP_STAGES_MIN))) {
            return;
        }
        // If form processed: all field are right, and files(docx,excel) are loaded.
        if ($this->model->isFormProcessed($form)) {
            if ($hash = $this->model->processPdf($form)) {
                $data('notify.success', $this->router->getUri('plan.get', ['name' => $hash]));

                //todo Prevent resubmit through redirect resets all stages ... =(
                //System::redirect2Url($this->router->generate('plan.index', ['time' => md5(time())]), $_SERVER);
                //$this->router->redirect('plan.index', ['time' => md5(time())]);
            } else {
                // Error message.
                $data('notify.error', 'PDF not processed, retry...');
            }
        }

        $this->view->render();

        //todo debug, toDel
        var_dump($_POST);
    }

    /**
     * Show or download pdf file by token(hash).
     *
     * @param array $params Data keys map to param tokens in the path.
     *
     * @throws AppException
     */
    public function get($params)
    {
        //todo Data::__call() doesn't work on $this-data
        $data = $this->data;

        // Set view templates
        $this->view->set('plan_pdf_result', $data);

        $data('pdf', $this->model->showPdf($params['name'], APP_RESULT));

        // compression breaks PDF file, do not use it!
        $this->view->render(['minify' => false]);
    }

    // -------------- NEXT ACTIONS IS OPTIONAL -------------- //

    /**
     * Show table of the treatment plans with possibility to generate again or
     * open plan in fully filled form with possibility edit it and generate.
     *
     * todo
     *
     * @param $params
     *
     * @throws AppException
     */
    public function show($params)
    {
        //generating links based on controller and action using aura/router
        $path = $this->router->getUri('plan.show.name');

        echo htmlspecialchars($path, ENT_QUOTES, 'UTF-8');
        var_dump($params);
    }

    // ??
    public function showByName($params)
    {
        echo __METHOD__;
        var_dump($params);
    }
}
