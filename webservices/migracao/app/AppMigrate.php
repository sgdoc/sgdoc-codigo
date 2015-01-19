<?php

class AppMigrate {

    /**
     * @var string
     */
    private $_digital = '';

    /**
     * @var string
     */
    private $_content = NULL;

    /**
     * @var string
     */
    private $_path = '';

    /**
     * @return App
     * @param string $digital
     * @param string $path
     */
    public static function factory($digital, $path) {
        return new self($digital, $path);
    }

    /**
     * @return void
     * @param string $digital
     * @param string $path
     */
    private function __construct($digital, $path) {
        $this->_path = $path;
        $this->_digital = $digital;
        $this->_content = Documento\Imagem\DocumentoImagemFactory::factory($digital)->getPDF()->getData();
    }

    /**
     * @return App
     */
    public function run($callback) {

        $lote = Util::gerarRaiz($this->_digital, $this->_path);
        $filename = $this->_path . '/' . $lote . '/' . $this->_digital . '.pdf';

        $this->write($filename);

        $callback(array(
            'pages' => 12,
            'lote' => $lote,
            'status' => true,
            'filename' => $filename,
            'checksum' => md5(file_get_contents($filename)),
        ));
    }

    /**
     * @return App Description
     * @param string $filename
     */
    public function write($filename) {
        file_put_contents($filename, $this->_content);
        return $this;
    }

}
