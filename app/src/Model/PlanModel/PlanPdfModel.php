<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Model\PlanModel;

use iio\libmergepdf\Exception as LibMergePdfException;
use iio\libmergepdf\Merger;
use UTI\Core\AbstractModel;
use UTI\Core\Exceptions\ModelException;
use UTI\Lib\Config\Exceptions\ConfigException;
use UTI\Lib\Data;
use UTI\Lib\File\Exceptions\FileException;
use UTI\Lib\File\File;

/**
 * Plan model PDF handling.
 *
 * @package UTI
 */
class PlanPdfModel extends AbstractModel
{
    /**
     * @var string Directory with html templates which would converted to pdf.
     */
    protected $dirHtml;

    /**
     * @var string Directory with pdf templates.
     */
    protected $dirPdfIn;

    /**
     * @var string Directory with ready-to-print pdf files.
     */
    protected $dirPdfOut;

    /**
     * @var string Temporary files, like html templates converted to pdf.
     */
    protected $dirTmp;

    /**
     * @var string Images of doctors, can be accessed from from outside
     */
    protected $dirImgDoc;

    /**
     * @var PlanModel Inherit from class which creates current.
     */
    protected $caller;

    /**
     * Set parameters
     *
     * todo (!) Temporary decision (!)
     *
     * @param AbstractModel $caller Caller class.
     *
     * @throws ModelException
     */
    public function setParameters($caller)
    {
        $this->caller = $caller;
        try {
            $this->dirTmp = $this->conf->get('dir.tmp');
            $this->dirHtml = $this->conf->get('dir.tpl.pdf');
            $this->dirPdfIn = $this->conf->get('dir.pdf.in');
            $this->dirPdfOut = $this->conf->get('dir.pdf.out');
            $this->dirImgDoc = $this->conf->get('dir.img.doctors');
        } catch (ConfigException $e) {
            // Catch if config option do not exists (wrong name, misspelling etc.)
            throw new ModelException($e->getMessage(), null, $e);
        }
    }

    /**
     * Load template and insert form data into for summary page.
     *
     * @param array  $formData Form data from POST
     * @param string $template HTML template for pdf generating
     *
     * @return string Generated HTML
     *
     * @throws ModelException
     */
    public function summaryToHtml($formData, $template)
    {
        $data = new Data();
        $data('customer.name', $formData['fio']);
        $data('doctor.name', $this->caller->getDoctorById($formData['doctor']));
        $data('doctor.photo', $this->dirImgDoc.$this->caller->getDoctorPhotoById($formData['doctor']));

        for ($i = 1, $s = $this->session->get('stage'); $i <= $s; $i++) {
            $data('name'.$i, $this->caller->getStageById($formData['stage'.$i]));
            $data('number'.$i, $i);
            $data('period'.$i, $formData['period'.$i]);
        }
        $data('stages.number', $i - 1);

        $html = $this->loadTpl($template, $data);

        return $html;
    }

    /**
     * Load template and insert price file data into.
     *
     * @param array  $formData
     * @param array  $price Array of prices parsed from file.
     * @param string $template HTML template for pdf generating.
     *
     * @return array
     *
     * @throws ModelException
     */
    public function stagePriceToHtml($formData, $price, $template)
    {
        $html = [];

        for ($i = 1, $s = $this->session->get('stage'); $i <= $s; $i++) {
            $data = [];
            $data['number'] = $i;
            $data['name'] = $this->caller->getStageById($formData['stage'.$i]);
            $data['period'] = $formData['period'.$i];

            //todo real doc/excel data
            $data['price'] = $price;
            //todo fixed number of row of fixed height, separate page accordingly oth their length,
            //todo name pdf pages of the same stage as: stage_num - stage_name: page_num

            $html[] = $this->loadTpl($template, $data);
        }

        return $html;
    }

