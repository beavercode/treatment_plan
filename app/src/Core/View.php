<?php

namespace UTI\Core;

use UTI\Lib\MinifyHTML;

class View
{
    protected $compressor;

    public function __construct($compression = 'raw')
    {
        //todo compression level: min, comments, raw

        if ($compression === 'min') {
            $this->compressor = new MinifyHTML();
        }
    }

    /**
     * Show
     *
     * @param string $contentView виды отображающие контент страниц;
     * @param string $templateView общий для всех страниц шаблон
     * @param null   $data массив, содержащий элементы контента страницы. Обычно заполняется в модели.
     * @return string
     */
    public function render($contentView, $templateView, $data = null)
    {
        $contentView = APP_TPL . $contentView;
        $templateView = APP_TPL . $templateView;

        ob_start();
        include "$templateView";
        $html = ob_get_clean();

        //minify html if need
        if (is_object($this->compressor)) {
            $html = $this->compressor->minify($html);
        }

        echo $html;
    }

    /**
     * Load template file
     *
     * @param         $fileName
     * @param  null   $data
     * @return string
     */
    public function load($fileName, $data = null)
    {
        $file = APP_TPL . $fileName;

        ob_start();
        include $file;

        return ob_get_clean();
    }
}
