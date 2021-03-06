<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Controller;

use UTI\Core\AbstractController;
use UTI\Core\Exceptions\ViewException;
use UTI\Lib\Config\Exceptions\ConfigException;
use UTI\Model\PlanModel\PlanModel;
use UTI\Core\Exceptions\ModelException;
use UTI\Core\Exceptions\RoutingException;

/**
 * Used to handle plan page actions.
 *
 * @package UTI
 */
class PlanController extends AbstractController
{
    /**
     * Uses parent ctor.
     *
     * @inheritdoc
     *
     * @throws ModelException
     * @throws RoutingException
     */
    public function __construct($router)
    {
        parent::__construct($router);

        $this->model = new PlanModel($this->conf);

        if (!$this->model->isLogged()) {
            $this->router->redirect('auth.login');
        }
    }

    /**
     * Process index (default) action.
     *
     * @throws RoutingException
     * @throws ModelException
     * @throws ViewException
     */
    public function index()
    {
        //todo Data::__call() doesn't work on $this-data
        $data = $this->data;
        try {
            $stagesMax = $this->conf->get('stages.max');
            $stagesMin = $this->conf->get('stages.min');
        } catch (ConfigException $e) {
            // Catch if config option do not exists (wrong name, misspelling etc.)
            throw new RoutingException($e->getMessage(), null, $e);
        }

        // Set view templates.
        $this->view->set('plan_template', $data, ['plan_form', 'plan_form_stage', 'plan_form_result']);

        // Get page's specific data.
        $data('plan.logout', $this->router->getUri('auth.logout'));
        $data('title', 'План лечения');
        $data('notify.success', '');
        $data('notify.error', '');

        // Working with stages using ajax.
        $form = $this->model->processForm(
            $data,
            $this->view,
            $stagesMax,
            $stagesMin
        );
        if (false === $form) {
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

        // Show page.
        $this->view->render();

        var_dump($_POST);//todo debug, toDel
    }

    /**
     * Show or download pdf file by token(hash).
     *
     * @param array $params Data keys map to param tokens in the path.
     *
     * @throws RoutingException
     * @throws ModelException
     * @throws ViewException
     */
    public function get($params)
    {
        //todo Data::__call() doesn't work on $this-data
        $data = $this->data;

        try {
            // Set view template.
            $this->view->set('plan_pdf_result', $data);
            // Generate pdf result string.
            $data('pdf', $this->model->showPdf($params['name'], $this->conf->get('pdf_result')));
            // Compression breaks PDF file. Thus do not use it!!!
            $this->view->render(['minify' => false]);
        } catch (ConfigException $e) {
            throw new RoutingException($e->getMessage(), null, $e);
        }
    }

    // -------------- NEXT ACTIONS IS OPTIONAL -------------- //

    /**
     * Show table of the treatment plans with possibility to generate again or
     * open plan in fully filled form with possibility edit it and generate.
     *
     * todo example.com/plan/show   example.com/plan/show/12
     * todo show all                show 12 treatment plan
     *
     * @param $params
     *
     * @throws RoutingException
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
