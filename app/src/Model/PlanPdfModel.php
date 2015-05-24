<?php

namespace UTI\Model;

use UTI\Core\Model;

/**
 * Plan PDF handling
 *
 * Class FormStages
 * @package UTI\Lib
 */
class PlanPdfModel extends Model
{
    protected $formData;

    /**
     * @param $formData
     */
    public function __construct($formData)
    {
        parent::__construct();

        $this->formData = $formData;
    }

    public function htmlToPdf()
    {

    }

    public function mergePdf()
    {

    }

    public function getPdfName()
    {
        //todo
        return md5(time());
    }

    public function getPdf($name)
    {
        return $name;
    }
}
