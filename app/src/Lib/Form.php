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
    protected $name;
    protected $validate = [];

    /**
     * Initialize with form name
     *
     * @param string $value
     */
    public function __construct($value = 'form')
    {
        $this->name = $value;
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
     * Get field value of the form.
     * Escaping characters to prevent XSS
     *
     * @param        $field
     * @param string $default
     * @return string
     */
    public function getValue($field, $default = '')
    {
        return array_key_exists($field, $_POST[$this->getName()])
            ? htmlspecialchars($_POST[$this->getName()][$field], ENT_QUOTES, 'utf-8')
            : $default;
    }

    public function getArrayValue($field, $template, array $array)
    {
        $cur = '';
        foreach ($array as $key => $val) {
            str
            
        }
    }

    //todo
    protected function populate($data, array $citizens)
    {
        $populated = '';
        foreach ($citizens as $key => $val) {
            $populated = str_replace('{{' . $key . '}}', $val, $data);
        }

        return $populated;
    }

    /**
     * Set field value of the form
     *
     * @param $field
     * @param $value
     */
    public function setValue($field, $value)
    {
        $_POST[$this->getName()][$field] = $value;
    }

    /**
     * Set field value of the form from array
     * Search in array for value and return key
     *
     * @param string $field Key of super global array $_POST
     * @param array  $array Array to search
     * @param string $search Value to search
     */
    public function setArrayValue($field, array $array, $search)
    {
        if ($value = array_search($search, $array, true)) {
            $_POST[$this->getName()][$field] = $value;
        }
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
     * Check if field is invalid or if there exists a fields that need validation
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
     * Is post a request method
     *
     * @return bool
     */
    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Is form submitted using post method
     *
     * @return bool
     */
    public function isSubmit()
    {
        return isset($_POST[$this->getName()]);
    }
}
