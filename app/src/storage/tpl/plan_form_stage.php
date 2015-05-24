<?php
/** @var \UTI\Lib\Data $data */
/** @var \UTI\Lib\Form $form */
$form = $data('plan.form');
?>
<div id="stage<?= $data('plan.form.stageID') ?>">
    <div class="form-group col-sm-5">
        <label for="stage<?= $data('plan.form.stageID') ?>" class="col-sm-4 control-label">Название</label>

        <div class="col-sm-8">
            <select id="stage<?= $data('plan.form.stageID') ?>" class="selectpicker show-tick"
                    name="<?= $form->getName() ?>[stage<?= $data('plan.form.stageID') ?>]"
                    data-width="100%">
                <?= $form->getArrayValue(
                    'stage' . $data('plan.form.stageID'),
                    '<option {{opt}} value="{{val}}">{{val}}</option>',
                    $data('plan.form.stages'),
                    'selected'
                ) ?>
            </select>
        </div>
    </div>
    <div class="form-group col-sm-5">
        <label for="period<?= $data('plan.form.stageID') ?>" class="col-sm-4 control-label">Период</label>

        <div class="col-sm-8">
            <input type="text" id="period<?= $data('plan.form.stageID') ?>" class="form-control"
                   name="<?= $form->getName() ?>[period<?= $data('plan.form.stageID') ?>]"
                   value="<?= $form->getValue('period' . $data('plan.form.stageID')) ?>"
                   placeholder="Длительность лечения">
        </div>
    </div>
    <div class="form-group col-sm-2">
        <div class="col-sm-12">
            <!--todo: interactive upload-->
            <input type="file" id="file<?= $data('plan.form.stageID') ?>" class="col-sm-12"
                   name="<?= $form->getName() ?>[file<?= $data('plan.form.stageID') ?>]"
                   data-filename-placement="inside" title="Прайс">
        </div>
    </div>
</div>