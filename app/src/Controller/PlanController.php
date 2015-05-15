<?php
namespace UTI\Controller;

use UTI\Core\Controller;
use UTI\Core\System;
use UTI\Lib\Data;
use UTI\Lib\Form;
use UTI\Model\PlanModel;

/**
 * Class Plan
 * @package UTI\Controller
 */
class PlanController extends Controller
{
    public function __construct($router)
    {
        parent::__construct($router);
        $this->model = new PlanModel();

        if (! $this->model->isLogged()) {
            System::redirect2Url($this->router->generate('auth.login'), $_SERVER);
        }
    }

    public function main($params)
    {
        $data = new Data(URI_BASE);

        // Make stages for AJAX
        if (isset($_POST['stage'])) {
            //todo get stages 'id => name' from DB
            $data('stages', $this->model->getFormStages());

            $this->model->crateStages(
                $_POST['stage'],
                $this->view->load('plan_form_stage.php', $data),
                5,
                1
            );

            return null;
        }

        //Usual page render
        //todo get doctors 'id => name' from DB
        $data('form.doctors', $this->model->getFormDoctors());
        // Links
        //$data('link.action', $this->router->generate('plan.add'));
        $data('link.action', '/form/add');
        $data('link.logout', $this->router->generate('auth.logout'));

        $this->view->render('plan_form.php', 'plan_template.php', $data);
    }

    public function add($params)
    {
        // Process form
        echo $this->model->processForm();

        var_dump($_SERVER);
        var_dump($_POST);
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
