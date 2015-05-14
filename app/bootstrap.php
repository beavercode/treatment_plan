<?php
namespace UTI;

require 'vendor/autoload.php';

use UTI\Core\Router;
use UTI\Core\AppException;
use UTI\Core\System;

ini_set('display_errors', 1);   //disable for prod
ini_set('short_open_tag', 1);

try {
    define('APP_DIR', __DIR__ . '/src/');

    System::loadConf(APP_DIR . 'config.php');
    define('APP_ENV', System::getConfig('app.env'));
    define('URI_BASE', System::getConfig('app.uri_base'));
    define('APP_TPL', APP_DIR . System::getConfig('app.tpl'));
    define('APP_SES', APP_DIR . System::getConfig('app.session.dir'));
    define('APP_SES_DUR', System::getConfig('app.session.duration'));
    define('APP_LOG', APP_DIR . System::getConfig('app.log'));
    define('APP_PDF_IN', APP_DIR . System::getConfig('app.pdf_in'));
    define('APP_PDF_OUT', APP_DIR . System::getConfig('app.pdf_out'));
    define('APP_DOCX', APP_DIR . System::getConfig('app.docx'));
    define('HTML_TYPE', System::getConfig('app.html'));

    $router = new Router($_SERVER, URI_BASE);
    $router->run();
} catch (AppException $e) {
    if (APP_ENV === 'prod') {
        System::log(APP_LOG . 'exceptions.log', $e->getError());
    } else {
        echo $e->getError();
    }
} catch (\Exception $e) {
    die('Exception fired, really?!');
}
