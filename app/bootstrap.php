<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI;

require('vendor/autoload.php');

use UTI\Core\Router;
use UTI\Core\AppException;
use UTI\Core\System;
use UTI\Lib\Memory\Memory;

// Set execution time limit, memory limit and internal encoding for multi byte extension.
set_time_limit(180); // http://php.net/manual/en/function.set-time-limit.php
//todo Something wrong if memory_limit set here.
//ini_set('memory_limit', 512); // http://php.net/manual/en/ini.core.php#ini.memory-limit
mb_internal_encoding('UTF-8');
try {
    // Define application directory.
    define('APP_DIR', __DIR__.'/src/');
    // Load config.php file.
    System::loadConf(APP_DIR.'config.php');
    // Get app environment.
    define('APP_ENV', System::getConfig('app.env'));
    // Show error on development and hide(log) in production.
    if (APP_ENV === 'dev') {
        ini_set('display_errors', 1);
        //todo toDel, debug, memory usage
        Memory::start([
            function () {
                // do not check for stage ajax requests
                if (! isset($_POST['stage'])) {
                    return true;
                }
            }
        ]);
    }
    // Define main app constants.
    define('URI_BASE', System::getConfig('app.uri_base'));
    define('APP_TMP', APP_DIR.System::getConfig('app.tmp'));
    define('APP_TPL_VIEW', APP_DIR.System::getConfig('app.tpl.view'));
    define('APP_TPL_PDF', APP_DIR.System::getConfig('app.tpl.pdf'));
    define('APP_SES', APP_DIR.System::getConfig('app.session.dir'));
    define('APP_SES_DUR', System::getConfig('app.session.duration'));
    define('APP_LOG', APP_DIR.System::getConfig('app.log'));
    define('APP_PDF_IN', APP_DIR.System::getConfig('app.pdf_in'));
    define('APP_PDF_OUT', APP_DIR.System::getConfig('app.pdf_out'));
    define('APP_UPLOAD_DIR', APP_DIR.System::getConfig('app.upload_dir'));
//    define('APP_IMG_COM', APP_DIR . System::getConfig('app.img.common'));
    define('APP_IMG_DOC', APP_DIR.System::getConfig('app.img.doctors'));
    define('HTML_TYPE', System::getConfig('app.html'));
    define('APP_STAGES_MIN', System::getConfig('app.stages.min'));
    define('APP_STAGES_MAX', System::getConfig('app.stages.max'));
    define('APP_RESULT', System::getConfig('app.result'));

    // Start routing.
    $router = new Router($_SERVER, URI_BASE, 'http://');
    $router->run();
} catch (AppException $e) {
    // Log error for production and show for development.
    if (APP_ENV === 'prod') {
        System::log(APP_LOG.'exceptions.log', $e->getError());
    } else {
        echo $e->getError();
    }
    // This catch block would never reached. If not - you messed up with exceptions.
} catch (\Exception $e) {
    die("Oops! Exception fired! \nMessage: {$e->getMessage()}\n, {$e->getTraceAsString()}");
}

//todo toDel, debug, memory usage
Memory::finish();
