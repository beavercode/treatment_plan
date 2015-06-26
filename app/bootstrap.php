<?php

namespace UTI;

require_once 'vendor/autoload.php';

use UTI\Core\Router;
use UTI\Core\AppException;
use UTI\Core\System;
use UTI\Lib\MemoryUsageInformation;

$memoryDebug = true;

//debug memory usage
if ($memoryDebug && ! isset($_POST['stage'])) {
    $memory = new MemoryUsageInformation();
// Set start
    $memory->setStart();
}

// set directives
ini_set('display_errors', 1);   //disable for prod
ini_set('short_open_tag', 1);

//todo timelimit
//http://php.net/manual/en/function.set-time-limit.php
set_time_limit(180);

//todo memory limit
//http://php.net/manual/en/ini.core.php#ini.memory-limit
//ini_set('memory_limit', 512);

/* Set internal character encoding to UTF-8 */
mb_internal_encoding('UTF-8');

try {
    define('APP_DIR', __DIR__ . '/src/');

    System::loadConf(APP_DIR . 'config.php');

    define('APP_ENV', System::getConfig('app.env'));
    define('URI_BASE', System::getConfig('app.uri_base'));
    define('APP_TMP', APP_DIR . System::getConfig('app.tmp'));
    define('APP_TPL_VIEW', APP_DIR . System::getConfig('app.tpl.view'));
    define('APP_TPL_PDF', APP_DIR . System::getConfig('app.tpl.pdf'));
    define('APP_SES', APP_DIR . System::getConfig('app.session.dir'));
    define('APP_SES_DUR', System::getConfig('app.session.duration'));
    define('APP_LOG', APP_DIR . System::getConfig('app.log'));
    define('APP_PDF_IN', APP_DIR . System::getConfig('app.pdf_in'));
    define('APP_PDF_OUT', APP_DIR . System::getConfig('app.pdf_out'));
    define('APP_DOCX', APP_DIR . System::getConfig('app.docx'));
//    define('APP_IMG_COM', APP_DIR . System::getConfig('app.img.common'));
    define('APP_IMG_DOC', APP_DIR . System::getConfig('app.img.doctors'));
    define('HTML_TYPE', System::getConfig('app.html'));
    define('APP_STAGES_MIN', System::getConfig('app.stages.min'));
    define('APP_STAGES_MAX', System::getConfig('app.stages.max'));

    $router = new Router($_SERVER, URI_BASE);
    $router->run();
} catch (AppException $e) {
    if (APP_ENV === 'prod') {
        System::log(APP_LOG . 'exceptions.log', $e->getError());
    } else {
        echo $e->getError();
    }
} catch (\Exception $e) {
    die("External exception fired! \nMessage: {$e->getMessage()}\n, {$e->getTraceAsString()}");
}

//debug memory usage
if ($memoryDebug && ! isset($_POST['stage']) && isset($memory) && $memory instanceof MemoryUsageInformation) {
// Set end
    $memory->setEnd();
// Print memory usage statistics
    echo '<pre>';
    $memory->printMemoryUsageInformation();
    echo '</pre>';
}
