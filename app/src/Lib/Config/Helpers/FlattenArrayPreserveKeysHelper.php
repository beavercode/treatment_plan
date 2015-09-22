<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Lib\Config\Helpers;

/**
 * Used to flatten multidimensional assoc array and preserve keys.
 *
 * @package UTI\Lib\Config
 */
class FlattenArrayPreserveKeysHelper extends \RecursiveIteratorIterator
{
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
     * @param array $array Input array
     *
     * @return array Resulting array
     */
    public static function iterate(array $array)
    {
        $res = [];
        foreach ($iteration = new self(new \RecursiveArrayIterator($array)) as $val) {
            $res[implode('.', $iteration->getKeyStack())] = $val;
        }

        return $res;
    }
}

// Test.
//$array = [
//    'app' => [
//        'env'        => 'dev',
//        'uri_base'   => '/',
//        'db'         => [
//            'sqlite' => 'storage/sqlite/uti.sl3'
//        ],
//        'html'       => 'min',
//        'result'     => 'show',
//        'stages'     => [
//            'min' => 1,
//            'max' => 5
//        ],
//        'session'    => [
//            'dir'      => 'storage/sessions/',
//            'duration' => 32400,
//        ],
//        'tpl'        => [
//            'view' => 'storage/tpl/view/',
//            'pdf'  => 'storage/tpl/pdf/',
//
//        ],
//        'log'        => [
//            'exceptions' => 'storage/logs/exceptions.log'
//        ],
//        'upload_dir' => 'storage/upload_dir/',
//        'tmp'        => 'storage/tmp/',
//        'pdf_in'     => 'storage/pdf/',
//        'pdf_out'    => './../../pdf/',
//        'img'        => [
//            'doctors' => '../../doctors/',
//            /*'common'  => 'storage/img/common/'*/
//        ],
//    ]
//];
//$res = FlattenArrayPreserveKeysHelper::iterate($array);
//die;