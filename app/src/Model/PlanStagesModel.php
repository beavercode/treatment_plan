<?php

namespace UTI\Model;

use UTI\Core\Model;

/**
 * Plan form stages handling
 *
 * Class PlanStagesModel
 * @package UTI\Model
 */
class PlanStagesModel extends Model
{

    /**
     * @var \UTI\Core\View
     */
    protected $view;
    protected $min;
    protected $max;
    protected $template;

    /**
     * Init variables
     */
    public function __construct($data, $view, $max, $min = 1)
    {
        parent::__construct();

        $this->view = $view;
        $this->max = $max;
        $this->min = $min;
        $this->template = 'plan_form_stage';
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
    public function init($callback)
    {
        //if no stages in session
        $this->session->set('stage', $this->session->get('stage') ?: $this->min);
        // default values
        $callback($this->session->get('stage'));
        $html = '';
        for ($i = 1, $num = $this->session->get('stage'); $i <= $num; ++$i) {
            $html .= $this->view->block($this->template, ['plan.form.stageID' => $i]);
        }

        $data = [
            'stage'    => $this->session->get('stage'),
            'maxStage' => $this->max,
            'html'     => $html
        ];

        $this->sendJSON($data);
    }

    /**
     * Handle stage number and echo html data
     */
    public function add($callback)
    {
        $data = ['limit' => $this->session->get('stage')];

        if ($this->isNotMax()) {
            $this->session->set('stage', $this->session->get('stage') + 1);
            // default values
            $callback($this->session->get('stage'));
            $html = $this->view->block($this->template, ['plan.form.stageID' => $this->session->get('stage')]);

            $data = [
                'html'     => $html,
                'stage'    => $this->session->get('stage'),
                'maxStage' => $this->max
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
                'stage'    => $this->session->get('stage'),
                'maxStage' => $this->max
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
        return $this->session->get('stage') > 1;
    }

    /**
     * Stage lesser than max
     *
     * @return bool
     */
    protected function isNotMax()
    {
        return $this->session->get('stage') < $this->max;
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

//        ob_start();
//        header('Content-Type: application/json');
        echo $json;
        //echo ob_get_clean();
    }
}
