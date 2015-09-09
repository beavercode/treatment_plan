<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Lib\Config;

use UTI\Core\AppException;
use UTI\Lib\File\File;

/**
 * Works with php configs.
 *
 * @package UTI\Lib\Config
 */
class PhpConfig extends AbstractConfig
{
    /**
     * @var string Root dir for application sources
     */
    private $dir;

    /**
     * @var array Configuration assoc array
     */
    private $conf;

    /**
     * Init.
     *
     * Includes configuration array from file.
     *
     * @param string $srcDir Root dir for sources
     * @param string $dsn Absolute path to file
     *
     * @throws AppException
     */
    public function __construct($srcDir, $dsn)
    {
        $this->dir = $srcDir;
        $this->conf = File::inc($srcDir.$dsn);

        // Generate configuration class.
        $this->generate();
    }

    /**
     * Using array_reduce function (no user loops).
     *
     * This function is named fold in functional programming languages such as
     * lisp, ocaml, haskell, and erlang. Python just calls it reduce.
     *
     * @inheritdoc
     */
    public function get($key, $default = null)
    {
        return array_reduce(
            explode('.', $key),
            function ($result, $item) use ($default) {
                return isset($result[$item]) ? $result[$item] : $default;
            },
            $this->conf
        );
    }

    /**
     * @inheritdoc
     */
    protected function generate()
    {
        //todo Automatic config class generation and caching.
        // Options can be directory dependent and not, example:
        /* return [
            'app' => [
                'env'        => ['dev', null],
                'upload_dir' => ['storage/upload_dir/', true],
                    ...
        Where each element is an array with [value, directory_flag]
            - value is an option,
            - directory_flag treat this option as directory or not
         */
        //todo Rid of self::get() of no need in automatic generation?

        $config = new Config();
        $config::$APP_DIR = $this->dir;
        $config::$APP_ENV = $this->get('app.env');
        $config::$URI_BASE = $this->get('app.uri_base');
        $config::$APP_UPLOAD_DIR = $config::$APP_DIR.$this->get('app.upload_dir');
        $config::$APP_TMP = $config::$APP_DIR.$this->get('app.tmp');
        $config::$APP_TPL_VIEW = $config::$APP_DIR.$this->get('app.tpl.view');
        $config::$APP_TPL_PDF = $config::$APP_DIR.$this->get('app.tpl.pdf');
        $config::$APP_SES = $config::$APP_DIR.$this->get('app.session.dir');
        $config::$APP_SES_DUR = $this->get('app.session.duration');
        $config::$APP_LOG_EXC = $config::$APP_DIR.$this->get('app.log.exceptions');
        $config::$APP_PDF_IN = $config::$APP_DIR.$this->get('app.pdf_in');
        $config::$APP_PDF_OUT = $config::$APP_DIR.$this->get('app.pdf_out');
        $config::$APP_IMG_DOC = $config::$APP_DIR.$this->get('app.img.doctors');
        $config::$HTML_TYPE = $this->get('app.html');
        $config::$APP_STAGES_MIN = $this->get('app.stages.min');
        $config::$APP_STAGES_MAX = $this->get('app.stages.max');
        $config::$APP_RESULT = $this->get('app.result');
    }
}
