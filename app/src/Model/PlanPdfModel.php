<?php

namespace UTI\Model;

use UTI\Core\AppException;
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
        return 'd9fdc8b5ff01b048417a2e1a4ef2edc8';
    }
}
