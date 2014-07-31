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

class Uploader {

    public $session = array();
    public $size;
    public $allowSize = 1024000;
    public $type;
    public $compression;
    public $compressionQuality;
    public $resolutionUnits;
    public $resolution;
    public $allowType = 'tif';
    public $height;
    public $width;
    public $file = array();
    public $error = array();
    public $digital;
    public $public = true;
    public $hash;
    public $md5;
    public $codeType;
    public $sizeBytes;

    /**
     * @return void
     */
    public function __construct($file) {

        $this->session = Session::get('_upload');
        $this->file = (boolean) isset($file) ? $file : false;
        $this->md5 = (string) md5(microtime());
    }

    /**
     * @return Uploader
     */
    public static function factory($file) {
        return new self($file);
    }

    /**
     * @return Uploader
     */
    public function prepare() {

        if (is_array($this->session)) {
            $this->digital = (string) $this->session['digital'];
            $this->public = (boolean) $this->session['fg_publico'];
        } else {
            throw new Exception('Não foi possível recuperar os dados da sessão!');
        }

        if ($this->_isFile()) {

            $this->hash = (string) hash_file('md5', $this->file["tmp_name"]);
            $this->type = (string) substr($this->file["name"], -3);
            $this->size = (int) $this->file["size"];

            try {

                MagickReadImage($object = NewMagickWand(), $this->file["tmp_name"]);

                $this->width = MagickGetImageWidth($object);
                $this->height = MagickGetImageHeight($object);
                $this->codeType = MagickGetImageFormat($object);
                $this->sizeBytes = MagickGetImageSize($object);
                $this->compression = MagickGetImageCompression($object);
                $this->compressionQuality = MagickGetImageCompressionQuality($object);
                $this->resolution = MagickGetImageResolution($object);
                $this->resolutionUnits = MagickGetImageUnits($object);
            } catch (Exception $e) {
                throw new Exception('Ocorreu um erro!');
            }
        } else {
            throw new Exception('O arquivo está ausente!');
        }

        return $this;
    }

    /**
     * @return Uploader
     */
    public function run() {

        try {
            $this->_checkType();
            $this->_checkSize();
            $this->_createDir();
            $this->_imageExists();
            $this->_checkDpi();
            $this->_moveFile();
            $this->_persist();
        } catch (Exception $e) {
            throw $e;
        }

        return $this;
    }

    /**
     * @return boolean
     */
    private function _isFile() {
        return (!$this->file) ? false : true;
    }

    /**
     * @return boolean
     */
    private function _isDir() {
        return (is_dir(__CAM_UPLOAD__ . "/" . $this->_getRaiz() . "/" . $this->digital)) ? true : false;
    }

    /**
     * @return boolean
     */
    private function _createDir() {
        return (!$this->_isDir()) ? mkdir(__CAM_UPLOAD__ . "/" . $this->_getRaiz() . "/" . $this->digital, 0777) : false;
    }

