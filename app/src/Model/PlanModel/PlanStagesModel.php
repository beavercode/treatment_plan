<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Model\PlanModel;

use UTI\Core\AbstractModel;
use UTI\Core\Exceptions\ViewException;
use UTI\Core\View;
use UTI\Model\PlanModel\Exceptions\PlanStagesModelException;

/**
 * Plan form stages handling.
 *
 * @package UTI
 */
class PlanStagesModel extends AbstractModel
{
    /**
     * @var View View object used to generate html
     */
    protected $view;

    /**
     * @var int Minimum stages
     */
    protected $stageMin;

    /**
     * @var int Maximum stages
     */
    protected $stageMax;

    /**
     * @var string Template to load
     */
    protected $template;

    /**
     * Set parameters.
     *
     * todo (!) Temporary decision (!)
     *
     * @param View $view View object used to generate html
     * @param int  $max Maximum stages
     * @param int  $min Minimum stages
     */
    public function setParameters($view, $max, $min = 1)
    {
        $this->view = $view;
        $this->stageMin = $min;
        $this->stageMax = $max;

        //todo Move to config???
        $this->template = 'plan_form_stage';
    }

    /**
     * Triggered when invoking inaccessible methods.
     *
     * @param $methodName
     * @param $args
     *
     * @throws PlanStagesModelException
     */
    public function __call($methodName, $args)
    {
        if (!method_exists($this, $methodName)) {
            throw new PlanStagesModelException(sprintf('Method "%s" not exists', $methodName));
        }

        return $this->$methodName($args);
    }

    /**
     * Init stage value with min.
     *
     * @param $callback
     *
     * @throws PlanStagesModelException
     */
    public function init($callback)
    {
        try {
            // Default values.
            $callback($this->session->get('stage'));
            $html = '';
            for ($i = 1, $num = $this->session->get('stage'); $i <= $num; ++$i) {
                $html .= $this->view->block($this->template, ['plan.form.stageID' => $i]);
            }

            $data = [
                'stage'    => $this->session->get('stage'),
                'maxStage' => $this->stageMax,
                'html'     => $html
            ];

            $this->sendJSON($data);
        } catch (ViewException $e) {
            throw new PlanStagesModelException($e->getMessage(), null, $e);
        }
    }

    /**
     * Handle stage number and echo html data.
     *
     * @param $callback
     *
     * @throws PlanStagesModelException
     */
    public function add($callback)
    {
        try {
            $data = ['limit' => $this->session->get('stage')];

            if ($this->isNotMax()) {
                $this->session->set('stage', $this->session->get('stage') + 1);
                // default values
                $callback($this->session->get('stage'));
                $html = $this->view->block($this->template, ['plan.form.stageID' => $this->session->get('stage')]);

                $data = [
                    'html'     => $html,
                    'stage'    => $this->session->get('stage'),
                    'maxStage' => $this->stageMax
                ];
            }
            $this->sendJSON($data);
        } catch (ViewException $e) {
            throw new PlanStagesModelException($e->getMessage(), null, $e);
        }
    }

    /**
     * Handle stage number.
     */
    public function delete()
    {
        $data = ['limit' => $this->session->get('stage')];

        if ($this->isNotMin()) {
            $data = [
                'stage'    => $this->session->get('stage'),
                'maxStage' => $this->stageMax
            ];
            $this->session->set('stage', $this->session->get('stage') - 1);
        }

        $this->sendJSON($data);
    }

    /**
     * Stage greater than min.
     *
     * @return bool
     */
    protected function isNotMin()
    {
        return $this->session->get('stage') > 1;
    }

    /**
     * Stage lesser than max.
     *
     * @return bool
     */
    protected function isNotMax()
    {
        return $this->session->get('stage') < $this->stageMax;
    }

    /**
     * Send data as json.
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
