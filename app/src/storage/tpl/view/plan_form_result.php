<?php
/** @var \UTI\Lib\Data $data */
//<div id="notify-msg" class="col-lg-10 col-lg-offset-1 alert alert-danger text-center" role="alert">
?>
<?php if ($errorNotify = $data('notify.error')): ?>
    <div id="notify-msg" class="col-sm-7 alert alert-danger text-center notify-window" role="alert">
        <?= $errorNotify ?>
    </div>
<?php endif; ?>
<?php if ($successNotify = $data('notify.success')): ?>
    <div id="notify-msg" class="col-sm-7 alert alert-success text-center notify-window" role="alert">
        <a target="_blank" href="<?= $successNotify ?>">Нажмите на текст, чтобы получить план лечения.</a>
    </div>
<?php endif; ?>