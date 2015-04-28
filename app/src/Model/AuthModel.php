<?php
namespace UTI\Model;

use UTI\Core\Model;
use UTI\Lib\Form;

class AuthModel extends Model
{
    /**
     * Process form and set flag auth=in(logged in) if all is ok
     * Otherwise form.validate populated with an errors
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
                $form->setInvalid('login', 'Field required.');
            } elseif ($form->getValue('login') !== $userInfo['login']) {
                $form->setInvalid('login', 'Wrong login value.');
            }
            //pass check
            if (! $form->getValue('password')) {
                $form->setInvalid('password', 'Field required.');
            } elseif ((int)$form->getValue('password') !== $userInfo['password']) {
                $form->setInvalid('password', 'Wrong password value.');
            }
            //no errors there
            if (! $form->isInvalid()) {
                $this->session->set('auth', 'in');
            }
        } else {
            //default value
            $form->setValue('login', 'admin');
            $form->setValue('password', '1');
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
            'password' => 1
        ];
    }
}
