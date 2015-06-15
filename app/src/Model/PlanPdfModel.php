<?php
/**
 * One of the model parts
 */

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
    /**
     * @var array
     */
    protected $formData;

    protected $dirHtml;
    protected $dirPdfIn;
    protected $dirPdfOut;

    /**
     * @param array $formData
     */
    public function __construct(array $formData)
    {
        parent::__construct();

        $this->formData = $formData;
        $this->dirHtml = APP_TPL_PDF;
        $this->dirPdfIn = APP_PDF_IN;
        $this->dirPdfOut = APP_PDF_OUT;
    }

    public function formToHtml()
    {
    }

    public function htmlToPdf()
    {
    }

    public function mergePdf(array $pdf = [])
    {
    }

    //return hash
    public function getPdfName()
    {
        //todo
        return 'd9fdc8b5ff01b048417a2e1a4ef2edc8';
    }

    /**
     * Load HTML template for PDF
     */
    private function loadTpl($file, $tpl)
    {
        $path = $this->dirHtml . $file;

        if (! is_file($path) && ! is_readable($path)) {
            throw new AppException('Failed to load template ' . $path);
        }
        ob_start();
        include $path;

        return ob_get_clean();
    }
}
