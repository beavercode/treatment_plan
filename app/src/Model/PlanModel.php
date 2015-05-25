<?php

namespace UTI\Model;

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
            if ($this->session->get('stage') && ! $this->isFormProcessed($form->getName())) {
                $realMinStages = $this->session->get('stage');
            }
            //form default for stages template
            $form->load($this->session->get($form->getName()));

            //todo view generation outside of the model
            $stages = new PlanStagesModel($data, $view, $maxStages, $realMinStages);
            // default values for stages
            $stages->$event(function ($stage) use ($form) {
                //default period
                $stageMSG = [
                    1 => 'Отбеливание',
                    2 => 'Ортодонтия',
                    3 => 'Имплантация'
                ];
                //default period
                $periodMSG = [
                    1 => 'First period',
                    2 => '2nddd',
                    3 => 'one more period'
                ];
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
     * @param $name
     * @return bool
     */
    public function isFormProcessed($name)
    {
        return $this->session->get('form_processed') && in_array($name, $this->session->get('form_processed'), true);
    }

    /**
     * @param Form $form
     * @return string
     */
    public function processPdf(Form $form)
    {
        $pdf = new PlanPdfModel($this->session->get($form->getName()));

        //proccess pdf

        return $pdf->getPdfName();
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
