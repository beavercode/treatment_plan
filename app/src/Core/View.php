<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Core;

use UTI\Lib\Data;
use UTI\Lib\File\File;
use UTI\Lib\MinifyHTML;
use UTI\Lib\File\Exceptions\FileException;
use UTI\Core\Exceptions\ViewException;

/**
 * Used to show html with data.
 *
 * @package UTI
 */
class View
{
    /**
     * @var string Stores Path to the directory with templates
     */
    protected $dir;

    /**
     * @var string Main template name
     */
    protected $template;

    /**
     * @var Data Object to inject into loaded file
     */
    protected $data;

    /**
     * @var array Array's pairs what looks like: "blockName" => "path"
     */
    protected $blocks;

    /**
     * @var MinifyHTML Class used for minimisation of HTML
     */
    protected $compressor;

    /**
     * Init.
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
     * Set main template, view data and addition block for view.
     *
     * @param string $template Name of main template
     * @param Data   $data Object that stores view data
     * @param array  $blocks List of block which would used later
     */
    public function set($template, $data, array $blocks = [])
    {
        $this->data = $data;
        $this->blocks = $blocks;
        $this->template = $template;
    }

    /**
     * Load page template and set page blocks.
     *
     * @param array $options Additional options applied before send page
     *      'minify' - true|false Override minimisation
     *      'cache'  - true|false Override caching
     *
     * @throws ViewException
     */
    public function render(array $options = [])
    {
        // Last point where minimization can be disabled.
        $compress = isset($options['minify']) ? $options['minify'] : true;

        //todo caching

        // Load html template.
        $html = $this->load($this->template.'.php');

        // Check for minify or not.
        if (!empty($this->compressor) && $compress) {
            $html = $this->compressor->minify($html);
        }

        echo $html;
    }

    /**
     * Load block with name what is in blocks.
     *
     * @param string $name Block name that was introduces at View::set() method
     * @param array  $additionalData Add additional view data to view data
     *
     * @return string Content of the block
     *
     * @throws ViewException
     */
    public function block($name, array $additionalData = [])
    {
        $data = $this->data;
        foreach ($additionalData as $key => $val) {
            $data($key, $val);
        }

        return $this->load($name.'.php');
    }

    /**
     * Load file and inject data and view into it.
     *
     * @param string $file Path to the file
     *
     * @return string Content of the file
     *
     * @throws ViewException
     */
    protected function load($file)
    {
        $path = $this->dir.$file;
        try {
            return File::inc($path, ['data' => $this->data, 'view' => $this], true);
        } catch (FileException $e) {
            throw new ViewException($e->getMessage(), null, $e);
        }
    }
}
