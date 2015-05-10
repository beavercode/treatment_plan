<?php
/** @var \UTI\Lib\Form $data */
?>
<div class="container login">
    <?php if ($errors = $data->isInvalid()): ?>
        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger login__alert" role="alert"><?= $error ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <form class="login__form" action="" name="<?= $data->getName() ?>" method="post">
        <h2 class="login__heading">Авторизация</h2>
        <label for="inputLogin" class="sr-only">Логин</label>
        <input type="text" id="inputLogin" name="<?= $data->getName() ?>[login]" class="login__control"
               value="<?= $data->getValue('login') ?>" placeholder="Логин" autofocus>
        <label for="inputPassword" class="sr-only">Пароль</label>
        <input type="password" id="inputPassword" name="<?= $data->getName() ?>[password]" class="login__control"
               value="<?= $data->getValue('password') ?>" placeholder="Пароль">

        <button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
    </form>

</div>