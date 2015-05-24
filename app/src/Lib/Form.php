<?php

namespace UTI\Lib;

/**
 * Form handling
 *
 * http://amdy.su/work-with-forms/
 * http://amdy.su/flash-message/
 * http://amdy.su/flash-message-2/
 * http://amdy.su/template-what-is/
 *
 * @package UTI\Lib
 */
class Form
{
    /**
     * @var string Form name
     */
    protected $name;
    /**
     * @var Form method
     */
    protected $method;

    /**
     * @var array Error's array
     */
    protected $validate = [];

    /**
     * Initialize with form name
     *
     * @param string      $value
     * @param null|string $method Form method, POST as default
     */
    public function __construct($value = 'form', $method = '')
    {
        $this->name = $value;
        if ($method === 'get') {
            $this->method =& $_GET;
        }
        $this->method =& $_POST;
    }

    /**
     * Get form name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Save form data from method array ($_POST, $_GET)
     * @param array $default
     * @return array|string
     */
    public function save(array $default = [])
    {
        return (isset($this->method[$this->getName()]))
            ? $this->method[$this->getName()]
            : $default;
    }

    /**
     * Load form data from SESSION to method array ($_POST, $_GET)
     *
     * @param false|array $formData
     */
    public function load($formData)
    {
        if (false !== $formData) {
            $this->method[$this->getName()] = $formData;
        }
    }

    /**
     * Get field value of the form.
     * Escaping characters to prevent XSS
     *
     * @param        $field
     * @param string $default
     * @return string
     */
    public function getValue($field, $default = '')
    {
        return (isset($this->method[$this->getName()]) && isset($this->method[$this->getName()][$field]))
            ? htmlspecialchars($this->method[$this->getName()][$field], ENT_QUOTES, 'UTF-8')
            : $default;
    }

    /**
     * Walk array get key=>val and insert them along with optional parameter(if set) into template
     *
     * @param        $field
     * @param        $template
     * @param array  $array
     * @param string $optional
     * @return string
     */
    public function getArrayValue($field, $template, array $array, $optional = '')
    {
        $res = '';
        foreach ($array as $key => $val) {
            $res .= str_replace(
                ['{{key}}', '{{val}}', '{{opt}}'],
                [
                    htmlspecialchars($key, ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($val, ENT_QUOTES, 'UTF-8'),
                    (isset($this->method[$this->getName()][$field])
                        // in_array() can not be strict, because of different result(key or value)
                        && in_array($this->method[$this->getName()][$field], [$key, $val]))
                        ? $optional
                        : ''
                ],
                $template
            );
        }

        return $res;
    }

    /**
     * Set field value of the form
     *
     * @param $field
     * @param $value
     */
    public function setValue($field, $value)
    {
        $this->method[$this->getName()][$field] = $value;
    }

    /**
     * Set field value of the form from array
     * Search in array for value and return key
     *
     * @param string $field Key of super global array $_POST
     * @param array  $array Array to search
     * @param string $value Value to search
     */
    public function setArrayValue($field, array $array, $value = '')
    {
        if ($value && ($find = array_search($value, $array, true))) {
            $this->method[$this->getName()][$field] = $find;
        }
        //gets fist val of result array returned from db; really need this?
        /* else {
            list($this->method[$this->getName()][$field]) = each($array);
        }*/
    }

    /**
     * Set form field as invalid
     *
     * @param        $field
     * @param string $message
     */
    public function setInvalid($field, $message = '')
    {
        $this->validate[$field] = $message;
    }

    /**
     * Check if validation errors exists for field or fields of form
     *
     * @param null $field
     * @return array|bool
     */
    public function isInvalid($field = null)
    {
        if ($field) {
            return array_key_exists($field, $this->validate)
                ? $this->validate[$field]
                : false;
        }

        return $this->validate;
    }

    /**
     * Is request method POST
     *
     * @return bool
     */
    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Is form submitted using proper method
     *
     * @return bool
     */
    public function isSubmit()
    {
        return isset($this->method[$this->getName()]);
    }
}
