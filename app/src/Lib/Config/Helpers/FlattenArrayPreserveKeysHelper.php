<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Lib\Config\Helpers;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * Used to flatten multidimensional assoc array and preserve keys.
 *
 * @package UTI\Lib\Config
 */
class FlattenArrayPreserveKeysHelper extends RecursiveIteratorIterator
{
    /**
     * Get key name(s) as array-like string.
     *
     * @return string
     */
    public function key()
    {
        return json_encode($this->getKeyStack());
    }

    /**
     * Form list of the keys.
     *
     * @return array
     */
    public function getKeyStack()
    {
        $result = [];
        for ($depth = 0, $limit = $this->getDepth(); $depth < $limit; ++$depth) {
            $result[] = $this->getSubIterator($depth)->key();
        }
        $result[] = parent::key();

        return $result;
    }

    /**
     * Iterate through input array and form resulting array.
     *
     * @param array  $array Input array
     *
     * @param string $srcDir Source root directory
     * @param string $dirFlag Flag when prepend $srcDir to option value(make absolute path)
     *
     * @return array Resulting array
     */
    public static function iterate(array $array, $srcDir, $dirFlag = 'dir')
    {
        $res = [];
        foreach ($iteration = new self(new RecursiveArrayIterator($array)) as $key => $val) {
            if (!(false === strpos($key, $dirFlag))) {
                $val = $srcDir.$val;
            }
            $res[implode('.', $iteration->getKeyStack())] = $val;
        }

        return $res;
    }
}

// Test.
//
//$array = [
//    /* Mode in which application runs:
//        dev - show errors in browser
//        prod - log errors to a log files */
//    'env'         => 'dev',
//
//    /* Relative path to app
//        '/' = example.com/
//        'app/' = example.com/app/ */
//    'uri_base'    => '/',
//
//    'http_schema' => 'http://',
//
//    /* Get HTML as
//        raw (comments, whitespaces etc)
//        min  */
//    'html_type'   => 'min',
//
//    /* How to handle resulting pdf
//        download
//        show */
//    'pdf_result'  => 'show',
//
//    /* Session continues until browser is closed or duration expires */
//    'session'     => [
//        'duration' => 32400 // 9 hours
//    ],
//
//    /*  Minimum and maximum number of stages in the treatment plan form. Min - 1, max - 10. */
//    'stages'      => [
//        'min' => 1,
//        'max' => 5
//    ],
//
//    /* Directories */
//    'dir'         => [
//        /* Sqlite database */
//        'sqlite'  => 'storage/sqlite/uti.sl3',
//
//        /* Session's files */
//        'session' => 'storage/sessions/',
//
//        /* Templates
//            view - php pages templates
//            pdf - html template which would generate to pdf and merge after
//        */
//        'tpl'     => [
//            'view' => 'storage/tpl/view/',
//            'pdf'  => 'storage/tpl/pdf/',
//        ],
//
//        /* Application logs */
//        'log'     => [
//            'exception' => 'storage/logs/exceptions.log'
//        ],
//
//        /* Uploaded files resides here */
//        'upload'  => 'storage/upload_dir/',
//
//        /* Temporary files */
//        'tmp'     => 'storage/tmp/',
//
//        /* Pdf files
//            in - pdf file (templates) ready to merge
//            out - place for resulting pdf files.
//        */
//        'pdf'     => [
//            'in'  => 'storage/pdf/',
//            'out' => './../../pdf/',
//        ],
//
//        /* Path to system images */
//        'img'     => [
//            'doctors' => '../../doctors/',
//            /*'common'  => 'storage/img/common/'*/
//        ],
//    ],
//];
//$srcDir = '/home/bbr/v/debian_server/var3w/webdev.vag/worx/treatment_plan/app/src/';
//$res = FlattenArrayPreserveKeysHelper::iterate($array, $srcDir);
//die;