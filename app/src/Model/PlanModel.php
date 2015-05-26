<?php

namespace UTI\Model;

use UTI\Core\AppException;
use UTI\Core\Model;
use UTI\Lib\Form;

/**
 * Class PlanModel
 * @package UTI\Model
 */
class PlanModel extends Model
{
    /**
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
     */
    public function processForm($data, $view, $maxStages, $minStages = 1)
    {
        $form = new Form('plan_form');
        $data('plan.form', $form);
        $data('plan.form.doctors', $this->getFormDoctors());

        // forming stages with ajax
        if (isset($_POST['stage'])) {
            // get an event
            $event = $_POST['stage'];
            // get stages names
            $data('plan.form.stages', $this->getFormStages());
            // init action's specific model
            $realMinStages = $minStages;
            if ($this->session->get('stage') && ! $this->isFormProcessed($form)) {
                $realMinStages = $this->session->get('stage');
            }
            //form default for stages template
            $form->load($this->session->get($form->getName()));

            //todo view generation outside of the model
            $stages = new PlanStagesModel($data, $view, $maxStages, $realMinStages);
            // default values for stages
            $stages->$event(function ($stage) use ($form) {
                $stageMSG = [
                    1 => 'Отбеливание',
                    2 => 'Ортодонтия',
                    3 => 'Имплантация'
                ];
                $periodMSG = [
                    1 => 'First period',
                    2 => '2nddd',
                    3 => 'one more period'
                ];
                //todo situation when stage got from DB has fixed limit, e.g. 'Whitening' => '3 hours'
                if ($form->isPost()) {
                    for ($N = 1; $N <= $stage; $N++) {
                        // if there are the field value in method than no set defaults
                        if (! isset($_POST[$form->getName()]['stage' . $N])) {
                            $form->setArrayValue('stage' . $N, $this->getFormStages(), isset($stageMSG[$N]) ? $stageMSG[$N] : '');
                        }
                        if (! isset($_POST[$form->getName()]['period' . $N])) {
                            $form->setValue('period' . $N, isset($periodMSG[$N]) ? $periodMSG[$N] : $N . 'month(s)');
                        }
                    }
                }
            });

            return false;
        }

        // form sent but no stage handling
        if ($form->isSubmit()) {
            $fioLength = 5;
            $periodLength = 5;
            $fileExt = ['docx'];

            // FIO check
            if (! $form->getValue('fio') || mb_strlen($form->getValue('fio')) < $fioLength) {
                $form->setInvalid(
                    'fio',
                    'Введите "ФИО", пожалуйста. Длинна поля не менее ' . $fioLength . ' символов.'
                );
            }
            // Period check for stages
            for ($N = 1; $N <= $this->session->get('stage'); $N++) {
                if (! $form->getValue('period' . $N) || mb_strlen($form->getValue('period' . $N)) < $periodLength) {
                    $form->setInvalid(
                        'period',
                        'Введите "Период лечения", пожалуйста. Длинна поля не менее ' . $periodLength . ' символов.'
                    );
                }
            }
            // Load file(s)
            //todo handle file loading
            /*for ($N = 1; $N <= $this->session->get('stage'); $N++) {
                if (! $form->loadFile('period' . $N, $fileExt)) {
                    $form->setInvalid(
                        'file',
                        'Файл должен быть типа: "' . implode(',', $fileExt) . '"'
                    );
                }
            }*/
            // No errors there, check form as processed
            if (! $form->isInvalid()) {
                // reset form data in session
                $this->session->set('form_processed', [$form->getName()]);
            } else {
                $this->session->set('form_processed', []);
            }
        } else {
            //form default values
            $this->session->set('form_processed', []);
            $this->session->set('stage', $minStages);
            $form->setValue('fio', 'default_name');
            $form->setArrayValue('doctor', $data('plan.form.doctors'), '');
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
        return $this->session->get('form_processed') && in_array($form->getName(), $this->session->get('form_processed'), true);
    }

    /**
     *
     * 1. from data to html template
     * 2. html template to pdf
     * 3. make list of pdf files needed for result
     * 4. merge pdf files in list
     * 5. save result(one pdf file) to disk (good utilization of  disk space limiting pdf's number or overall size)
     * 6. out pdf file to browser
     *
     * @param Form $form
     * @return string
     */
    public function processPdf(Form $form)
    {
        //todo list in doc block(upper)
        $pdf = new PlanPdfModel($this->session->get($form->getName()));

        //process pdf

        return $pdf->getPdfName();
    }

    /**
     * Get pdf data and show inline or force to download
     *
     * @param        $hash
     * @param string $action
     * @return string
     */
    public function getPdfData($hash, $action = 'show')
    {
        $name = $hash . '.pdf';
        $file = APP_PDF_OUT . $name;

        if (! is_file($file) && ! is_readable($file)) {
            throw new AppException('Failed to load pdf ' . $file);
        }

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

        ob_start();
        readfile($file);

        return ob_get_clean();
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
            1  => 'Имплантация',
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
            24 => 'Воронин М. В.',
            31 => 'Павленко Я. И.'
        ];
    }
}
