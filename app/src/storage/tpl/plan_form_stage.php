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
                    name="<?= $form->getName() ?>[<?= $data('plan.form.stageID') ?>][stage]"
                    data-width="100%">
                <?php foreach ($data('plan.form.stages') as $stage_key => $stage_val): ?>
                    <option value="<?= $stage_key ?>"><?= $stage_val ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group col-sm-5">
        <label for="period<?= $data('plan.form.stageID') ?>" class="col-sm-4 control-label">Период</label>

        <div class="col-sm-8">
            <input type="text" id="period<?= $data('plan.form.stageID') ?>" class="form-control"
                   name="<?= $form->getName() ?>[<?= $data('plan.form.stageID') ?>][period]"
                   placeholder="Длительность лечения">
        </div>
    </div>
    <div class="form-group col-sm-2">
        <div class="col-sm-12">
            <!--todo: interactive upload-->
            <input type="file" id="file<?= $data('plan.form.stageID') ?>" class="col-sm-12"
                   name="<?= $form->getName() ?>[<?= $data('plan.form.stageID') ?>][file]"
                   data-filename-placement="inside" title="Прайс">
        </div>
    </div>
</div>