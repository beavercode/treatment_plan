<?php
namespace UTI\Model;

use UTI\Core\Model;
use UTI\Lib\Form;

class AuthModel extends Model
{
    /**
     * Process form and set flag auth=in(logged in) if all is OK
     * Otherwise set error message for field
     *
     * @return Form
     */
    public function processForm()
    {
        $form = new Form('form_login');
        $userInfo = $this->getLoginDataFromDB();

        if ($form->isSubmit()) {
            //login check
            if (! $form->getValue('login')) {
                $form->setInvalid('login', 'Введите "Логин", пожалуйста.');
            } elseif ($form->getValue('login') !== $userInfo['login']) {
                $form->setInvalid('login', 'Введенный "Логин" неправильный.');
            }
            //pass check
            if (! $form->getValue('password')) {
                $form->setInvalid('password', 'Введите "Пароль", пожалуйста.');
            } elseif ((int)$form->getValue('password') !== $userInfo['password']) {
                $form->setInvalid('password', 'Введенный "Пароль" неправильный.');
            }
            //no errors there
            if (! $form->isInvalid()) {
                $this->session->set('auth', 'in');
            }
        } else {
            //default values
            $form->setValue('login', 'admin');
            $form->setValue('password', 123);
        }

        return $form;
    }

    /**
     * Log out
     */
    public function logOut()
    {
        $this->session->halt();
    }

    /**
     * DB stub, get user data
     *
     * @return array
     */
    protected function getLoginDataFromDB()
    {
        return [
            'login'    => 'admin',
            'password' => 123
        ];
    }
}
