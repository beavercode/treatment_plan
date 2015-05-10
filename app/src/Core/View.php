<?php
namespace UTI\Core;

class View
{
    /**
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
        echo ob_get_clean();
    }
}
