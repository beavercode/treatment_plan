<?php

/**
 * Model used to work with pdf
 */

namespace UTI\Model;

use iio\libmergepdf\Exception;
use iio\libmergepdf\Merger;
use UTI\Core\AppException;
use UTI\Core\Model;
use UTI\Lib\Data;
use UTI\Lib\File;

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
    protected $dirTmp;

    /**
     * @var array List of block_name => file_name.pdf to merge
     */
    protected $mergeList = [];

    /**
     * Inherit from class which creates current
     *
     * @var PlanModel
     */
    protected $caller;

    //todo Think about db!!!
    public function __construct($caller)
    {
        parent::__construct();

        $this->dirTmp = APP_TMP;
        $this->dirHtml = APP_TPL_PDF;
        $this->dirPdfIn = APP_PDF_IN;
        $this->dirPdfOut = APP_PDF_OUT;
        //$this->dirImgDoc = URI_BASE . APP_IMG_DOC; //for web
        $this->dirImgDoc = APP_IMG_DOC; //for internal usage
        $this->caller = $caller;
    }

    /**
     * Load template and insert form data into
     *
     * @param $formData
     * @param $template
     * @return string
     * @throws AppException
     */
    public function summaryToHtml($formData, $template)
    {
        $data = new Data();
        $data('customer.name', $formData['fio']);
        $data('doctor.name', $this->caller->getDoctorById($formData['doctor']));
        $data('doctor.photo', $this->dirImgDoc . $this->caller->getDoctorPhotoById($formData['doctor']));

        for ($i = 1, $s = $this->session->get('stage'); $i <= $s; $i++) {
            $data('name' . $i, $this->caller->getStageById($formData['stage' . $i]));
            $data('number' . $i, $i);
            $data('period' . $i, $formData['period' . $i]);
        }
        $data('stages.number', $i - 1);

        $html = $this->loadTpl($template, $data);

        return $html;
    }

    // Load template and insert price file data into
    public function stagePriceToHtml($formData, $docData, $template)
    {
        $html = [];

        for ($i = 1, $s = $this->session->get('stage'); $i <= $s; $i++) {
            $data = [];
            $data['number'] = $i;
            $data['name'] = $this->caller->getStageById($formData['stage' . $i]);
            $data['period'] = $formData['period' . $i];

            //todo real doc/excel data
            $data['price'] = $docData;
            //todo fixed number of row of fixed height, separate page accordingly oth their length,
            //todo name pdf pages of the same stage as: stage_num - stage_name: page_num

            $html[] = $this->loadTpl($template, $data);
        }

        return $html;
    }

    // works with css very bad, use mPdf instead
    public function domPdfHtmlToPdf($html)
    {
        //@src http://pxd.me/dompdf/www/setup.php
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        define('DOMPDF_ENABLE_CSS_FLOAT', true);    // 	Enable CSS float support (experimental)
        define('DOMPDF_ENABLE_HTML5PARSER', true);  // 	Enable the HTML5 parser (experimental)
        require_once APP_DIR . '../vendor/dompdf/dompdf/dompdf_config.inc.php';

        //  PDF settings
        $paperSize = 'A4';
        $orientation = 'portrait';

        //  Create PDF
        $domPdf = new \DOMPDF();
        $domPdf->set_paper(strtolower($paperSize), $orientation);

//        $html =
//            '<html><body>'.
//            '<p>Put your html here, or generate it with your favourite '.
//            'templating system.</p>'.
//            '</body></html>';

        $domPdf->load_html($html);
        $domPdf->render();
        //todo save pdf to file tmp file, which should delete after all operations
        $domPdf->stream('sample.pdf', ['Attachment' => 0]);
    }

    /**
     * Get html as string, convert to pdf(using mPdf) and show or save it to a file in temp dir
     *
     * @src http://mpdf1.com/manual/index.php?tid=184
     * @param string $html HTML with css
     * @param null   $file File save to
     * @param array  $options Options for mPdf
     *      '',    This parameter specifies the mode of the new document, default ''
     *      '',    Pre-defined page size or as an array of width and height in millimetres, default 'A4'ault ''
     *      0,     Document font size in points(pt), if set to 0 uses the default value set in defaultCSS
     *      '',    Font-family for the document, if '' than uses default value set in defaultCSS
     *      15,    Left margin for the document, value should be specified in millimetres, default 15
     *      15,    Right margin for the document, value should be specified in millimetres, default 15
     *      16,    Top margin for the document, value should be specified in millimetres, default 16
     *      16,    Bottom margin for the document, value should be specified in millimetres, default 16
     *       9,    Header margin for the document, value should be specified in millimetres, default 9
     *       9,    Footer margin for the document, value should be specified in millimetres, default 9
     *      'L'    This attribute specifies the default page orientation of the new document if format is defined as
     *             an array. This value will be ignored if format is a string value. Default 'P'
     * @return string
     */
    public function htmlToPdf($html, $file = null, array $options = [])
    {
        $defaults = [
            'mode'        => '',
            'pageFormat'  => 'A4',
            'fontSize'    => 0,
            'fontFamily'  => '',
            'marLeft'     => 0,
            'marRight'    => 0,
            'marTop'      => 17,
            'marBottom'   => 5,
            'marHeader'   => 0,
            'marFooter'   => 0,
            'orientation' => 'P'
        ];
        $options = array_merge($defaults, $options);
        extract($options); //slower 20-80% that foreach
        foreach ($options as $varName => $value) {
            $$varName = $value;
        }
        /**
         * @var string $mode
         * @var string $pageFormat
         * @var int    $fontSize
         * @var string $fontFamily
         * @var int    $marLeft
         * @var int    $marRight
         * @var int    $marTop
         * @var int    $marBottom
         * @var int    $marHeader
         * @var int    $marFooter
         * @var string $orientation
         */
        $mPdf = new \mPDF($mode, $pageFormat, $fontSize, $fontFamily, $marLeft, $marRight, $marTop, $marBottom, $marHeader, $marFooter, $orientation);

        $mPdf->WriteHTML($html);
        if (null === $file) {
            $mPdf->Output();
        }
        $content = $mPdf->Output('', 'S');

        $path = $this->dirTmp . $file;
        File::write($path, $content, 'w');

        return $path;
    }

    /**
     * Accepts array of pdf files, add correct FS paths and merge them.
     * Result stored in file which name generated from current timestamp.
     *
     * @return array
     * @throws \iio\libmergepdf\Exception
     * @throws AppException
     */
    public function mergePdf()
    {
        try {
            $merger = new Merger();
            foreach ($this->mergeList as $item) {
                $merger->addFromFile($item);
            }
            $merged = $merger->merge();
        } catch (Exception $e) {
            throw new AppException($e->getMessage() . '; rethrow from "\iio\libmergepdf\Exception:"', 911, $e);
        }

        $hash = md5(time());
        $path = $this->dirPdfOut . $hash . '.pdf';
        if (! file_put_contents($path, $merged)) {
            throw new AppException('Pdf out dir not writable!');
        }

        return $hash;
    }

    /**
     * Delete files listed in array
     *
     * @param array $pdf
     * @throws AppException
     */
    public function removePdf(array $pdf)
    {
        foreach ($pdf as $item) {
            File::remove($item);
        }
    }

    /**
     * Load HTML template for PDF
     *
     * @param $file
     * @param $data
     * @return string
     * @throws AppException
     */
    private function loadTpl($file, $data)
    {
        $path = $this->dirHtml . $file;

        if (! is_file($path) && ! is_readable($path)) {
            throw new AppException('Failed to load template ' . $path);
        }
        ob_start();
        include($path);

        return ob_get_clean();
    }

    /**
     * Get list of files to merge
     *
     * @return array
     */
    public function getMergeList()
    {
        return $this->mergeList;
    }

    /**
     * Populate dictionary for merge
     * @src http://stackoverflow.com/a/3322641
     * @param $key
     * @param $value
     * @throws AppException
     */
    public function pdfMergeList($key, $value)
    {
        if (! preg_match("#\.pdf$#i", $value)) {
            throw new AppException('File "' . $value . '" not a PDF file.');
        }
        // $value is a basename of a file, e.g. is a pdf template stored in $this->dirPdfIn dikrectory
        if (basename($value) === $value) {
            $value = $this->dirPdfIn . $value;
        }

        $this->mergeList[$key] = $value;
    }
}
