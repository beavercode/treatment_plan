<?php

namespace UTI\Model;

use UTI\Core\Model;
use UTI\Core\View;
use UTI\Lib\Form;

/**
 * Plan form stages handling
 *
 * Class FormStages
 * @package UTI\Lib
 */
class PlanStagesModel extends Model
{

    /**
     * @var View
     */
    protected $view;
    protected $min;
    protected $max;
    protected $template;

    /**
     * Init variables
     */
    public function __construct($view, $max, $min = 1)
    {
        parent::__construct();

        $this->view = $view;
        $this->max = $max;
        $this->min = $min;

        $this->template = 'plan_form_stage';

        // next step
        $form = new Form('plan_form');
        $form->load($this->session->get($form->getName()));
    }

    /**
     * Triggered when invoking inaccessible methods
     *
     * @param $methodName
     * @param $args
     * @return null
     */
    public function __call($methodName, $args)
    {
        if (method_exists($this, $methodName)) {
            return $this->$methodName($args);
        }

        return null;
    }

    /**
     * Init stage value with min
     */
    public function init()
    {
//        $this->session->set('stage', $this->min);

        // Process html form 1+ stages
        $html = '';
        for ($i = 1, $num = $this->session->get('stage'); $i <= $num; ++$i) {
            $html .= $this->view->block($this->template, ['plan.form.stageID' => $i]);
        }

        $data = [
            'stages' => $this->session->get('stage'),
            'html'   => $html
        ];

        $this->sendJSON($data);
    }

    /**
     * Handle stage number and echo html data
     */
    public function add()
    {
        $data = ['limit' => $this->session->get('stage')];

        if ($this->isNotMax()) {
            $this->session->set('stage', $this->session->get('stage') + 1);
            $html = $this->view->block($this->template, ['plan.form.stageID' => $this->session->get('stage')]);

            $data = [
                'html'      => $html,
                'stage'     => $this->session->get('stage'),
                'maxStages' => $this->max
            ];
        }

        $this->sendJSON($data);
    }

    /**
     * Handle stage number
     */
    public function delete()
    {
        $data = ['limit' => $this->session->get('stage')];

        if ($this->isNotMin()) {
            $data = [
                'stage'     => $this->session->get('stage'),
                'minStages' => $this->min
            ];
            $this->session->set('stage', $this->session->get('stage') - 1);
        }

        $this->sendJSON($data);
    }

    /**
     * Stage greater than min
     *
     * @return bool
     */
    protected function isNotMin()
    {
        return $this->session->get('stage') > $this->min;
    }

    /**
     * Stage lesser than max
     *
     * @return bool
     */
    protected function isNotMax()
    {
        return $this->session->get('stage') <= $this->max;
    }

    /**
     * Send data as json
     *
     * @param $data
     */
    protected function sendJSON($data)
    {
        $json = json_encode($data);
        //todo
        // http://stackoverflow.com/questions/10579116/how-to-flush-data-to-browser-but-continue-executing
        // http://stackoverflow.com/questions/3133209/how-to-flush-output-after-each-echo-call
        // http://stackoverflow.com/questions/265073/php-background-processes

        ob_start();
        header('Content-Type: application/json');
        echo $json;
        echo ob_get_clean();
    }
}
