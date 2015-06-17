<?php

namespace UTI\Lib;

use UTI\Core\AppException;

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
     * @var array Represents re-arranged $_FILES array
     */
    protected $files;

    /**
     * @var string Files upload directory
     */
    protected $dirUpload;

    /**
     * @var string File upload error
     */
    protected $uploadError;

    /**
     * Initialize with form name
     *
     * @param string      $value Form name
     * @param string      $dirUpload File upload dir, mandatory for file uploading
     * @param null|string $method Form method, POST as default
     */
    public function __construct($value = 'form', $dirUpload = '', $method = '')
    {
        $this->name = $value;
        if ($method === 'get') {
            $this->method =& $_GET;
        }
        $this->method =& $_POST;

        if ($dirUpload && is_dir($dirUpload)) {
            $this->files = $_FILES;
            $this->dirUpload = $dirUpload;
        }
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
     * @param null|array $formData
     */
    public function load(array $formData)
    {
        if ($formData) {
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
     * Upload file and set its value to $this->method
     *
     * Codes:
     *  0 => 'There is no error, the file uploaded with success'
     *  1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini'
     *  2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form'
     *  3 => 'The uploaded file was only partially uploaded'
     *  4 => 'No file was uploaded'
     *  6 => 'Missing a temporary folder'
     *  7 => 'Failed to write file to disk.'
     *  8 => 'A PHP extension stopped the file upload.
     *
     * @param string $field Field name in $this->files array
     * @param array  $options Additional options
     * @return bool|string
     * @throws AppException
     */
    public function uploadFile($field, array $options = [])
    {
        //todo check for already uploaded files in $_POST, use them again there are no newer in $_FILES
        //1. if field exist in $_POST and there are no newer file in this field in $_FILES
        //2. save old name of file for show in popover near input[file], some kind of mapping: newFileName(hash) => old_file_name
        //3. display filename in popover and change button style for existing correct file(green button with check mark)

        //if $this->files initialized, file exists in $this->files and file was selected(file was uploaded)
        if (isset($this->files[$field]) && (null !== $this->files && $this->files[$field]['error'] !== 4)) {
            //system error
            if (in_array($this->files[$field]['error'], [3, 6, 7, 8], true)) {
                throw new AppException('File upload system error', $this->files[$field]['error']);

                //$this->uploadError = 'Системная ошибка, код: ' . $this->files[$field]['error'];
                //return false;
            }

            //generate values base on $options parameter
            $fileExt = isset($options['ext'])
                ? $options['ext']
                : null;
            $fileMime = isset($options['mime'])
                ? $options['mime']
                : null;
            $fileSize = isset($options['size']) && $options['size'] === (int)$options['size']
                ? $options['size']
                : ini_get('upload_max_filesize');

            //size check
            if (in_array($this->files[$field]['error'], [1, 2], true) || $this->files[$field]['size'] > $fileSize) {
                $this->uploadError = 'Размер файла "' . $field . '" больше чем ' .
                    sprintf('%.2f Мб', $fileSize / 1024 / 1024);

                return false;
            }

            //mime && ext checks
            $fieldExt = end(explode('.', $this->files[$field]['name']));
//            $fileInfo = new finfo(FILEINFO_MIME_TYPE);
//            $mime = $fileInfo->file($this->files[$field]['tmp_name']);
            $mime = $this->getMimeType($this->files[$field]['tmp_name']);
            if (! (count($fileExt) && in_array($fieldExt, $fileExt, true))
                || (! (count($fileMime) && in_array($mime, $fileMime, true)))
            ) {
                $this->uploadError = 'Неправильный тип файла "' . $field . '", разрешенные типы файлов: ' .
                    implode(', ', $fileExt) . '.';

                return false;
            }

            $newFileName = md5(basename($this->files[$field]['name']) . $this->files[$field]['size']) . '.' . $fieldExt;
            $uploadFile = $this->dirUpload . $newFileName;

            if (move_uploaded_file($this->files[$field]['tmp_name'], $uploadFile)) {
                //set file to $this->method
                $this->method[$this->getName()][$field] = $this->dirUpload . $newFileName;

                return [$field => [
                    $newFileName => htmlspecialchars($this->files[$field]['name'])
                ]];
            }
        }

        return true;
    }

    /**
     * Get file upload error
     *
     * @return string
     */
    public function fileUploadError()
    {
        return $this->uploadError;
    }

    //todo set default file
    public function setFileValue()
    {
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
        } else { //gets fist val of result array returned from db; really need this?
            //list(, $this->method[$this->getName()][$field]) = each($array); //return value
            list($this->method[$this->getName()][$field]) = each($array); //return key
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

    /**
     * Convert the $_FILES array to the cleaner array
     *
     * @src http://php.net/manual/en/features.file-upload.multiple.php#53240
     *
     * @param $files
     * @return array
     */
    private function reArrayFiles($files)
    {
        $newFiles = [];
        $count = count($files['name']);
        $fileKeys = array_keys($files);

        for ($i = 0; $i < $count; $i++) {
            foreach ($fileKeys as $key) {
                $newFiles[$i][$key] = $files[$key][$i];
            }
        }

        return $newFiles;
    }

    /**
     * Get MIME type of the file using shell's 'file'
     *
     * @param $fileName
     * @return string
     */
    private function getMimeType($fileName)
    {
        return trim(exec('file --mime-type -b ' . escapeshellarg($fileName)));
    }
}
