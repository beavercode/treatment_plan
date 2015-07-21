<?php
/**
 * UTI
 */

namespace UTI\Core;

use UTI\Lib\File\File;
use UTI\Lib\MinifyHTML;

/**
 * Class View
 * @package UTI\Core
 */
class View
{
    /**
     * Stores Path to the directory with templates
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
     * Init
     *
     * @param string $dir Path to template's directory
     * @param string $compression Add compression, flags:
     *      'min' - minify HTMLs
     *      'raw' - not, default
     */
    public function __construct($dir, $compression = 'raw')
    {
        $this->dir = $dir;

        if ($compression === 'min') {
            $this->compressor = new MinifyHTML();
        }
    }

    /**
     * Set main template, view data and addition block for view
     *
     * @param string        $template Name of main template
     * @param \UTI\Lib\Data $data Object that stores view data
     * @param array         $blocks List of block which would used later
     */
    public function set($template, $data, array $blocks = [])
    {
        $this->data = $data;
        $this->blocks = $blocks;
        $this->template = $template;
    }

    /**
     * Load page template and set page blocks
     *
     * @param array $options Additional options applied before send page
     *      'minify' - true|false Override minimisation
     *      'cache'  - true|false Override caching
     * @throws AppException
     */
    public function render(array $options = [])
    {
        // last point to disable minimization
        $compress = isset($options['minify']) ? $options['minify'] : true;

        //todo caching

        $html = $this->load($this->template . '.php');
        // minify html base on setting in config.php and $options['minify']
        if (! empty($this->compressor) && $compress) {
            $html = $this->compressor->minify($html);
        }

        echo $html;
    }

    /**
     * Load block with name what is in blocks
     *
     * @param string $name Block name that was introduces at View::set() method
     * @param array  $additionalData Add additional view data to view data
     * @return string Content of the block
     * @throws AppException
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
     * Load file and inject data and view into it
     *
     * @param string $file Path to the file
     * @return string Content of the file
     * @throws AppException
     */
    protected function load($file)
    {
        $path = $this->dir . $file;

        return File::inc($path, ['data' => $this->data, 'view' => $this], true);
    }
}
