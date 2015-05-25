<?php
/** @var \UTI\Lib\Data $data */
/** @var \UTI\Lib\Form $form */
$form = $data('plan.form');
?>
<!-- Plan form -->
<?php if ($errors = $form->isInvalid()):
    foreach ($errors as $error): ?>
        <div class="col-lg-10 col-lg-offset-1 alert alert-danger" role="alert">
            <button aria-label="Close" data-dismiss="alert" class="close" type="button">
                <span aria-hidden="true">×</span>
            </button>
            <?= $error ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<form class="form-horizontal form" role="form" name="<?= $form->getName() ?>" action="" method="post">
    <!-- Common info -->
    <div class="form-group col-sm-7">
        <label for="fio" class="col-sm-4 control-label">Введите имя</label>

        <div class="col-sm-8">
            <input type="text" id="fio" class="form-control" name="<?= $form->getName() ?>[fio]"
                   value="<?= $form->getValue('fio') ?>"
                   placeholder="Фамилия Имя Отчество">
        </div>
    </div>
    <div class="form-group col-sm-5">
        <label for="doctor" class="col-sm-4 control-label">Врач</label>

        <div class="col-sm-8">
            <select id="doctor" class="selectpicker show-tick" name="<?= $form->getName() ?>[doctor]" data-width="100%">
                <?= $form->getArrayValue(
                    'doctor',
                    '<option {{opt}} value="{{val}}">{{val}}</option>',
                    $data('plan.form.doctors'),
                    'selected'
                ) ?>
            </select>
        </div>
    </div>
    <!-- /Common info -->

    <!-- Title -->
    <div class="form-group col-sm-12">
        <hr/>
        <h3 class="title">Введите этапы</h3>
    </div>
    <!-- /Title -->

    <!-- Stages -->
    <div id="stages"></div>
    <!-- /Stage  -->

    <!-- Add/Remove stage -->
    <div class="form-group col-sm-10 col-sm-offset-2">
        <button type="button" id="add-stage" class="btn btn-primary btnAdd" role="button">Добавить этап</button>
        <button type="button" id="remove-stage" class="btn btn-danger btnRemove" role="button">Удалить
            этап
        </button>
    </div>
    <!-- /Add/Remove stage -->

    <!-- Save plan -->
    <div class="form-group col-sm-5 col-sm-offset-2">
        <button type="submit" class="btn btn-success btn-lg">Сохранить план лечения</button>
    </div>
    <?= $this->block('plan_form_result') ?>
    <!-- /Save plan -->
</form>
<!-- /Plan form -->
