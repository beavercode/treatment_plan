<?php

namespace UTI\Lib;

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
     * @var array
     */
    protected $data = [];

    /**
     * Set base uri
     *
     * @param $uriBase
     */
    public function __construct($uriBase)
    {
        $this->data['uri_base'] = $uriBase;
    }

    /**
     * Set and get values
     *
     * @param      $key
     * @param null $value
     * @return bool|null
     */
    public function __invoke($key = null, $value = null)
    {
        // get
        if ($key && isset($this->data[$key])) {
            return $this->data[$key];
        }
        // set
        if (null !== $value && ! isset($this->data[$key])) {
            $this->data[$key] = $value;

            return true;
        }

        return $this->data['uri_base'];
    }

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
    public function chars($key)
    {
        if (isset($this->data[$key])) {
            return htmlspecialchars($this->data[$key], ENT_QUOTES, 'UTF-8');
        }

        return null;
    }

    /**
     * Convert all applicable characters to HTML entities
     * Use get_html_translation_table() to see full list 'characters => entities'
     *
     * @param $key
     * @return null|string
     */
    public function entities($key)
    {
        if ($value = $this->get($key)) {
            return htmlentities($value, ENT_QUOTES, 'UTF-8');
        }

        return null;
    }

    public function fore($key, $template)
    {
    }
}