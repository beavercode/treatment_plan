<?php
/** @var \UTI\Lib\Form $data */
$data;
?>

<form method="post" action="" name="<?= $data->getName() ?>">
    <dl>
        <dt><label for="login">Login</label></dt>
        <dd>
            <input id="login" type="text" name="<?= $data->getName() ?>[login]" value="<?= $data->getValue('login') ?>">
            <?= $data->isInvalid('login') ?>
        </dd>
        <dt><label for="password">Password</label></dt>
        <dd>
            <input id="password" type="password" name="<?= $data->getName() ?>[password]"
                   value="<?= $data->getValue('password') ?>">
            <?= $data->isInvalid('password') ?>
        </dd>
        <dd>
            <input type="submit" value=Login>
        </dd>
    </dl>
</form>