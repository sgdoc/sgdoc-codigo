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

ini_set("max_execution_time", 0);

//Se for setado manualmente (administrativamente), assume o valor
if (isset($_REQUEST['memory_limit'])) {
    ini_set('memory_limit', "{$_REQUEST['memory_limit']}M");
}

class Imagens extends Base {

    const Q_LOW = 1;
    const Q_MEDIUM = 2;
    const Q_HIGH = 3;

    /**
     * @var mixed
     */
    private $_output = null;

    /**
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @return Imagens
     */
    public static function factory() {
        return new self();
    }

    /**
     * @return array
     * @param array $pages
     */
    public function generateLinks($pages) {

        $quantidade = 0;
        $out = array();

        $allow = AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999));

        foreach ($pages as $key => $page) {

            $quantidade++;

            if ($page['FLG_PUBLICO'] == 0 && !$allow) {

                $out['imagem']['preview'][$key] = sprintf("%s/imagens/documento_confidencial_thumb.jpg", __URLSERVERAPP__);
                $out['imagem']['full'][$key] = sprintf("%s/imagens/documento_confidencial_view.jpg", __URLSERVERAPP__);
            } else {

                $out['imagem']['preview'][$key] = sprintf("%scache/%s/%s/%s_thumb.jpg", __CAM_IMAGENS__, $this->generateLote($page['DIGITAL']), $page['DIGITAL'], $page['MD5']);
                $out['imagem']['full'][$key] = sprintf("%scache/%s/%s/%s_view_1.jpg", __CAM_IMAGENS__, $this->generateLote($page['DIGITAL']), $page['DIGITAL'], $page['MD5']);


                $thumb = sprintf('%s/cache/%s/%s/%s_thumb.jpg', __CAM_UPLOAD__, $this->generateLote($page['DIGITAL']), $page['DIGITAL'], $page['MD5']);
                $view = sprintf('%s/cache/%s/%s/%s_view_1.jpg', __CAM_UPLOAD__, $this->generateLote($page['DIGITAL']), $page['DIGITAL'], $page['MD5']);

                if (!is_file($thumb)) {
                    $out['imagem']['preview'][$key] = sprintf("%s/imagens/imagem_quebrada.jpg", __URLSERVERAPP__);
                }

                if (!is_file($view)) {
                    $out['imagem']['full'][$key] = sprintf("%s/imagens/imagem_quebrada_a4.jpg", __URLSERVERAPP__);
                }
            }

            $out['imagem']['status'][$key] = $page['FLG_PUBLICO'];
            $out['imagem']['md5'][$key] = $page['MD5'];
            $out['imagem']['ordem'][$key] = $page['ORDEM'];
        }

        $out['imagem']['quantidade'] = $quantidade;

        $this->_output = $out;

        return $this;
    }

    /**
     * @return Imagens
     * @param string $numero_processo
     */
    public function generateLinksForProcess($numero_processo, $high = false) {
        $pages = $this->recoverInformationDBByProcess($numero_processo);
        return $this->generateLinks($pages);
    }

    /**
     * @return Imagens
     * @param string $digital
     */
    public function generateLinksForDigital($digital, $between = array()) {
        $pages = $this->recoverInformationDBByDigital($digital, false, '2000-01-01 00:00:00', $between);
        return $this->generateLinks($pages);
    }

    /**
     * @return Imagens
     * @param string $digital
     * @param boolean $high true para alta resolução de imagem
     * @param array between array de 2 elementos para definir os documentos que serão mostrados ex: páginas de 1 a 15
     */
    public function generateCacheForDigital($digital, $high = false, $between = array()) {

        if ($this->isLocked($digital)) {
            return $this;
        }

        $pages = $this->recoverInformationDBByDigital($digital, false, '2000-01-01 00:00:00', $between);

        if (!empty($pages)) {
            if ($this->lock($digital)) {
                $this->convertTiffToJpegView($pages, $high);
                $this->unlock($digital);
            } else {
                throw new Exception("Não foi possível criar arquivo de lock do digital {$digital}.");
            }
        }

        return $this;
    }

    /**
     * @return Imagens
     * @param string $digital
     * @param boolean $high // define a qualidade da imagem
     * @param boolean $generatePDFForProcess // define se será gerado o PDF para os arquivos.
     */
    public function generateCacheForProcess($numero_processo, $high = false, $generatePDFForProcess = false) {

        $documentos = array();

        $pages = $this->recoverInformationDBByProcess($numero_processo);

        if ($generatePDFForProcess) {
            $this->generatePDFForProcess($numero_processo, $high, $pages);
        }

        foreach ($pages as $key => $page) {
            $documentos[$page['DIGITAL']][] = $page;
        }

        foreach ($documentos as $digital => $pages) {
            $this->generateCacheForDigital($digital, $high);
        }

        return $this;
    }

    /**
     * Recupera informações das imagens
     * @return array
     * @param string $digital
     * @param booleano $all booleano que define se serão retornados todos os registros
     * @param date $date formato yyyy-mm-dd hh:mm:ss
     * @param array between array de 2 elementos para definir os documentos que serão mostrados ex: páginas de 1 a 15
     */
    public function recoverInformationDBByDigital($digital, $all = false, $date = '2000-01-01 00:00:00', $between = array()) 
    {
        $status = ($all == false) ? 2 : -1;

        $sql = "SELECT ORDEM, DIGITAL, MD5, FLG_PUBLICO, IMG_WIDTH, IMG_HEIGHT, DAT_INCLUSAO, IMG_TYPE, TOTAL_PAGINAS
                FROM TB_DOCUMENTOS_IMAGEM 
                WHERE DIGITAL = ? AND FLG_PUBLICO != ? AND DAT_INCLUSAO >= ? ";

        if (count($between) == 2) {
            $sql .= " AND ORDEM >= {$between[0]} ";
        }

        $sql .= " ORDER BY ORDEM ASC";

        if (count($between) == 2) {
            $between[1]++;
            $sql .= " LIMIT {$between[1]} ";
        }

        try {

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam(1, $digital, PDO::PARAM_STR);
            $stmt->bindParam(2, $status, PDO::PARAM_INT);
            $stmt->bindParam(3, $date, PDO::PARAM_STR);
            $stmt->execute();

            $objAssoc = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if( $objAssoc[0]['IMG_TYPE'] == '9' ){
                $documentoImagemPDF = new Documento\Imagem\DocumentoImagemPDF();
                return $documentoImagemPDF
                        ->setRowSet( $objAssoc )
                        ->getImageList();
            }else{                
                return $objAssoc;
            }
            
        } catch (Exception $e) {
            throw $e;
        }
    }    

    /**
     * @return array
     * @param string $digital
     * @param booleano $all booleano que define se serão retornados todos os registros
     * @param date $date formato yyyy-mm-dd hh:mm:ss
     * @param array between array de 2 elementos para definir os documentos que serão mostrados ex: páginas de 1 a 15
     */
    public function recoverInformationDBByDigitalAndMD5($digital, $md5 = array(), $allow = false) {

        $exclude = $allow ? 4 : 0;

        //@todo verificar porque o PDO nao aceitar fazer bindParam() no implode da variavel $md5...
        $md5 = preg_replace("/[^a-zA-Z0-9,']+/", "", "'" . implode("','", $md5) . "'");

        $sql = "SELECT ORDEM, DIGITAL, MD5, FLG_PUBLICO, IMG_WIDTH, IMG_HEIGHT, DAT_INCLUSAO 
                    FROM SGDOC.TB_DOCUMENTOS_IMAGEM 
                WHERE (DIGITAL = ? AND FLG_PUBLICO != 3 AND FLG_PUBLICO != ?) AND MD5 IN ($md5) ORDER BY ORDEM";

        try {

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam(1, $digital, PDO::PARAM_STR);
            $stmt->bindParam(2, $exclude, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     * @param string $numero_processo
     */
    public function recoverInformationDBByProcess($numero_processo) {

        $vinculacao = new Vinculacao();

        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
            SELECT DC.DIGITAL
            FROM TB_PROCESSOS_DOCUMENTOS PXD
                INNER JOIN TB_PROCESSOS_CADASTRO PC ON PC.ID = PXD.ID_PROCESSOS_CADASTRO
                INNER JOIN TB_DOCUMENTOS_CADASTRO DC ON DC.ID = PXD.ID_DOCUMENTOS_CADASTRO
            WHERE PC.NUMERO_PROCESSO = ? ORDER BY PXD.ID
        ");

        $stmt->bindParam(1, $numero_processo, PDO::PARAM_STR);
        $stmt->execute();
        $first = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $list = $all = array();

        foreach ($first as $key => $digital) {
            $all[$digital['DIGITAL']] = $vinculacao->getDocumentosRelacionados( $digital['DIGITAL'] );
        }

        foreach ($all as $pai => $filhos) {

            $list[] = $pai;

            foreach ($filhos as $key => $xn) {
                if (in_array($key, $list) === false) {
                    $list[] = $key;
                }
                foreach ($xn as $ix => $jk) {
                    if (in_array($jk, $list) === false) {
                        $list[] = $jk;
                    }
                }
            }
        }

        $response = array();

        foreach ($list as $key => $digital) {
            $response = array_merge($response, $this->recoverInformationDBByDigital($digital));
        }

        return $response;
    }

    /**
     * @return void
     */
    public function toJson($print = false) {
        if (!$print) {
            return json_encode($this->_output);
        }
        print json_encode($this->_output);
    }

    /**
     * @return boolean
     * @param string $dir
     */
    private function _createDirectory($dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0775);
        }
        return is_dir($dir);
    }

    /**
     * @return string
     * @param string $digital
     */
    public function generateLote($digital) {
        return 'LOTE' . floor($digital / 10000);
    }

    /**
     * @return Imagens
     * @param string $digital
     * @param integer $active
     * @param integer $totallas-1
     * @param boolean $high
     */
    public function updateLastCount($digital, $active, $total, $high) {
        file_put_contents(
                sprintf('%s/cache/%s/%s/last-%s', __CAM_UPLOAD__, $this->generateLote($digital), $digital, ($high === false) ? 1 : 3), "{$active}/{$total}"
        );

        return $this;
    }

    /**
     * @return Imagens
     * @param array $pages
     * @param integer $quality
     */
    public function convertTiffToJpegView($pages, $high = false) {

        $report = array();
        $total = array();
        $active = 0;

        foreach ($pages as $page) {
            $total[$page['DIGITAL']]++;
        }

        foreach ($pages as $page) {
            $active++;
            try {
                $this->createCacheJpegView($page['DIGITAL'], $page['MD5'], $high, $active, $total[$page['DIGITAL']]);
            } catch (Exception $e) {
                $report[$active] = array(
                    'md5' => $page['MD5'],
                    'error' => $e->getMessage(),
                    'active' => $active,
                    'total' => $total[$page['DIGITAL']]
                );
            }
            $this->updateLastCount($page['DIGITAL'], $active, $total[$page['DIGITAL']], $high);
        }

        // Reportar erros ao administradores do sistema...
        if (!empty($report)) {

            Error::factory()
                    ->setSpecificError(print_r($report, true), __FILE__, __LINE__)
                    ->sendEmailFatalError()
            ;

            // @todo Correcao pro ativa 
            // Avaliar a necessidade de resolver inconsistencias pro-ativamente...             
            //$this->garbageCollection($page['DIGITAL']);

            foreach ($total as $digital => $count) {
                file_put_contents(sprintf('%s/cache/%s/%s/last-%d', __CAM_UPLOAD__, $this->generateLote($digital), $digital, ($high === false) ? 1 : 3), "XXX/XXX");
            }
        }

        return $this;
    }

    /**
     * @return Imagens
     * @param string $digital
     * @param string $md5
     * @param boolean $high
     * @param int $active
     * @param int $total
     */
    public function createCacheJpegView($digital, $md5, $high = false) {

        $iImageHeightPixel = ($high === false) ? 960 : 2480;
        $iImageWidthPixel = ($high === false) ? 1280 : 3508;

        $lote = $this->generateLote($digital);

        $dirCache = sprintf('%s/cache/%s', __CAM_UPLOAD__, $lote);
        $tiff = sprintf('%s/%s/%s/%s.tif', __CAM_UPLOAD__, $lote, $digital, $md5);
        $view = sprintf('%s/%s/%s_view_%d.jpg', $dirCache, $digital, $md5, ($high === false) ? self::Q_LOW : self::Q_HIGH);
        $thumbs = sprintf('%s/%s/%s_thumb.jpg', $dirCache, $digital, $md5);

        if (!is_file($tiff)) {
            throw new Exception('Arquivo TIFF original não encontrado!');
        }

        if (is_file($view)) {
            return $this;
        }

        // thumbs
        if (!is_file($thumbs)) {

            MagickReadImage($magickThumbs = NewMagickWand(), $tiff);

            if (MagickGetImageWidth($magickThumbs) < MagickGetImageHeight($magickThumbs)) {
                MagickResizeImage($magickThumbs, 150, 200, MW_QuadraticFilter, 1.0);
            } else {
                MagickResizeImage($magickThumbs, 200, 150, MW_QuadraticFilter, 1.0);
            }

            MagickSetImageFormat($magickThumbs, 'JPG');
            MagickSetImageResolution($magickThumbs, 200, 200);
            MagickSetImageUnits($magickThumbs, MW_PixelsPerInchResolution);
            MagickSetImageCompression($magickThumbs, MW_JPEGCompression);
            MagickSetImageCompressionQuality($magickThumbs, 0.0);
            MagickWriteImage($magickThumbs, $thumbs);
        }

        // views
        MagickReadImage($magickView = NewMagickWand(), $tiff);

        if (MagickGetImageWidth($magickView) > MagickGetImageHeight($magickView)) {
            MagickResizeImage($magickView, $iImageWidthPixel, $iImageHeightPixel, MW_QuadraticFilter, 1.0);
        } else {
            MagickResizeImage($magickView, $iImageHeightPixel, $iImageWidthPixel, MW_QuadraticFilter, 1.0);
        }

        MagickSetImageFormat($magickView, 'JPG');
        MagickSetImageResolution($magickView, 200, 200);
        MagickSetImageUnits($magickView, MW_PixelsPerInchResolution);
        MagickSetImageCompression($magickView, MW_JPEGCompression);
        MagickSetImageCompressionQuality($magickView, 0.0);
        MagickWriteImage($magickView, $view);

        $errorMagick = MagickGetExceptionString($magickView);

        if ($errorMagick) {
            throw new Exception($errorMagick);
        }

        return $this;
    }

    /**
     * @return Imagens
     * @param string $digital
     */
    public function clearCache($digital) {

        $dirCache = sprintf('%s/cache/%s/%s', __CAM_UPLOAD__, $this->generateLote($digital), $digital);

        foreach (glob("{$dirCache}/*", GLOB_NOSORT) as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }

        if (is_dir($dirCache)) {
            rmdir($dirCache);
        }

        return $this;
    }

    /**
     * @return Imagens
     */
    public function clearAllCache() {

        $handleLotes = opendir(__CAM_UPLOAD__);

        //Interacao nos lotes...
        if ($handleLotes) {
            while (false !== ($lote = readdir($handleLotes))) {
                if ($lote != '.' && $lote != '..') {

                    $dirLote = __CAM_UPLOAD__ . '/cache/' . $lote;

                    if (!is_dir($dirLote)) {
                        continue;
                    }

                    //Interacao nos digitais...
                    $handleDigitais = opendir($dirLote);

                    if ($handleDigitais) {
                        while (false !== ($digital = readdir($handleDigitais))) {
                            if ($digital != '.' && $digital != '..') {

                                $last = __CAM_UPLOAD__ . "/cache/{$lote}/{$digital}/last-1";

                                if (!is_file($last)) {
                                    continue;
                                }

                                // Se idade do arquivo em dias for maior que o definido no arquivo de configuracao entao limpa o cache...
                                if (round((time() - filemtime($last)) / 86400) >= __CACHEIMAGENSDIAS__) {
                                    Imagens::factory()->clearCache($digital);
                                }
                            }
                        }
                        closedir($handleDigitais);
                    }
                }
            }
            closedir($handleLotes);
        }

        return $this;
    }

    /**
     * @return void
     * @param string $numero_processo
     * @param boolean $high
     * @param string $type pode ser jpg ou png a imagem de origem
     */
    public function generatePDFForDigital($digital, $high = false, $type = 'jpg') {
        $pages = $this->recoverInformationDBByDigital($digital);
        $this->generatePDF($pages, $high, $type);
    }

    /**
     * @return void
     * @param string $digital
     * @param boolean $high
     * @param string $type pode ser jpg ou png a imagem de origem
     * @param array $md5
     * @param array $allow
     */
    public function generatePDFForDigitalByMD5($digital, $high = false, $type = 'jpg', $md5 = array(), $allow = false) {
        $pages = $this->recoverInformationDBByDigitalAndMD5($digital, $md5, $allow);
        $this->generatePDF($pages, $high, $type);
    }

    /**
     * @return void
     * @param string $digital
     * @param boolean $high
     * @param string $type pode ser jpg ou png a imagem de origem
     * @param array $md5
     * @param array $allow
     */
    public function generatePDFForProcessByDigitalsAndMD5($md5 = array(), $allow = false, $type = 'png', $high = false) {

        $pages = array();

        foreach ($md5 as $digital => $hash) {
            $pages = array_merge($pages, $this->recoverInformationDBByDigitalAndMD5($digital, $hash, false));
        }

        $this->generatePDF($pages, $high, $type);
    }

    /**
     * @return void
     * @param string $numero_processo
     * @param boolean $high
     */
    public function generatePDFForProcess($numero_processo, $high = false, $pages = null) {

        if (is_null($pages)) {
            $pages = $this->recoverInformationDBByProcess($numero_processo);
        }
        $this->generatePDF($pages, $high);
    }

    /**
     * @return void
     * @param array $pages
     */
    public function generatePDF($pages, $high = false, $type = 'jpg') {

        $allow = AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999));

        $pdf = new PDF_Bookmark();
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetFont('Arial', '', 12);

        foreach ($pages as $key => $page) {

            if (!$allow && $page['FLG_PUBLICO'] == 0) {
                continue;
            }

            $pdf->author = utf8_decode(__CABECALHO_ORGAO__);
            $pdf->title = 'Sistema Gerenciador de Documentos - v' . __VERSAO__;
            $pdf->creator = $pdf->title . ' (' . utf8_decode(Controlador::getInstance()->usuario->NOME) . ')';

            $imagePath = sprintf('%s/cache/%s/%s/%s_view_%d.jpg', __CAM_UPLOAD__, $this->generateLote($page['DIGITAL']), $page['DIGITAL'], $page['MD5'], ($high ? self::Q_HIGH : self::Q_LOW));

            if ($type == 'png') {
                $imagePath = sprintf('%s/%s/%s/%s.png', __CAM_UPLOAD__, $this->generateLote($page['DIGITAL']), $page['DIGITAL'], $page['MD5']);
            }

            if (!is_file($imagePath)) {
                $imagePath = 'imagens/imagem_quebrada_a4.jpg';
            }

            $typeToUp = strtoupper($type);

            if ($page["IMG_WIDTH"] < $page["IMG_HEIGHT"]) {
                $pdf->AddPage("P", "A4");
                $pdf->Image($imagePath, 0, 0, 210, 297, "$typeToUp");
            } else {
                $pdf->AddPage("L", "A4");
                $pdf->Image($imagePath, 0, 0, 297, 210, "$typeToUp");
            }

            $pdf->Bookmark('Pag. ' . $key + 1);
        }

        $pdf->Output("", 'I');
    }

    /**
     * @return string
     * @param string $digital
     */
    public function percentCacheDigital($digital) {

        $cache = sprintf('%s/cache/%s/%s/last-1', __CAM_UPLOAD__, $this->generateLote($digital), $digital);

        if (is_file($cache)) {
            $contentFile = file_get_contents($cache);
            $contentParts = explode('/', $contentFile);
            if (is_numeric($contentParts[0]) && $contentParts[0] === $contentParts[1]) {
                unlink($cache);
            }
            return $contentFile;
        }

        return '???';
    }

    /**
     * @return string
     * @param string $numero_processo
     */
    public function percentCacheProcess($numero_processo) {
        //@todo Implementar futuramente...
    }

    /**
     * @return Imagens
     */
    public function isExistsDirCache() {
        if (!is_dir(__CAM_UPLOAD__ . '/cache')) {
            mkdir(__CAM_UPLOAD__ . '/cache', 0775);
        }
        return $this;
    }

    /**
     * @return Imagens
     * @param string $digital
     */
    public function garbageCollection($digital) {

        $hashs = array();

        $recoverInDB = $this->recoverInformationDBByDigital($digital, true);
        $recoverInDisc = $this->recoverInformationDiscByDigital($digital);

        // imagem no banco mas nao em disco...
        foreach ($recoverInDB as $image) {
            if (!in_array($image['MD5'], $recoverInDisc)) {
                $this->deleteImageFromDB($digital, $image['MD5']);
            }
            $hashs[] = $image['MD5'];
        }

        // imagem no disco mas nao em banco...
        foreach ($recoverInDisc as $image) {
            if (!in_array($image, $hashs)) {
                $this->deleteImageFromDisc($digital, $image);
            }
        }

        return $this;
    }

    /**
     * @return Imagens
     * @param string $digital
     */
    public function deleteImageFromDisc($digital, $hash) {

        $file = sprintf('%s/%s/%s/%s.tif', __CAM_UPLOAD__, $this->generateLote($digital), $digital, $hash);

        if (file_exists($file)) {
            unlink($file);
        }

        return $this;
    }

    /**
     * @return Imagens
     * @param string $digital
     */
    public function deleteImageFromDB($digital, $hash) {

        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("DELETE FROM TB_DOCUMENTOS_IMAGEM WHERE DIGITAL = ? AND MD5 = ? LIMIT 1");

        $stmt->bindParam(1, $digital, PDO::PARAM_STR);
        $stmt->bindParam(2, $hash, PDO::PARAM_STR);
        $stmt->execute();

        return $this;
    }

    /**
     * @return array
     * @param string $digital
     */
    public function recoverInformationDiscByDigital($digital) {

        $dir = sprintf('%s/%s/%s', __CAM_UPLOAD__, $this->generateLote($digital), $digital);
        $files = array();

        foreach (glob("{$dir}/*.tif", GLOB_NOSORT) as $file) {
            if (file_exists($file)) {
                $files[] = substr($file, -36, -4);
            }
        }

        return $files;
    }

    /**
     * @return boolean
     * @param string $digital
     */
    public function isLocked($digital) {

        $locked = sprintf('%s/cache/%s/%s/locked', __CAM_UPLOAD__, $this->generateLote($digital), $digital);

        return is_file($locked);
    }

    /**
     * @return boolean
     * @param string $digital
     */
    public function lock($digital) {

        $dirCache = sprintf('%s/cache/%s', __CAM_UPLOAD__, $this->generateLote($digital));

        $this->_createDirectory($dirCache);
        $this->_createDirectory("{$dirCache}/{$digital}");

        $lock = sprintf('%s/cache/%s/%s/locked', __CAM_UPLOAD__, $this->generateLote($digital), $digital);

        file_put_contents($lock, microtime());

        return is_file($lock);
    }

    /**
     * @return boolean
     * @param string $digital
     */
    public function unlock($digital) {

        $lock = sprintf('%s/cache/%s/%s/locked', __CAM_UPLOAD__, $this->generateLote($digital), $digital);

        if (!is_file($lock)) {
            return false;
        }

        return unlink($lock);
    }

    /**
     * Atualiza o campo pela digital
     */
    public static function updateImagensFieldByDigital($field, $value, $digital, $extraConditional = '') {

        $sql = "UPDATE TB_DOCUMENTOS_IMAGEM SET {$field} = {$value}
                WHERE DIGITAL = ? {$extraConditional}";

        $conn = Controlador::getInstance()->getConnection()->connection;
        $conn->beginTransaction();
        try {

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $digital, PDO::PARAM_STR);
            $stmt->execute();

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

}