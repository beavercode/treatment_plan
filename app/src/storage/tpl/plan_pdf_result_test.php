<?php
/** @var \UTI\Lib\Data $data */
echo 'POST';
var_dump($_POST);
echo 'SESSION';
var_dump($_SESSION);
echo 'data[form_data]';
var_dump($data('form_data'));
echo "<a href='{$_SERVER['HTTP_REFERER']}'>{$_SERVER['HTTP_REFERER']}</a>";