<?php
/** @var \UTI\Lib\Data $data */
?>
<div id="stage{{stage}}">
    <div class="form-group col-sm-5">
        <label for="inputStage{{stage}}" class="col-sm-4 control-label">Название</label>

        <div class="col-sm-8">
            <select id="inputStage{{stage}}" class="selectpicker show-tick" name="inputStage{{stage}}"
                    data-width="100%">
                <?php foreach ($data('stages') as $stage_key => $stage_val): ?>
                    <option value="<?= $stage_key ?>"><?= $stage_val ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group col-sm-5">
        <label for="inputPeriod{{stage}}" class="col-sm-4 control-label">Период</label>

        <div class="col-sm-8">
            <input type="text" id="inputPeriod{{stage}}" class="form-control" name="inputPeriod{{stage}}"
                   placeholder="Длительность лечения">
        </div>
    </div>
    <div class="form-group col-sm-2">
        <div class="col-sm-12">
            <!--todo: interactive upload-->
            <input type="file" id="inputFile{{stage}}" class="file btn btn-default" name="inputFile{{stage}}">
        </div>
    </div>
</div>