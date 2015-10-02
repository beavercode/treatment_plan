<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Lib\Transliterate;

/**
 * Convert cyrillic string to latin.
 *
 * @package UTI\Lib\Transliterate
 */
class Transliterate
{
    /**
     * @var string Charset used for internal functions.
     */
    protected $encoding;

    /**
     * Init.
     *
     * @param string $encoding
     */
    public function __construct($encoding = ' UTF-8')
    {
        $this->encoding = $encoding;
    }

    /**
     * String in cyrillic.
     *
     * @param $cyr
     * @return mixed|string
     */
    public function make($cyr)
    {
        $str = mb_strtolower($cyr, $this->encoding);
        $str = strtr($str, 'абвгдежзийклмнопрстуфыэі—–', 'abvgdegzijklmnoprstyfuei--');
        $str = strtr(
            $str,
            ['ё' => 'jo', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch',
             'ъ' => '', 'ь' => '', 'ю' => 'ju', 'я' => 'ja', 'ї' => 'ji', 'є' => 'je',
             '&' => '-and-', '@' => '-at-', '№' => '-num-', '«' => '', '»' => '']
        );
        // Delete start/end and multiple in-row hyphens
        $str = preg_replace_callback(
            '#^([[:punct:][:space:]]*)(.+?)([[:punct:][:space:]]*)$#',
            function ($match) {
                return preg_replace('#([[:punct:][:space:]]+)#', '-', $match[2]);
            },
            $str
        );

        return $str;
    }
}
