<?php
namespace UTI\Model;

use UTI\Core\Model;
use UTI\Lib\FormStages;

class PlanModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function processForm()
    {
    }

    /**
     * Handle form stages with ajax
     * $minStages !== $maxStages
     *
     * @param $action
     * @param $rawHtml   string
     * @param $maxStages int
     * @param $minStages int
     */
    public function processFormStages($action, $rawHtml, $maxStages, $minStages)
    {
        //todo security check through $_SERVER['HTTP_REFERER']

        $stage = new FormStages($this->session, $rawHtml, $maxStages, $minStages);
        $stage->$action();
    }


    // ------------------ STUBS ------------------

    /**
     * Get form stages from DB
     * @state stub
     *
     * @return array
     */
    public function getFormStages()
    {
        //todo get from DB, using stub for now
        return [
            1 => 'Имплантация',
            10 => 'Ортодонтия',
            25 => 'Отбеливание'
        ];
    }

    /**
     * Get form stages from DB
     * @state stub
     *
     * @return array
     */
    public function getFormDoctors()
    {
        //todo get from DB, using stub for now
        return [
            5  => 'Катаева В. Р.',
            24 => 'Воронин М. В.'
        ];
    }
}
