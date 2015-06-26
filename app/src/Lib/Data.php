<?php

namespace UTI\Lib;

use UTI\Core\AppException;

/**
 * Process data in views
 *
 * todo this class using for storing and process data before put it in view template
 * functions to do:
 *  sanitize: htmlspecialchars(), htmlentities()
 *  loops: foreach
 *  etc.
 *
 * Class Data
 * @package UTI\Lib
 */
class Data
{
    /**
     * @var array Storage
     */
    protected $data = [];

    /**
     * @var mixed Stores temporary value for fluent interface
     */
    protected $temp;

    /**
     * @var string Charset encoding
     */
    protected $charset;

    /**
     * Set view and base uri
     *
     * @param string $charset Set character encoding
     */
    public function __construct($charset = 'UTF-8')
    {
        $this->charset = $charset;
    }

    /**
     * Set and get values
     *
     * @param null|string $key
     * @param mixed       $value
     * @return mixed
     */
    //todo real string 'lvl1.lvl2.lvl3' to array and back conversion
    public function __invoke($key = null, $value = null)
    {
        // set, overwrite existing value
        if (null !== $value && null !== $key) {
            return $this->set($key, $value);
        }
        // get
        if (isset($this->data[$key])) {
            return $this->get($key);
        }

        //get repo array
        return $this->data;
    }

    /**
     * Return $this->temp for fluent interface
     *
     * @return string
     */
    public function __toString()
    {
        //todo if an element is array
        return (string)$this->temp;
    }

    //todo real string 'lvl1.lvl2.lvl3' to array and back conversion
    private function get($key)
    {
        return $this->data[$key];
    }

    //todo real string 'lvl1.lvl2.lvl3' to array and back conversion
    private function set($key, $value)
    {
        if (null === $key) {
            throw new AppException('Key can not be a null!');
        }

        return $this->data[$key] = $value;
    }

    /** EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE
     *  EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE EXAMPLE
     *
     *
     * Using array_reduce function (no user loops)
     *
     * This function is named fold in functional programming languages such as
     * lisp, ocaml, haskell, and erlang. Python just calls it reduce.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
//    public static function getConfig($key, $default = null)
//    {
//        return array_reduce(
//            explode('.', $key),
//            function ($result, $item) use ($default) {
//                return array_key_exists($item, $result) ? $result[$item] : $default;
//            },
//            self::$conf
//        );
//    }

    /**
     * Convert special characters to HTML entities:
     *  & (ampersand) becomes '&amp;'
     *  " (double quote) becomes '&quot;' when ENT_NOQUOTES is not set.
     *  ' (single quote) becomes '&#039;' (or &apos;) only when ENT_QUOTES is set.
     *  < (less than) becomes '&lt;'
     *  > (greater than) becomes '&gt;'
     *
     * @param $key
     * @return null|string
     */
    public function esc($key = null)
    {
        if (isset($this->data[$key])) {
            $this->temp = htmlspecialchars($this->data[$key], ENT_QUOTES, $this->charset);
        }
        if (null !== $this->temp) {
            $this->temp = htmlspecialchars($this->temp, ENT_QUOTES, $this->charset);
        }

        return $this;
    }

    /**
     * Convert all applicable characters to HTML entities
     * Use get_html_translation_table() to see full list 'characters => entities'
     *
     * @param $key
     * @return null|string
     */
    public function escEnt($key = null)
    {
        if (isset($this->data[$key])) {
            $this->temp = htmlentities($this->data[$key], ENT_QUOTES, $this->charset);
        }
        if (null !== $this->temp) {
            $this->temp = htmlentities($this->temp, ENT_QUOTES, $this->charset);
        }

        return $this;
    }

    /**
     * Cut out string of specified length
     *
     * @param     $key
     * @param     $length
     * @param int $start
     * @return null|string
     */
    public function cut($key = null, $length = null, $start = 0)
    {
        if (isset($this->data[$key])) {
            $this->temp = mb_substr($this->data[$key], $start, $length, $this->charset);
        }
        if (null !== $this->temp) {
            $this->temp = mb_substr($this->temp, $start, $length, $this->charset);
        }

        return $this;
    }
}
