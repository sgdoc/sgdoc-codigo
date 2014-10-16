<?php

/*
 * Copyright 2008 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuíção e/ou modifição dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuíção na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 * */

/**
 * @author Emmanuel de C. Garcia <emanu.ti@gmail.com>
 */
class UploaderPdf {

    public $digital;
    public $tmpName;
    public $originalName;
    public $img_name;
    public $uploadPath;
    public $pages;

    public function UploaderPdf($file) {

        $session = Session::get('_upload');
        $this->digital = $session['digital'];

        $this->_isPdf($file['type']);

        $this->originalName = $file['name'];
        $this->tmpName = $file['tmp_name'];
        $this->img_name = preg_replace('/[., ]/', '', $this->digital . '_' . microtime());

        $this->_setUploadPath();

        return $this;
    }

    /*
     * verifica se o arquivo é um pdf
     */

    private function _isPdf($type) {
        if (strpos(strtolower($type), 'pdf') === false) {
            throw new Exception('Tipo de arquivo inválido!' . $type);
        }
    }

    /**
     * Configura o caminho de destino para o arquivo
     */
    private function _setUploadPath() {
        $dir = sprintf('%s/%s/%s/pdf/', __CAM_UPLOAD__, $this->_generateLoteFolder(), $this->digital);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        $this->uploadPath = $dir;
    }

    /**
     * 
     * @return string
     */
    private function _generateLoteFolder() {
        return (string) Util::gerarRaiz($this->digital, __CAM_UPLOAD__);
    }

    /**
     * Move o PDF de UPLOAD para o caminho de armazenagem
     * 
     * @return \UploaderPdf
     * @throws Exception
     */
    public function upload() {
        if (!move_uploaded_file($this->tmpName, $this->uploadPath . $this->img_name . '.pdf')) {
            throw new Exception('Erro na tentativa de gravar o arquivo.');
        }

        $this->_extractInfo();
        return $this;
    }

    /**
     * @todo: Este processo demora muito, chegando a 1 minuto no caso de pdf com 594 páginas e 256MB
     * Extrai informações do arquivo pdf
     * @return \UploaderPdf
     */
    private function _extractInfo() 
    {        
        $this->pages = exec('/usr/local/bin/identify -format %n ' . $this->uploadPath . $this->img_name . '.pdf');        
        return $this;
    }

    /**
     * Exporta o arquivo PDF para PNG(s) em tamanho reduzido
     */
    public function exportToPNGThumbnails() 
    {
        $pdfToPng = new Pdf2Png($this);
        $pdfToPng->generatePNG(true);
    }

    /**
     * Exporta o arquivo PDF para PNG(s)
     */
    public function exportToPNG() 
    {
        $pdfToPng = new Pdf2Png($this);
        $pdfToPng->generatePNG();
    }

}
