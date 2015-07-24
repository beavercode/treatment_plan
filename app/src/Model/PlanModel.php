<?php

namespace UTI\Model;

use UTI\Core\AppException;
use UTI\Core\Model;
use UTI\Lib\File\File;
use UTI\Lib\Form;

/**
 * Class PlanModel
 * @package UTI\Model
 */
class PlanModel extends Model
{
    /**
     * @var string Uploaded docx files are store here
     */
    protected $dirDocx;

    /**
     * @var string Directory with ready-to-print pdf files
     */
    protected $dirPdfOut;

    /**
     * Init
     */
    public function __construct()
    {
        parent::__construct();

        $this->dirDocx = APP_DOCX;
        $this->dirPdfOut = APP_PDF_OUT;
    }

    /**
     * Process form
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
     * @return bool|Form False for stage's ajax or Form object for submitted form
     */
    public function processForm($data, $view, $maxStages, $minStages = 1)
    {
        $form = new Form('plan_form', $this->dirDocx);
        $data('plan.form', $form);
        // get doctors name from DB
        $doctors = $this->getDoctors();
        $data('plan.form.doctors', $doctors);

        // forming stages with ajax
        if (isset($_POST['stage'])) {
            // get an event
            $event = $_POST['stage'];
            // get stages names
            $data('plan.form.stages', $this->getStages());
            //form default for stages template
            $form->load($this->session->get($form->getName()));

            //todo view generation outside of the model
            $stages = new PlanStagesModel($data, $view, $maxStages);
            // default values for stages
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
                    if (! isset($_POST[$form->getName()]['stage' . $N])) {
                        $form->setArrayValue(
                            'stage' . $N,
                            $this->getStages(),
                            isset($stageMSG[$N]) ? $stageMSG[$N] : ''
                        );
                    }
                    if (! isset($_POST[$form->getName()]['period' . $N])) {
                        $form->setValue(
                            'period' . $N,
                            isset($periodMSG[$N]) ? $periodMSG[$N] : $N . 'month(s)'
                        );
                    }
                }
            });

            return false;
        }

        // form sent but no stage handling
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

            // FIO check
            if (! $form->getValue('fio') || mb_strlen($form->getValue('fio')) < $fioLength) {
                $form->setInvalid(
                    'fio',
                    'Введите "ФИО", пожалуйста. Длинна поля не менее ' . $fioLength . ' символов.'
                );
            }
            // Period check for stages
            //todo form resubmit problem when adding and deleting stage after form submit, $this->session->get('stage') changed but not the $_POST
            for ($N = 1; $N <= $this->session->get('stage'); $N++) {
                //period check
                //if ((! $form->getValue('period' . $N) || mb_strlen($form->getValue('period' . $N)) < $periodLength) && isset($_POST['period' . $N]) {
                if (! $form->getValue('period' . $N) || mb_strlen($form->getValue('period' . $N)) < $periodLength) {
                    $form->setInvalid(
                        'period' . $N,
                        'Введите "Период лечения"#' . $N . ', пожалуйста. Длинна поля не менее ' . $periodLength . ' символов.'
                    );
                }
                //file upload
                //todo save and display saved files to user in form
                if (! $form->uploadFile('file' . $N, $uploadOptions)) {
                    $form->setInvalid(
                        'file' . $N,
                        $form->fileUploadError()
                    );
                }
            }

            // No errors there, check form as processed
            if (! $form->isInvalid()) {
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
     * Check if form processed
     *
     * @param Form $form
     * @return bool
     */
    public function isFormProcessed(Form $form)
    {
        return ($this->session->get('form_processed')
            && in_array($form->getName(), $this->session->get('form_processed'), true));
    }

    /**
     * Process pdf
     *
     * 1. from form data to html template
     * 2. html template to pdf
     * 3. make list of pdf files needed for result
     * 4. merge pdf files in list
     * 5. save result(one pdf file) to disk (good utilization of  disk space limiting pdf's number or overall size)
     * 6. out pdf file to browser
     *
     * @param Form $form
     * @return string
     * @throws \iio\libmergepdf\Exception
     * @throws AppException
     */

    public function processPdf(Form $form)
    {
        $pdf = new PlanPdfModel($this);
        $formData = $this->session->get($form->getName());
        $toDel = [];

        //make html/pdf for Summary
        $summaryHtml = $pdf->summaryToHtml($formData, 'pdf_summary_tpl');
        $toDel[] = $summaryPdf = $pdf->htmlToPdf($summaryHtml, md5(microtime(true)) . '.pdf');

//        echo $this->showPdfDev($summaryPdf);die;

        // make pdf list to merge
        $pdf->pdfMergeList('title', 'pdf_title.pdf');
        $pdf->pdfMergeList('summary', $summaryPdf);
        $pdf->pdfMergeList('tooth_map', 'pdf_tooth_map.pdf');

        //convert doc to data array
        //todo parse docx or excel to get price table data for arbitrary number of stages, number if row in template limited by 17 rows (procedures)
        //$doc = new Docx();
        //$docData = $doc->getPriceTable($formData['fileName']);
        $docData = [];
        //make html for stage price (stage's price)
        //todo dynamically stage data
        $stagePriceHtmlArray = $pdf->stagePriceToHtml($formData, $docData, 'pdf_stage-price_tpl');

        //todo associate stage name with pdf of stage terms
        //$this->getStagesForMerge();
        /*if prices is uploaded for each of them make pdfPricePage with corresponding terminology*/
        for ($i = 1, $s = $this->session->get('stage'); $i <= $s; ++$i) {
            $toDel[] = $testPricePage = $pdf->htmlToPdf($stagePriceHtmlArray[$i - 1], md5(microtime(true)) . '.pdf');

            //todo generate price for each stage
            if (! ($stagePdf = $this->getStagePdfById($formData['stage' . $i]))) {
                continue;
            }
            $pdf->pdfMergeList('stage' . $i . '_price', $testPricePage);
            $pdf->pdfMergeList('stage' . $i . '_term', $stagePdf);
        }
        unset($stagePdf, $i, $s);

        //$pdf->pdfMergeList('stage1_price', $testPricePage);
        //$pdf->pdfMergeList('stage1_term', 'pdf_term_implantation.pdf');
        //$pdf->setMergeList('stage2_price', 'generated from form data');
        //$pdf->pdfMergeList('stage2_term', 'pdf_term_orthodontics.pdf');

        $pdf->pdfMergeList('faq', 'pdf_faq.pdf');
        $pdf->pdfMergeList('extra', 'pdf_extra.pdf');
        $pdfOutName = $pdf->mergePdf();

        //delete tmp pdf
        $pdf->removePdfList($toDel);

        //todo save treatment plan parts to DB for recovering later

        return $pdfOutName;
    }

    /**
     * Get pdf data and show inline or force to download
     *
     * @param string $hash
     * @param string $action
     * @return string
     * @throws AppException
     */
    public function showPdf($hash, $action = 'show')
    {
        $name = $hash . '.pdf';
        $file = $this->dirPdfOut . $name;

        $fileData = File::read($file);
        //todo re-check for proper HTTP  headers
        header('Content-type: application/pdf');
        //header('Content-Type: application/octet-stream');
        //header('Content-Description: File Transfer');
        switch ($action) {
            case 'download':
                header('Content-Disposition: attachment; filename=' . urlencode($name));
                //todo proper HTTP caching
                header('Pragma: public');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private', false); //use this to open files directly
                break;
            case 'show':
            default:
                header('Content-Disposition: inline; filename="' . urlencode($name) . '"');
        }
        header('Content-Length: ' . filesize($file)); // provide file size
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');

        //header('Connection: close');

        return $fileData;
    }

    public function showPdfDev($fileName, $action = 'show')
    {
        $name = basename($fileName);
        $file = $fileName;

        $fileData = File::read($file);
        //todo re-check for proper HTTP  headers
        header('Content-type: application/pdf');
        //header('Content-Type: application/octet-stream');
        //header('Content-Description: File Transfer');
        switch ($action) {
            case 'download':
                header('Content-Disposition: attachment; filename=' . urlencode($name));
                //todo proper HTTP caching
                header('Pragma: public');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private', false); //use this to open files directly
                break;
            case 'show':
            default:
                header('Content-Disposition: inline; filename="' . urlencode($name) . '"');
        }
        header('Content-Length: ' . filesize($file)); // provide file size
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');

        //header('Connection: close');

        return $fileData;
    }


    // ------------------ STUBS ------------------

    /**
     * Get form stages from DB
     *
     * @state stub
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
     * Get stage name from db by id
     *
     * @stub
     * @param $stageId
     * @return null
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
     * Get stage's pdf for merge
     *
     * @state stub
     * @param $stageId
     * @return null
     */
    public function getStagePdfById($stageId)
    {
        //todo get from DB, using stub for now
        $dbResult = [
            2  => 'pdf_term_implantation.pdf',
            10 => 'pdf_term_orthodontics.pdf'];

        return isset($dbResult[$stageId]) ? $dbResult[$stageId] : null;
    }

    /**
     * Get full list of doctors from DB
     *
     * @state stub
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
     * Get doctor name from db by id
     *
     * @state stub
     * @param $doctorId
     * @return array
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
     * Get doctor photo from db by id
     *
     * @state stub
     * @param $doctorId
     * @return array
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
