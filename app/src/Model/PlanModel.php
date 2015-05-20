<?php

namespace UTI\Model;

use UTI\Core\Model;
use UTI\Lib\Form;
use UTI\Lib\FormStages;

/**
 * Class PlanModel
 * @package UTI\Model
 */
class PlanModel extends Model
{
    /**
     * Initialize form with data and handle ajax requests
     *
     * @param     $view
     * @param int $max
     * @param int $min
     * @return bool
     */
    public function ajaxStages($view, $max, $min = 1)
    {
        // process Ajax request for add / remove form stage
        if (isset($_POST['stage'])) {
            // get an event
            $event = $_POST['stage'];
            $stages = new FormStages($this->session, $view, $max, $min);
            $stages->$event();

            //todo need to flush this out!!!
            return $event;
        }

        return false;
    }

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
    public function processForm($data, $view)
    {
        //$min = $this->session->get('stage') ?: 1;
        $stagesMin = 3;
        $stagesMax = 5;
        $form = new Form('plan_form');
        $data('plan.form', $form);
        $data('plan.form.doctors', $this->getFormDoctors());
        $data('plan.form.stages', $this->getFormStages());

        // process Ajax request for add / remove form stage
        if ($this->ajaxStages($view, $stagesMax, $stagesMin)) {
            //todo nicer!
            return false;
        }

        // form sent but no stage handling
        if ($form->isSubmit()) {
            //FIO check
            $fioLength = 5;
            if (! $form->getValue('fio') || mb_strlen($form->getValue('fio')) < $fioLength = 5) {
                $form->setInvalid(
                    'fio',
                    'Введите "ФИО", пожалуйста. Длинна поля не менее ' . $fioLength . ' символов.'
                );
            }

            //Period check for stages
            //if ()


            /*if (! $form->getValue('login')) {
                $form->setInvalid('login', 'Введите "Логин", пожалуйста.');
            } elseif ($form->getValue('login') !== $userInfo['login']) {
                $form->setInvalid('login', 'Введенный "Логин" неправильный.');
            }
            //pass check
            if (! $form->getValue('password')) {
                $form->setInvalid('password', 'Введите "Пароль", пожалуйста.');
            } elseif ((int)$form->getValue('password') !== $userInfo['password']) {
                $form->setInvalid('password', 'Введенный "Пароль" неправильный.');
            }*/
            //no errors there
            if (! $form->isInvalid()) {
                $this->session->set('form', $form->getName());
                $this->session->set('stage', $stagesMin);
            }
        } else {
            $this->session->set('form', null);
            $this->session->set('stage', $stagesMin);
            //form default values
            $form->setValue('fio', 'default name');
            $form->setArrayValue('doctor', $data('plan.form.doctors'), '');

            for ($i = 1; $i <= $this->session->get('stage'); ++$i) {
                $form->setValue('period' . $i, '');
                $form->setArrayValue('stage' . $i, $data('plan.form.stages'), '');
            }
        }
    }

    public function isFormPassed()
    {
        return (bool)$this->session->get('form');
    }

    /**
     * Check if pdf is ready to retrieve
     *
     * @return bool
     */
    public function isPdfReady()
    {
        return $this->session->get('pdf');
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
