<?php

namespace UTI\Lib;

/**
 * Plan form stages handling
 *
 * Class FormStages
 * @package UTI\Lib
 */
class FormStages
{
    protected $session;
    protected $rawHtml;
    protected $min;
    protected $max;

    /**
     * Init variables
     * $min !== $max
     *
     * @param Session $session
     * @param string  $rawHtml
     * @param int     $max
     * @param int     $min
     */
    public function __construct($session, $rawHtml, $max = 3, $min = 1)
    {
        $this->min = $min;
        $this->max = $max;
        $this->rawHtml = $rawHtml;
        $this->session = $session;
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
        $this->session->set('stage', $this->min);

        // Process html form 1+ stages
        $html = '';
        for ($i = 1, $num = $this->session->get('stage'); $i <= $num; ++$i) {
            $html .= $this->populate($this->rawHtml, ['stage' => $i]);
        }

        $data = [
            'stages' => $this->session->get('stage'),
            'html'   => $html
        ];

        echo json_encode($data);
    }

    /**
     * Handle stage number and echo html data
     */
    public function add()
    {
        $data = ['limit' => $this->session->get('stage')];

        if ($this->isNotMax()) {
            $this->session->set('stage', $this->session->get('stage') + 1);
            $html = $this->populate($this->rawHtml, ['stage' => $this->session->get('stage')]);
            $data = [
                'html'      => $html,
                'stage'     => $this->session->get('stage'),
                'maxStages' => $this->max
            ];
        }

        echo json_encode($data);
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

        echo json_encode($data);
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
     * Parse and replace substring with values
     *
     * @param $data
     * @param $citizens
     * @return mixed|string
     */
    protected function populate($data, $citizens)
    {
        $populated = '';
        foreach ($citizens as $key => $val) {
            $populated = str_replace('{{' . $key . '}}', $val, $data);
        }

        return $populated;
    }
}