    /**
     * Get html as string, convert to pdf(using mPdf) and show or save it to a file in temp dir.
     *
     * @link http://mpdf1.com/manual/index.php?tid=184
     *
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
     *
     * @return string
     *
     * @throws ModelException
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

        // Make variables from assoc array; extract($options) is slower on 20-80% than foreach
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

        $path = $this->dirTmp.$file;

        try {
            File::write($path, $content, 'w');
        } catch (FileException $e) {
            throw new ModelException($e->getError(), null, $e);
        }

        return $path;
    }

    /**
     * Delete files listed in array.
     *
     * @param array $pdf List of file to remove.
     *
     * @throws ModelException
     */
    public function removePdfList(array $pdf)
    {
        try {
            foreach ($pdf as $item) {
                File::remove($item);
            }
        } catch (FileException $e) {
            throw new ModelException($e->getError(), null, $e);
        }
    }

    /**
     * Convert html items to pdf.
     *
     * Pdf files temporary resides on hdd and be deleted later.
     *
     * @param array $formData Form items
     * @param array $htmls Items for conversion
     * @param array $toDel Files what will be deleted
     *
     * @return array List of stage_price/stage_term => converted_file.pdf
     *
     * @throws ModelException
     */
    public function stagePriceToPdf($formData, array $htmls, &$toDel)
    {
        $ret = [];
        //todo associate stage name with pdf of stage terms
        /*if prices is uploaded for each of them make pdfPricePage with corresponding terminology*/
        for ($i = 1, $s = $this->session->get('stage'); $i <= $s; ++$i) {
            $toDel[] = $testPricePage = $this->htmlToPdf($htmls[$i - 1], md5(microtime(true)).'.pdf');

            //todo generate price for each stage
            if (!($stagePdf = $this->caller->getStagePdfById($formData['stage'.$i]))) {
                continue;
            }

            $ret['stage'.$i.'_price'] = $testPricePage;
            $ret['stage'.$i.'_term'] = $stagePdf;
        }

        return $ret;
    }

    /**
     * Populate dictionary for merge.
     *
     * @link http://stackoverflow.com/a/3322641
     *
     * @param array $list Array of values for merge
     *
     * @returns string
     *
     * @throws ModelException
     */
    public function mergeList(array $list)
    {
        $mergeList = [];

        foreach ($list as $key => $value) {
            // If '@stub' are present than value is an array; each element of which are processed as separate element.
            if ('@stub@' === $key && is_array($value)) {
                foreach ($value as $k => $v) {
                    $this->preMerge($mergeList, $k, $v);
                }
                continue;
            }
            $this->preMerge($mergeList, $key, $value);
        }

        return $this->mergePdf($mergeList);
    }

    /**
     * Load HTML template for PDF.
     *
     * @param $file
     * @param $data
     *
     * @return string Data of included file
     *
     * @throws ModelException
     */
    private function loadTpl($file, $data)
    {
        $path = $this->dirHtml.$file.'.php';
        try {
            return File::inc($path, ['data' => $data], true);
        } catch (FileException $e) {
            throw new ModelException($e->getError(), null, $e);
        }
    }

    /**
     * Check file extension and make file's absolute path.
     *
     * @param $array
     * @param $key
     * @param $val
     *
     * @throws ModelException
     */
    private function preMerge(&$array, $key, $val)
    {
        // Check if file has '.pdf' extension.
        if (!preg_match("#\.pdf$#i", $val)) {
            throw new ModelException(sprintf('File "%s" not a PDF file', $val));
        }
        // Make an absolute path to store a file.
        if (basename($val) === $val) {
            $val = $this->dirPdfIn.$val;
        }
        $array[$key] = $val;
    }

    /**
     * Accepts array of pdf files, add correct paths and merge them using libmergepdf.
     *
     * Result stored in file which name generated from current timestamp.
     *
     * @param array $list List of block_name => file_name.pdf to merge
     *
     * @return string
     *
     * @throws ModelException
     */
    private function mergePdf(array $list)
    {
        try {
            $merger = new Merger();
            foreach ($list as $item) {
                $merger->addFromFile($item);
            }
            $merged = $merger->merge();
            // Generate a name
            $hash = md5(time());
            $path = $this->dirPdfOut.$hash.'.pdf';
            File::write($path, $merged, 'w');
        } catch (LibMergePdfException $e) {
            throw new ModelException(
                sprintf('Re-throw from "\iio\libmergepdf\Exception:" - %s', $e->getMessage()),
                911,
                $e
            );
        } catch (FileException $e) {
            throw new ModelException($e->getError(), null, $e);
        }

        return $hash;
    }
}
