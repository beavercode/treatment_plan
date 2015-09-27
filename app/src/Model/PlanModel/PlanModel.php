<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Model\PlanModel;

use UTI\Core\AbstractModel;
use UTI\Core\Exceptions\ModelException;
use UTI\Lib\Config\ConfigData;
use UTI\Lib\Config\Exceptions\ConfigException;
use UTI\Lib\File\Exceptions\FileException;
use UTI\Lib\File\File;
use UTI\Lib\Form\Form;

/**
 * Model for plan page.
 *
 * @package UTI
 */
class PlanModel extends AbstractModel
{
    /**
     * @var string Uploaded files are store here.
     */
    protected $dirUpload;

    /**
     * @var string Directory with ready-to-print pdf files.
     */
    protected $dirPdfOut;

    /**
     * Uses parent ctor.
     *
     * @inheritdoc
     */
    public function __construct(ConfigData $conf)
    {
        parent::__construct($conf);

        try {
            $this->dirUpload = $conf->get('dir.upload');
            $this->dirPdfOut = $conf->get('dir.pdf.out');
        } catch (ConfigException $e) {
            throw new ModelException($e->getMessage(), null, $e);
        }
    }

    /**
     * Process form.
     *
     * old:
     * 1. Name check
     * 2. get doc photo and name
     * Stage
     * 3. get stage name
     * 4. get treatment period
     * 5. process price upload
     *
     * 6. insert value into user profile in html format
     * 7. convert html to pdf
     * 8. get proper pdf info pages using stage name
     * 9. concatenate pdf together
     * 10. return pdf file or link?
     *
     * todo:
     *  1. doing to much: process form, process form stage's ajax
     *  2. code not simple and hard to maintain
     *  3. when form submitted, than added new stages and pushed "refresh page" button, an error occurs because
     *     $this->session->get('stage') changed but not the $_POST
     *  4. situation when stage got from DB has period limit, e.g. 'Whitening' => '3 hours'
     *  5. way to restore saved form (especially uploaded files) into filled form
     *
     * @param \UTi\Lib\Data  $data
     * @param \UTI\Core\View $view
     * @param int            $maxStages
     * @param int            $minStages
     *
     * @return bool|Form False for stage's ajax or Form object for submitted form
     *
     * @throws ModelException
     */
    public function processForm($data, $view, $maxStages, $minStages = 1)
    {
        $form = new Form('plan_form', $this->dirUpload);
        $data('plan.form', $form);
        // Get doctor's names from DB.
        $doctors = $this->getDoctors();
        $data('plan.form.doctors', $doctors);

        // forming stages with ajax
        if (isset($_POST['stage'])) {
            // Get an ajax event.
            $event = $_POST['stage'];
            // Get stage's names from DB.
            $data('plan.form.stages', $this->getStages());
            // Previously defined form inputs values.
            $form->load($this->session->get($form->getName()));

            //todo view generation outside of the model
            $stages = new PlanStagesModel($this->conf);
            //todo Remaster mechanism instead of this quirk
            $stages->setParameters($view, $maxStages, $minStages);

            // Default values for each stage of the form.
            $stages->$event(function ($stage) use ($form) {
                $stageMSG = [
                    2 => 'Ортодонтия',
                    3 => 'Имплантация'
                ];
                $periodMSG = [
                    1 => '1 месяц',
                    2 => '2 недели'
                ];

                //todo 1. when form submitted, than added new stages and pushed "refresh page" button, an error occurs
                //todo 2. situation when stage got from DB has period limit, e.g. 'Whitening' => '3 hours'
                for ($N = 1; $N <= $stage; $N++) {
                    // if there are the field value in method than no set defaults
                    if (!isset($_POST[$form->getName()]['stage'.$N])) {
                        $form->setArrayValue(
                            'stage'.$N,
                            $this->getStages(),
                            isset($stageMSG[$N]) ? $stageMSG[$N] : ''
                        );
                    }
                    if (!isset($_POST[$form->getName()]['period'.$N])) {
                        $form->setValue(
                            'period'.$N,
                            isset($periodMSG[$N]) ? $periodMSG[$N] : $N.'month(s)'
                        );
                    }
                }
            });

            return false;
        }

        // Form data sent but no stage handling.
        if ($form->isSubmit()) {
            $fioLength = 5;
            $periodLength = 5;
            //todo move options to config.php
            $uploadOptions = [
                'ext'  => ['docx'],
                'mime' => [
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // php finfo class
                    'application/msword',   // "file --mime-type -b fName.docx" on ubuntu 12.04
                    'application/zip'       // "file --mime-type -b fName".docx on freeBsd8.2
                ],
                'size' => 52428800 //50Mb = 52428800 bytes
            ];

            // FIO check.
            if (!$form->getValue('fio') || mb_strlen($form->getValue('fio')) < $fioLength) {
                $form->setInvalid(
                    'fio',
                    'Введите "ФИО", пожалуйста. Длинна поля не менее '.$fioLength.' символов.'
                );
            }

            // Period check for stages.
            //todo form resubmit problem when adding and deleting stage after form submit, $this->session->get('stage') changed but not the $_POST
            for ($N = 1; $N <= $this->session->get('stage'); $N++) {
                //period check
                //if ((! $form->getValue('period' . $N) || mb_strlen($form->getValue('period' . $N)) < $periodLength) && isset($_POST['period' . $N]) {
                if (!$form->getValue('period'.$N) || mb_strlen($form->getValue('period'.$N)) < $periodLength) {
                    $form->setInvalid(
                        'period'.$N,
                        'Введите "Период лечения"#'.$N.', пожалуйста. Длинна поля не менее '.$periodLength.' символов.'
                    );
                }
                //file upload
                //todo save and display saved files to user in form
                if (!$form->uploadFile('file'.$N, $uploadOptions)) {
                    $form->setInvalid(
                        'file'.$N,
                        $form->fileUploadError()
                    );
                }
            }

            // No errors there, check form as processed
            if (!$form->isInvalid()) {
                // reset form data in session
                $this->session->set('form_processed', [$form->getName()]);
            } else {
                $this->session->set('form_processed', []);
            }
        } else {
            $this->session->set('form_processed', []);
            //set min stages
            $this->session->set('stage', $minStages);
            //form default values
            $form->setValue('fio', 'Арсений Петрович');
            $form->setArrayValue('doctor', $doctors, '');   //e.g. Воронин М. В.  Катаева В. Р.
        }
        $this->session->set($form->getName(), $form->save($_POST));

        return $form;
    }