    /**
     * @return boolean
     */
    private function _checkSize() {
        if ($this->allowSize <= $this->size) {
            throw new Exception('Arquivo maior que o permitido!');
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    private function _checkType() {
        if ($this->allowType != $this->type || $this->codeType != 'TIFF') {
            throw new Exception('Formato inválido!');
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    private function _checkLzw() {
        //throw new Exception(MW_UndefinedCompression.','.MW_NoCompression.','.MW_BZipCompression.','.MW_FaxCompression.','.MW_Group4Compression.','.MW_JPEGCompression.',');
        //throw new Exception('Debug: '. $this->compression . '='. MW_LZWCompression .'? '. $this->compressionQuality);
        if ($this->compression != MW_LZWCompression) {
            throw new Exception('Imagem deve estar comprimida com LZW!');
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    private function _checkDpi() {
        // Verifica que a imagem se encontra em 300dpi
        if ($this->resolutionUnits == MW_PixelsPerInchResolution) {
            if ($this->resolution[0] > 300 || $this->resolution[1] > 300) {
                throw new Exception('Imagem deve ser scanneada com no máximo 300ppi!');
            } else {
                if ((($this->width < $this->height) && $this->width > 2500) ||
                        (($this->width > $this->height) && $this->width > 3900) ||
                        (($this->height < $this->width) && $this->height > 2500) ||
                        (($this->height > $this->width) && $this->height > 3900)) {

                    throw new Exception('Imagem deve ser no máximo A4, com no máximo 300ppi');
                }
            }
        }
    }

    /**
     * @return string
     */
    private function _getRaiz() {
        return (string) Util::gerarRaiz($this->digital, __CAM_UPLOAD__);
    }

    /**
     * @return void
     */
    private function _moveFile() {
        if (!move_uploaded_file($this->file["tmp_name"], $this->_makeFileName())) {
            throw new Exception('Não foi possível enviar o arquivo!');
        }
        // Registrar o ultimo upload do documento...
        file_put_contents(sprintf('%s/%s/%s/last', __CAM_UPLOAD__, $this->_getRaiz(), $this->digital), microtime());
        @unlink(sprintf('%s/cache/%s/%s/last-1', __CAM_UPLOAD__, $this->_getRaiz(), $this->digital));
    }

    /**
     * @return void
     */
    private function _persist() {
        try {

            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $sttm = Controlador::getInstance()->getConnection()->connection->prepare("SELECT (MAX(ORDEM)+1) AS NEXT FROM TB_DOCUMENTOS_IMAGEM WHERE DIGITAL = ? LIMIT 1");
            $sttm->bindParam(1, $this->digital, PDO::PARAM_STR);
            $sttm->execute();

            $next = $sttm->fetch(PDO::FETCH_OBJ)->NEXT;

            if (is_null($next)) {
                $next = 0;
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_DOCUMENTOS_IMAGEM (DIGITAL,FLG_PUBLICO,MD5,DES_HASH_FILE,IMG_WIDTH,IMG_HEIGHT,IMG_TYPE,IMG_BYTES,ORDEM) VALUES (?,?,?,?,?,?,7,?,?)");

            $stmt->bindParam(1, $this->digital, PDO::PARAM_STR);
            $stmt->bindParam(2, $this->public, PDO::PARAM_INT);
            $stmt->bindParam(3, $this->md5, PDO::PARAM_STR);
            $stmt->bindParam(4, $this->hash, PDO::PARAM_STR);
            $stmt->bindParam(5, $this->width, PDO::PARAM_STR);
            $stmt->bindParam(6, $this->height, PDO::PARAM_STR);
            $stmt->bindParam(7, $this->sizeBytes, PDO::PARAM_INT);
            $stmt->bindParam(8, $next, PDO::PARAM_INT);

            $stmt->execute();

            Controlador::getInstance()->getConnection()->connection->commit();
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            throw new Exception('Não foi possível registrar a arquivo!' . $e->getMessage());
        }
    }

    /**
     * @return void
     */
    private function _imageExists() {
        try {

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT 1 FROM TB_DOCUMENTOS_IMAGEM WHERE DIGITAL = ? AND DES_HASH_FILE = ? AND FLG_PUBLICO != 2 LIMIT 1");
            $stmt->bindParam(1, $this->digital, PDO::PARAM_STR);
            $stmt->bindParam(2, $this->hash, PDO::PARAM_STR);

            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                throw new Exception('Arquivo enviado anteriormente!');
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @return string
     */
    private function _makeFileName() {
        return sprintf('%s/%s/%s/%s.tif', __CAM_UPLOAD__, $this->_getRaiz(), $this->digital, $this->md5);
    }

    /**
     * 
     */
    public static function persisteLote($digital, $ocr) {
        try {

            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $sttm = Controlador::getInstance()->getConnection()->connection->prepare("SELECT (max(ORDEM)+1) as next FROM TB_DOCUMENTOS_IMAGEM WHERE DIGITAL = ? LIMIT 1");
            $sttm->bindParam(1, $digital, PDO::PARAM_STR);
            $sttm->execute();

            $next = $sttm->fetch(PDO::FETCH_ASSOC);

            if (is_null($next['next'])) {
                $next['next'] = 0;
            }

            $caminhoLOTE = __CAM_UPLOAD__ . '/' . gerarRaiz($digital, __CAM_UPLOAD__) . '/' . $digital;

            $arquivos = glob("{$caminhoLOTE}/*.png");

            $imagem = array();
            $imagem['public'] = 1;

            $imagem['next'] = (int) $next['next'];

            $query = "INSERT INTO SGDOC.TB_DOCUMENTOS_IMAGEM (DIGITAL,FLG_PUBLICO,MD5,DES_HASH_FILE,IMG_WIDTH,IMG_HEIGHT,IMG_TYPE,ORDEM,IMG_BYTES,ID_USUARIO,ID_UNIDADE) VALUES ";

            foreach ($arquivos as $arquivo) {
                $query .= "(?,?,?,?,?,?,?,?,?,?,?),";
            }

            $query = rtrim($query, ','); // remove a vírgula extra

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($query);

            $valores = array();

            foreach ($arquivos as $filename) {
                $filename = str_replace("{$caminhoLOTE}/", '', $filename);
                // Percorre a pasta
                // Se o arquivo ainda não foi salvo, salvar
                $imagem['md5'] = (string) aleatorio();
                $imagem['old_name'] = $filename;
                $imagem['hash'] = (string) hash_file('md5', $caminhoLOTE . '/' . $filename);

                $new_filename = "{$caminhoLOTE}/{$imagem['md5']}.png";

                rename($caminhoLOTE . '/' . $filename, $new_filename);

                $information = getimagesize($new_filename);

                $imagem['width'] = $information[0];
                $imagem['height'] = $information[1];
                $imagem['codeType'] = 8;
                $imagem['size'] = filesize($new_filename);
// Fim da etapa de pegar os dados, jogar na base
                $valores[] = $digital;
                $valores[] = $imagem['public'];
                $valores[] = $imagem['md5'];
                $valores[] = $imagem['hash'];
                $valores[] = $imagem['width'];
                $valores[] = $imagem['height'];
                $valores[] = $imagem['codeType'];
                $valores[] = $imagem['next'];
                $valores[] = $imagem['size'];
                $valores[] = Controlador::getInstance()->usuario->ID;
                $valores[] = Controlador::getInstance()->usuario->ID_UNIDADE;

                $imagem['next']++;
            }

            $stmt->execute($valores);

            // Se chegou aqui com segurança, pode commitar a sequencia

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * 
     */
    public static function atualizarPosicaoPaginaDocumento($digital, $token) {

        if (strlen($digital) != 7) {
            return new Output(array('success' => 'false', 'error' => 'Ocorreu um erro ao tentar salvar a ordenação das páginas deste documento.'));
        }

        try {

            $token = explode(';', $token);

            foreach ($token as $key => $value) {

                $page = explode('|', $value);

                if ($digital && strlen($page[1]) == 32) {
                    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_IMAGEM SET ORDEM = ? WHERE DIGITAL = ? AND MD5 = ?");
                    $stmt->bindParam(1, $page[0], PDO::PARAM_INT);
                    $stmt->bindParam(2, $digital, PDO::PARAM_STR);
                    $stmt->bindParam(3, $page[1], PDO::PARAM_INT);
                    $stmt->execute();
                }
            }

            return new Output(array('success' => 'true'));
        } catch (Exception $e) {
            return new Output(array('success' => 'false', 'error' => 'Ocorreu um erro ao tentar salvar a ordenação das páginas deste documento.' . $e->getMessage()));
        }
    }

}