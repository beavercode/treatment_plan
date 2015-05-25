<?php

namespace UTI\Core;

use UTI\Lib\MinifyHTML;

/**
 * Class View
 * @package UTI\Core
 */
class View
{
    /**
     * Stores absolute path to file
     * @var string
     */
    protected $dir;

    /**
     * @var string
     */
    protected $template;

    /**
     * Data to inject into loaded file
     * @var \UTI\Lib\Data
     */
    protected $data;

    /**
     * Array's pairs what looks like: "blockName" => "path"
     * @var array
     */
    protected $blocks;

    /**
     * Class used for minimisation of HTML
     * @var MinifyHTML
     */
    protected $compressor;

    /**
     * @param string $dir
     * @param string $compression Flag for minimisation:
     * 'min': minify HTMLs
     * 'raw': not, default
     */
    public function __construct($dir, $compression = 'raw')
    {
        $this->dir = $dir;

        if ($compression === 'min') {
            $this->compressor = new MinifyHTML();
        }
    }

    public function set($template, $data, array $blocks = [])
    {
        $this->data = $data;
        $this->blocks = $blocks;
        $this->template = $template;
    }

    /**
     * Load page template and set page blocks
     */
    public function render($options = [])
    {
        $compress = isset($options['minify']) ? $options['minify'] : true;
        $html = $this->load($this->template . '.php');

        //todo caching

        // minify html base on setting in config.php and $options['minify']
        if (! empty($this->compressor) && $compress) {
            $html = $this->compressor->minify($html);
        }

        echo $html;
    }

    /**
     * Load block with name what is in blocks
     *
     * @param string $name Block name
     * @param array  $additionalData
     * @return null|string
     */
    public function block($name, array $additionalData = [])
    {
        $data = $this->data;
        foreach ($additionalData as $key => $val) {
            $data($key, $val);
        }

        if (! in_array($name, $this->blocks, true)) {
            throw new AppException('No such block "' . $name . '""');
        }

        return $this->load($name . '.php');
    }

    /**
     * Load file
     *
     * @param string $file
     * @return string
     */
    protected function load($file)
    {
        $path = $this->dir . $file;

        if (! is_file($path)) {
            throw new AppException('Failed to load ' . $path);
        }
        //inject variables used in template files
        $data = $this->data;
        ob_start();
        include $path;

        return ob_get_clean();
    }

    /**
     * todo see later
     */
    protected function populate($data, array $citizens)
    {
        $populated = '';
        foreach ($citizens as $key => $val) {
            $populated = str_replace('{{' . $key . '}}', $val, $data);
        }

        return $populated;
    }
}
