<?php
/** @var \UTI\Lib\Data $data */
?>
<!-- Plan form -->
<form class="form-horizontal form" role="form" name="plan" action="/add" method="post">
    <!-- Common info -->
    <div class="form-group col-sm-7">
        <label for="inputName" class="col-sm-4 control-label">Введите имя</label>

        <div class="col-sm-8">
            <input type="text" id="inputName" class="form-control" name="inputName"
                   placeholder="Фамилия Имя Отчество">
        </div>
    </div>
    <div class="form-group col-sm-5">
        <label for="inputDoctor" class="col-sm-4 control-label">Врач</label>

        <div class="col-sm-8">
            <select id="inputDoctor" class="selectpicker show-tick" name="inputDoctor" data-width="100%">
                <?php foreach ($data('doctors') as $doctor_key => $doctor_val): ?>
                    <option value="<?= $doctor_key ?>"><?= $doctor_val ?></option>
                <?php endforeach; ?>
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
    <div class="form-group col-sm-10 col-sm-offset-2">
        <button type="submit" class="btn btn-success btn-lg">Сохранить план лечения</button>
    </div>
    <!-- /Save plan -->
</form>
<!-- /Plan form -->