    /**
     * Check if form processed.
     *
     * @param Form $form
     *
     * @return bool
     */
    public function isFormProcessed(Form $form)
    {
        return ($this->session->get('form_processed')
            && in_array($form->getName(), $this->session->get('form_processed'), true));
    }

    /**
     * Process pdf.
     *
     * 1. from form data to html template
     * 2. html template to pdf
     * 3. make list of pdf files needed for result
     * 4. merge pdf files in list
     * 5. save result(one pdf file) to disk (good utilization of  disk space limiting pdf's number or overall size)
     * 6. out pdf file to browser
     *
     * @param Form $form
     *
     * @return string
     *
     * @throws ModelException
     */
    public function processPdf(Form $form)
    {
        $pdf = new PlanPdfModel($this->conf);
        //todo Remaster mechanism instead of this quirk
        $pdf->setParameters($this);
        $formData = $this->session->get($form->getName());
        // Array of temporary files to delete.
        $toDel = [];

        // Parse uploaded files.
        //convert doc to data array
        //todo parse docx or excel to get price table data for arbitrary number of stages,
        //todo number if row in template limited by 17 rows (treatment procedures)
        //$doc = new Docx();
        //      or
        //$doc = new Excel();
        //$docData = $doc->getPriceTable($formData['fileName']);
        $docData = []; //resulting data array, empty for now.

        // Make html.
        $summaryHtml = $pdf->summaryToHtml($formData, 'pdf_summary_tpl');
        $stagePriceHtmlArray = $pdf->stagePriceToHtml($formData, $docData, 'pdf_stage-price_tpl');

        // Make pdf from html.
        // 1. Summary page html->pdf.
        $summaryPdf = $pdf->htmlToPdf($summaryHtml, md5(microtime(true)).'.pdf');
        $toDel[] = $summaryPdf;
        /*echo $this->showPdfDev($summaryPdf);die;*/ //todo debug, toDel
        // 2. Stage's pages(price, info) html->pdf.
        $stagesPdfArray = $pdf->stagePriceToPdf($formData, $stagePriceHtmlArray, $toDel);

        // Form list of pdf and merge them. @stub@ is placeholder for array-element to expand
        $pdfOutName = $pdf->mergeList([
            'title'     => 'pdf_title.pdf',
            'summary'   => $summaryPdf,
            'tooth_map' => 'pdf_tooth_map.pdf',
            '@stub@'    => $stagesPdfArray,
            'faq'       => 'pdf_faq.pdf',
            'extra'     => 'pdf_extra.pdf'
        ]);

        // Delete temporary pdf files.
        $pdf->removePdfList($toDel);

        return $pdfOutName;
    }

