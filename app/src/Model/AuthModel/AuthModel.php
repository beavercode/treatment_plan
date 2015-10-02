<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Model\AuthModel;

use UTI\Core\AbstractModel;
use UTI\Lib\Config\ConfigData;
use UTI\Lib\Form\Form;

/**
 * Class AuthModel used for user authentication and authorisation.
 *
 * @package UTI
 */
class AuthModel extends AbstractModel
{
    /**
     * Uses parent ctor.
     *
     * @inheritdoc
     */
    public function __construct(ConfigData $conf)
    {
        parent::__construct($conf);
        //todo Make real authentication and authorisation
    }

    /**
     * Process form and set flag auth=in(logged in) if all is OK
     * Otherwise set error message for field
     *
     * todo Need secure mechanism with ACL for authentication and authorisation.
     *
     * @return Form
     */
    public function processForm()
    {
        $form = new Form('form_login');
        $userInfo = $this->getLoginDataFromDB();

        if ($form->isSubmit()) {
            // Login check.
            if (!$form->getValue('login')) {
                $form->setInvalid('login', 'Введите "Логин", пожалуйста.');
                //todo login validation
            } elseif ($form->getValue('login') !== $userInfo['login']) {
                $form->setInvalid('login', 'Введенный "Логин" неправильный.');
            }
            // Password check.
            if (!$form->getValue('password')) {
                $form->setInvalid('password', 'Введите "Пароль", пожалуйста.');
                //todo pass validation
            } elseif ((int)$form->getValue('password') !== $userInfo['password']) {
                $form->setInvalid('password', 'Введенный "Пароль" неправильный.');
            }
            //no errors there
            if (!$form->isInvalid()) {
                $this->session->set('auth', 'in');
            }
        } else {
            //default values
            $form->setValue('login', $userInfo['login']);
            $form->setValue('password', $userInfo['password']);
        }

        return $form;
    }

    /**
     * DB stub, get user data.
     *
     * todo Need secure mechanism with ACL for authentication and authorisation.
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
