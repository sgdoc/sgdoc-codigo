<?php
/**
 * @deprecated
 */
class Pdf2Png {

    public $conn;
    public $uploadPath;
    public $img_name;
    public $pages;
    public $digital;
    public $extra_param = '';

    const IMG_TYPE = 8; // O tipo PNG será identificado com este número.
    const IMG_WIDTH = 595; // As imagens são convertidas com esta largura.
    const IMG_HEIGHT = 842; // As imagens são convertidas com esta altura.

    public function Pdf2Png(UploaderPdf $uploader) 
    {
        $controller = Controlador::getInstance();
        $this->usuario = $controller->usuario->ID;
        $this->unidade = $controller->usuario->ID_UNIDADE;

        $this->conn = $controller->getConnection()->connection;

        foreach ($uploader as $key => $property) {
            if (property_exists($this, $key)) {
                $this->$key = $property;
            }
        }

        if ($uploader->pages > 1) {
            $this->extra_param .= '%03d';
        }
    }

    /**
     *  Gera arquivos PNG 
     * 
     * @param type $isThumbnails serve para definir se os arquivos serão ou não thumbnails
     */
    public function generatePNG($isThumbnails = false) 
    {
        $this->conn->beginTransaction();

        try {

            $statmentPart = ' -type truecolor -density 200 ';
            $completeFilePath = $this->uploadPath . $this->img_name;

            if ($isThumbnails) {
                //retira a pasta pdf do caminho.
                $this->uploadPath = substr($this->uploadPath, 0, -4);
                $completeFilePath = $this->uploadPath . $this->img_name . '_thumb';
                $statmentPart = ' -resize 74x105 ';
            }

            $completePdfPath = $this->uploadPath . $this->img_name;
            $return = shell_exec("/usr/local/bin/convert -rotate '-90>' {$statmentPart} {$completePdfPath}.pdf {$completeFilePath}{$this->extra_param}.png");
            // verifica se houve erro na conversão do pdf
            $this->_errorExists($return);

            if (!$isThumbnails) {
                // Insere os dados das imagens geradas no banco
                $this->_saveImageData();
            }

            $this->conn->commit();
        } catch (Exception $e) {

            $this->conn->rollBack();
            throw $e;
        }
    }
    
    /**
     * Lança uma exceção caso encontre a palavra error
     * @todo Refatorar para utilizar tratamento em somente um local
     * 
     * 
     * @param string $statement
     * @throws Exception
     */
    private function _errorExists($statement) 
    {
        if (preg_match('/error/i', $statement)) {
            throw new Exception('Ocorreu um erro na conversão do PDF para PNG!' . $statement);
        }
    }

    /**
     * Gera um hash do conteúdo do arquivo
     * 
     * @param type $pieceFilename parte do nome do arquivo sem extensão
     * @return boolean
     */
    private function generateHashSha2ToPngFiles($pieceFilename) 
    {
        if (file_exists($pieceFilename)) {
            return hash('sha256', file_get_contents($pieceFilename));
        }
        if (file_exists($this->uploadPath . $pieceFilename . '.png')) {
            return hash('sha256', file_get_contents($this->uploadPath . $pieceFilename . '.png'));
        }
        return false;
    }

    /**
     * Retorna o valor do campo "ordem" de tb_documentos_imagem
     * serve para saber qual é o número da pŕoxima página que será 
     * inserida com esta digital
     * 
     * @param type $conn
     * @param type $digital
     * @return int
     */
    private function _getNextOrdem() 
    {
        $sttm = $this->conn->prepare("
            SELECT (MAX(ORDEM)+1) AS LAST 
            FROM TB_DOCUMENTOS_IMAGEM 
            WHERE DIGITAL = ? LIMIT 1
        ");
        $sttm->bindParam(1, $this->digital, PDO::PARAM_STR);
        $sttm->execute();

        $next = $sttm->fetch(PDO::FETCH_OBJ)->LAST;
        if (is_null($next)) {
            $next = 0;
        }

        return $next;
    }

    /**
     * Atualiza o campo "des_hash_file" de tb_documentos_imagem
     * Este campo é um hash do conteúdo do arquivo, e serve para identificar
     * o arquivo no sistema.
     * 
     */
    private function _saveImageData() 
    {
        $next = $this->_getNextOrdem();

        foreach (glob($this->uploadPath . $this->img_name . '*.png') as $k => $filename) {

            $next += (int) $k;

            if ($this->_hasUploaded($filename)) {
                // apaga o arquivo que já existe
//                @unlink($filename);
            } else {

                // criando um md5 para nomear o png gerado
                $md5 = md5($filename);
                $session = Session::get('_upload');

                rename($filename, $this->uploadPath . $md5 . '.png');

                $stmt = $this->conn->prepare("
                    INSERT INTO TB_DOCUMENTOS_IMAGEM 
                    ( DIGITAL, MD5, ORDEM, DES_HASH_FILE, IMG_WIDTH, IMG_HEIGHT, IMG_TYPE, ID_USUARIO, ID_UNIDADE, FLG_PUBLICO ) 
                    VALUES ( ?,?,?,?,?,?,?,?,?,?)
                ");

                $stmt->bindParam(1, $this->digital, PDO::PARAM_STR);
                $stmt->bindParam(2, $md5, PDO::PARAM_STR);
                $stmt->bindParam(3, $next, PDO::PARAM_INT);
                $stmt->bindParam(4, $this->generateHashSha2ToPngFiles($md5), PDO::PARAM_INT);
                $stmt->bindValue(5, self::IMG_WIDTH, PDO::PARAM_INT);
                $stmt->bindValue(6, self::IMG_HEIGHT, PDO::PARAM_INT);
                $stmt->bindValue(7, self::IMG_TYPE, PDO::PARAM_INT);
                $stmt->bindParam(8, $this->usuario, PDO::PARAM_INT);
                $stmt->bindParam(9, $this->unidade, PDO::PARAM_INT);
                $stmt->bindParam(10, $session['fg_publico'], PDO::PARAM_INT);

                $stmt->execute();
            }
        }

        // apaga o pdf que originou os png's
        //@unlink($this->uploadPath . $this->img_name . '.pdf');
        
        // retira o png da pasta pdf
        $pngPath = substr($this->uploadPath, 0, -4);
        exec("mv {$this->uploadPath}*.png {$pngPath}");
    }

    /**
     * Verifica se já foi feito o upload do arquivo no sistema.
     * @param string $file // ver propriedade md5 da classe no método construtor 
     * @return boolean
     */
    private function _hasUploaded($file) 
    {
        $hash = $this->generateHashSha2ToPngFiles($file);

        if ($hash) {
            $sttm = $this->conn->prepare("
                SELECT count(ORDEM) AS HAS FROM TB_DOCUMENTOS_IMAGEM 
                WHERE DES_HASH_FILE = ? AND DIGITAL = ? AND FLG_PUBLICO != 2
            ");
            $sttm->bindParam(1, $hash, PDO::PARAM_STR);
            $sttm->bindParam(2, $this->digital, PDO::PARAM_STR);
            $sttm->execute();

            if ($sttm->fetch(PDO::FETCH_OBJ)->HAS) {
                return true;
            }
        }

        return false;
    }

}
