<?php

class App extends CFModelDocumentoImagem {

    /**
     * @return App
     */
    public static function factory() {
        return new self;
    }

    /**
     * @return array
     */
    public function retrieveDocuments() {

        try {

            $stmt = $this->_conn->prepare("
                SELECT
                    digital,
                    (SELECT min(flg_publico) FROM sgdoc.tb_documentos_imagem WHERE digital = di.digital AND flg_publico != 2 LIMIT 1) AS flag,
                    (SELECT id_unidade FROM sgdoc.tb_documentos_imagem WHERE digital = di.digital ORDER BY id DESC LIMIT 1) AS unit,
                    (SELECT id_usuario FROM sgdoc.tb_documentos_imagem WHERE digital = di.digital ORDER BY id DESC LIMIT 1) AS user
		FROM sgdoc.tb_documentos_imagem di
               WHERE not convertido
                 AND flg_publico != 2
               GROUP BY digital
               ORDER BY digital
               LIMIT 4000");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return boolean
     * @param string $digital
     * @param string $lastDate
     */
    public function hiddenImages($digital) {

        try {

            $stmt = $this->_conn->prepare("delete from sgdoc.tb_documentos_imagem where digital = ?");

            $stmt->bindValue(1, $digital, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return boolean
     * @param string $digital
     * @param string $md5
     * @param string $hash
     * @param date $date
     * @param int $pages
     * @param int $idUser
     * @param int $idUnit
     * @param float $width
     * @param float $height
     * @param int $size
     * @param int $flag
     * @return boolean
     * @throws PDOException
     */
    public function registerPDFinDB($digital, $md5, $hash, $date, $pages, $idUser, $idUnit, $width, $height, $size, $flag) {

        try {

            $convertido = true;
            $sql = "insert into sgdoc.tb_documentos_imagem "
                    . "(digital, md5, des_hash_file, dat_inclusao, img_type,"
                    . " total_paginas, id_usuario, id_unidade, ordem, img_width,"
                    . " img_height, img_bytes, flg_publico, convertido) "
                    . " values (?,?,?,?,9,"
                    . "         ?,?,?,0,?,"
                    . "         ?,?,?,?)";

            $stmt = $this->_conn->prepare("insert into sgdoc.tb_documentos_imagem "
                    . "(digital,md5,des_hash_file,dat_inclusao,img_type,total_paginas,id_usuario,id_unidade,ordem,img_width,img_height,img_bytes,flg_publico,convertido) "
                    . " values (?,?,?,?,9,?,?,?,0,?,?,?,?,?)");

            $stmt->bindValue(1, $digital, PDO::PARAM_STR);
            $stmt->bindValue(2, $md5, PDO::PARAM_STR);
            $stmt->bindValue(3, $hash, PDO::PARAM_STR);
            $stmt->bindValue(4, $date, PDO::PARAM_STR);
            $stmt->bindValue(5, $pages, PDO::PARAM_INT);
            $stmt->bindValue(6, $idUser, PDO::PARAM_INT);
            $stmt->bindValue(7, $idUnit, PDO::PARAM_INT);
            $stmt->bindValue(8, $width, PDO::PARAM_STR);
            $stmt->bindValue(9, $height, PDO::PARAM_STR);
            $stmt->bindValue(10, $size, PDO::PARAM_INT);
            $stmt->bindValue(11, $flag, PDO::PARAM_INT);
            $stmt->bindValue(12, $convertido, PDO::PARAM_BOOL);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return void
     */
    public function run() {

        foreach ($this->retrieveDocuments() as $key => $result) {

            try {

                if (!$this->isLocked()) {
                    break;
                }

                AppMigrate::factory($result['DIGITAL'], __BASE_PATH__ . '/cache/import')->run(function($response) use ($result) {

                    $destination = __BASE_PATH__ . '/documento_virtual/' . $response['lote'] . '/' . $result['DIGITAL'] . '/' . App::factory()->md5($response['filename']) . '.pdf';

                    App::factory()->hiddenImages($result['DIGITAL']);
                    App::factory()->deleteImages($result['DIGITAL']);

                    App::factory()->registerPDFinDB($result['DIGITAL'], App::factory()->md5($response['filename']), $response['checksum'], date('Y-m-d H:i:s'), App::factory()->countPages($response['filename']), $result['USER'], $result['UNIT'], 15.2, 30.3, App::factory()->size($response['filename']), $result['FLAG']);

                    App::factory()->move($response['filename'], $destination);

                    App::factory()->clean($response['filename']);

                    file_put_contents($result['DIGITAL'], $result['DIGITAL']);
                });
            } catch (Exception $e) {
                $this->error($e->getMessage());
                continue;
            }
        }

        $this->unlock();
    }

    /**
     * @return boolean
     */
    public function isLocked() {
        return file_exists(__DIR__ . '/../lock');
    }

    /**
     * @return App
     */
    public function lock() {
        file_put_contents(__DIR__ . '/../lock', microtime());
        return $this;
    }

    /**
     * @return App
     */
    public function unlock() {
        unlink(__DIR__ . '/../lock');
        return $this;
    }

    /**
     * @return integer
     */
    public function countOk() {
        try {

            $stmt = $this->_conn->prepare("select count(convertido) as total from sgdoc.tb_documentos_imagem where convertido = true");
            $stmt->execute();

            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            return (integer) $out['TOTAL']? : 1;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return integer
     */
    public function countPendents() {

        try {

            $stmt = $this->_conn->prepare("select count(convertido) as total from sgdoc.tb_documentos_imagem where convertido = false");
            $stmt->execute();

            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            return (integer) $out['TOTAL']? : 1;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return integer
     * @param string $filename
     */
    public function countPages($filename) {
        $result = exec("/usr/local/bin/identify -format %n {$filename}");
        return (is_numeric($result)) ? $result : 0;
    }

    /**
     * @return string
     * @param string $filename
     */
    public function md5($filename) {
        return md5($filename);
    }

    /**
     * @return string
     * @param string $filename
     */
    public function hash($filename) {
        return hash('sha256', file_get_contents($filename));
    }

    /**
     * @return string
     * @param string $filename
     */
    public function size($filename) {
        return (int) filesize($filename);
    }

    /**
     * @return boolean
     * @param string $filename
     * @param string $destination
     */
    public function move($filename, $destination) {
        return copy($filename, $destination);
    }

    /**
     * @return boolean
     * @param string $filename
     */
    public function clean($filename) {
        return unlink($filename);
    }

    /**
     * 
     */
    public function error($error) {
        file_put_contents(__DIR__ . '/../errors', "[" . date('d/m/Y H:i:s') . "] - " . $error . "\n\r" . file_get_contents(__DIR__ . '/../errors'));
    }

    /**
     * 
     */
    public function log() {
        return file_get_contents(__DIR__ . '/../errors');
    }

    /**
     * @return boolean
     * @param string $digital
     */
    public function deleteImages($digital) {

        $dir = __CAM_UPLOAD__ . '/' . Util::gerarRaiz($digital, __DIR_IMAGENS__) . '/' . $digital;

        if (is_dir($dir)) {

            $files = scandir($dir);

            unset($files[0]); // remove '.'
            unset($files[1]); // remove '..'

            foreach ($files as $filename) {
                if (file_exists($dir . '/' . $filename)) {
                    unlink($dir . '/' . $filename);
                }
            }
        }
    }

}
