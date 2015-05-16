<?php
/** @var \UTI\Lib\Data $data */

/** @var \UTI\Lib\Form $form*/
$form = $data('login.form');

?>
<div class="container login">
    <?php if ($errors = $form->isInvalid()): ?>
        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger login__alert" role="alert"><?= $error ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <form class="login__form" action="" name="<?= $form->getName() ?>" method="post">
        <h2 class="login__heading">Авторизация</h2>
        <label for="inputLogin" class="sr-only">Логин</label>
        <input type="text" id="inputLogin" name="<?= $form->getName() ?>[login]" class="login__control"
               value="<?= $form->getValue('login') ?>" placeholder="Логин" autofocus>
        <label for="inputPassword" class="sr-only">Пароль</label>
        <input type="password" id="inputPassword" name="<?= $form->getName() ?>[password]" class="login__control"
               value="<?= $form->getValue('password') ?>" placeholder="Пароль">

        <button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
    </form>
</div>