    /**
     * Get pdf data and show inline or force to download.
     *
     * Another way how to show pdf({@link http://mozilla.github.io/pdf.js/ })
     *
     * @param string $hash
     * @param string $action How to handle resulting pdf
     *  - show
     *  - download
     *
     * @return string Pdf data string
     *
     * @throws ModelException
     */
    public function showPdf($hash, $action = 'show')
    {
        $name = $hash.'.pdf';
        $file = $this->dirPdfOut.$name;
        $data = '';

        try {
            $data = File::read($file);
        } catch (FileException $e) {
            new ModelException(sprintf('Cant read pdf file: "%s"', $file), null, $e);
        }

        //todo re-check for proper HTTP  headers
        header('Content-type: application/pdf');
        //header('Content-Type: application/octet-stream');
        //header('Content-Description: File Transfer');
        switch ($action) {
            case 'download':
                header('Content-Disposition: attachment; filename='.urlencode($name));
                //todo proper HTTP caching
                header('Pragma: public');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private', false); //use this to open files directly
                break;
            case 'show':
            default:
                header('Content-Disposition: inline; filename="'.urlencode($name).'"');
        }
        header('Content-Length: '.filesize($file)); // provide file size
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');

        //header('Connection: close');

        return $data;
    }

    /**
     * @test
     *
     * Show pdf file in dev purposes.
     *
     * @param string $fileName
     * @param string $action
     *
     * @return bool|string
     *
     * @throws ModelException
     */
    public function showPdfDev($fileName, $action = 'show')
    {
        $name = basename($fileName);
        $file = $fileName;
        $data = '';

        try {
            $data = File::read($file);
        } catch (FileException $e) {
            new ModelException(sprintf('Cant read pdf file: "%s"', $file), null, $e);
        }
        //todo re-check for proper HTTP  headers
        header('Content-type: application/pdf');
        //header('Content-Type: application/octet-stream');
        //header('Content-Description: File Transfer');
        switch ($action) {
            case 'download':
                header('Content-Disposition: attachment; filename='.urlencode($name));
                //todo proper HTTP caching
                header('Pragma: public');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private', false); //use this to open files directly
                break;
            case 'show':
            default:
                header('Content-Disposition: inline; filename="'.urlencode($name).'"');
        }
        header('Content-Length: '.filesize($file)); // provide file size
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');

        //header('Connection: close');

        return $data;
    }


    // ------------------ STUBS ------------------

    /**
     * Get form stages from DB.
     *
     * @state stub
     *
     * @return array
     */
    public function getStages()
    {
        //todo get from DB, using stub for now
        return [
            2  => 'Имплантация',
            10 => 'Ортодонтия'
        ];
    }

    /**
     * Get stage name from DB by id.
     *
     * @stub
     *
     * @param $stageId
     *
     * @return mixed
     */
    public function getStageById($stageId)
    {
        //todo get from DB, using stub for now
        $dbResult = [
            2  => 'Имплантация',
            10 => 'Ортодонтия'];

        return isset($dbResult[$stageId]) ? $dbResult[$stageId] : null;
    }

    /**
     * Get stage's pdf for merge.
     *
     * @stub
     *
     * @param $stageId
     *
     * @return string|null
     */
    public function getStagePdfById($stageId)
    {
        //todo get from DB, using stub for now
        $dbResult = [
            2  => 'pdf_term_implantation.pdf',
            10 => 'pdf_term_orthodontics.pdf'
        ];

        return isset($dbResult[$stageId]) ? $dbResult[$stageId] : null;
    }

    /**
     * Get full list of doctors from DB.
     *
     * @stub
     *
     * @return array
     */
    public function getDoctors()
    {
        //todo get from DB, using stub for now
        return [
            5  => 'Катаева В. Р.',
            24 => 'Воронин М. В.'
        ];
    }

    /**
     * Get doctor name from db by id.
     *
     * @state stub
     *
     * @param $doctorId
     *
     * @return string|null
     */
    public function getDoctorById($doctorId)
    {
        //todo get from DB, using stub for now
        $dbResult = [
            5  => 'Катаева В. Р.',
            24 => 'Воронин М. В.'];

        return isset($dbResult[$doctorId]) ? $dbResult[$doctorId] : null;
    }

    /**
     * Get doctor photo from db by id.
     *
     * @state stub
     *
     * @param $doctorId
     *
     * @return string|null
     */
    public function getDoctorPhotoById($doctorId)
    {
        //todo get from DB, using stub for now
        $dbResult = [
            5  => 'kataeva.jpg',
            24 => 'voronin.jpg'];

        return isset($dbResult[$doctorId]) ? $dbResult[$doctorId] : null;
    }
}
