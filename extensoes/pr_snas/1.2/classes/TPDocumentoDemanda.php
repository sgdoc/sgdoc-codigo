<?php

include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.2/classes/CFModelControlePrazosDemanda.php');

class TPDocumentoDemanda {

    /**
     * @return void
     */
    protected function __construct() {
        
    }

    /**
     * @return TPDocumento
     */
    public static function factory() {
        return new self();
    }

    /**
     * @return boolean
     * @param array $array
     */
    public function create($array) {

        try {

            $lastId = CFModelDocumento::factory()->insert($array);

            if ($lastId) {
                CFModelDigital::factory()->mark($array['DIGITAL'], $array['ID_UNIDADE'], $array['ID_USUARIO']);
                CFModelDocumentoHistoricoTramite::factory()->insert(array(
                    'DIGITAL' => $array['DIGITAL'],
                    'ID_USUARIO' => $array['ID_USUARIO'],
                    'USUARIO' => $array['USUARIO'],
                    'ID_UNIDADE' => $array['ID_UNIDADE'],
                    'DIRETORIA' => $array['DIRETORIA'],
                    'ACAO' => 'Documento Cadastrado',
                    'ORIGEM' => $array['ORIGEM'],
                    'DESTINO' => 'XXXXX',
                    'DT_TRAMITE' => date('Y-m-d H:i:s'),
                    'ST_ATIVO' => 1
                ));
            }

            return $lastId;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return TPDocumento
     * @param array $array
     */
    public function transact($array) {

        try {

            CFModelDocumento::factory()->update($array);

            CFModelDocumentoHistoricoTramite::factory()->insert(array(
                'DIGITAL' => $array['DIGITAL'],
                'ID_USUARIO' => $array['ID_USUARIO'],
                'USUARIO' => $array['USUARIO'],
                'ID_UNIDADE' => $array['ID_UNIDADE'],
                'DIRETORIA' => $array['DIRETORIA'],
                'ACAO' => 'Encaminhado',
                'ORIGEM' => $array['ORIGEM'],
                'DESTINO' => $array['DESTINO'],
                'DT_TRAMITE' => date('Y-m-d H:i:s'),
                'ST_ATIVO' => 1
            ));

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return boolean
     * @param array $array
     */
    public function edit($array) {
        try {
            return CFModelDocumento::factory()->update($array);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return string
     * @param string $digital
     */
    public function generateRepositoryForDigital($digital) {

        $dir = __CAM_UPLOAD__;

        $lote = 'LOTE' . floor($digital / 10000);

        if (!is_dir("{$dir}/{$lote}")) {
            mkdir("{$dir}/{$lote}", 0777);
        }

        if (!is_dir("{$dir}/{$lote}/{$digital}")) {
            mkdir("{$dir}/{$lote}/{$digital}", 0777);
        }

        return "{$dir}/{$lote}/{$digital}";
    }

    /**
     * @return TPDocumento
     * @param array $content
     */
    public function generatePDF($content) {

        define('FPDF_FONTPATH', 'bibliotecas/fpdf/font/');

        include('bibliotecas/fpdf/fpdf.php');
        include('bibliotecas/barcode/code128.php');

        $this->filename = $filename = sprintf('%s/TMP/%s.pdf', __CAM_UPLOAD__, CFUtils::random());
        $this->newname = sprintf('%s/0%s', $this->generateRepositoryForDigital($content['DIGITAL']), CFUtils::random());

        $pdf = new PDF_Code128();

        $pdf->SetFont('times', '', 12);
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $pdf->ln(2);

        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(170, 12, __ETIQUETA__);
        $pdf->Code128(165, 14, $content['DIGITAL'], 30, 9);
        $pdf->Text(173, 27, $content['DIGITAL']);

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->Cell(180, 5, '', 0, 0, 'R');
        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $pdf->Image("imagens/" . __LOGO_JPG__, 95, 8, 20, 20);
        $pdf->Ln(20);

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->SetFont("Arial", "B", 8);
        $pdf->Cell(22, 5, "Digital: ", 0, 0, 'L');
        $pdf->SetFont("Arial", '', 8);
        $pdf->Cell(100, 5, $content['DIGITAL'], 0, 0, 'L');
        $pdf->SetFont("Arial", "B", 8);
        $pdf->Cell(18, 5, "Data do Documento:  ", 0, 0, 'L');
        $pdf->SetFont("Arial", '', 8);
        $pdf->Cell(40, 5, CFUtils::formatDate($content['DT_DOCUMENTO']), 0, 1, 'R');

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->SetFont("Arial", "B", 8);
        $pdf->Cell(22, 5, "Origem:", 0, 0, 'L');
        $pdf->SetFont("Arial", '', 8);
        $pdf->Cell(100, 5, utf8_decode($content['ORIGEM']), 0, 0, 'L');
        $pdf->SetFont("Arial", "B", 8);
        $pdf->Cell(18, 5, utf8_decode("Número Solicitação:  "), 0, 0, 'L');
        $pdf->SetFont("Arial", '', 8);
        $pdf->Cell(40, 5, utf8_decode($content['NUMERO']), 0, 1, 'R');

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->SetFont("Arial", "B", 8);
        $pdf->Cell(22, 5, "Interessado:", 0, 0, 'L');
        $pdf->SetFont("Arial", '', 8);
        $pdf->Cell(100, 5, utf8_decode($content['INTERESSADO']), 0, 0, 'L');

        $pdf->Ln();

        $pdf->SetFont("Arial", "B", 8);
        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->Cell(22, 5, "Assunto:", 0, 0, 'L');
        $pdf->SetFont("Arial", '', 8);
        $pdf->MultiCell(165, 5, utf8_decode($content['ASSUNTO']), 0, 1);

        $pdf->SetFont("Arial", "B", 8);
        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->Cell(35, 5, "Assunto Complementar:", 0, 0, 'L');
        $pdf->SetFont("Arial", '', 8);
        $pdf->MultiCell(165, 5, utf8_decode($content['ASSUNTO_COMPLEMENTAR']), 0, 1);

        $pdf->SetFont("Arial", "B", 8);
        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->Cell(22, 5, "Prioridade:", 0, 0, 'L');
        $pdf->SetFont("Arial", '', 8);
        $pdf->MultiCell(165, 5, utf8_decode($content['NM_PRIORIDADE']), 0, 1);

        $pdf->SetFont("Arial", "B", 8);
        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->Cell(22, 5, "Data do Prazo:", 0, 0, 'L');
        $pdf->SetFont("Arial", '', 8);
        $pdf->MultiCell(165, 5, CFUtils::formatDate($content['DT_PRAZO']), 0, 1);

        $pdf->Ln();

        $pdf->SetFont("Arial", "B", 8);
        $pdf->Cell(195, 5, utf8_decode("Informações do Documento"), 0, 1, 'C');

        $pdf->Ln();

        $pdf->Cell(8, 5, '', 0, 0, 'C');
        $pdf->SetFont("Arial", '', 8);
        $pdf->Cell(40, 5, 'Ao ' . utf8_decode($content['DESTINO']), 0, 1, 'L');

        $pdf->Ln();

        $pdf->SetFont("Arial", '', 8);
        $pdf->Cell(8, 5, '', 0, 0, 'L');
        $pdf->Write(5, utf8_decode($content['SOLICITACAO']));

        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetFont("Arial", "B", 8);
        $pdf->Cell(8, 5, '', 0, 0, 'L');
        $pdf->Write(5, utf8_decode($content['NOME']));

        $pdf->Output($filename, 'F');

        return $this;
    }

    /**
     * @return TPDocumento
     */
    public function convertPDFToPng($digital) {

        $command = "gs -q -dNOPAUSE -dBATCH -sDEVICE=png16m -r300 -dEPSCrop -sOutputFile=%s/00000000000000000000000000000044_\%%04d.png {$this->filename}";
        $execute = sprintf($command, $this->generateRepositoryForDigital($digital));
        $result = shell_exec($execute);
        $this->_errorExists($result);

        return $this;
    }

    /**
     * @todo Refatorar para utilizar tratamento em somente um local
     * Lança uma exceção caso encontre a palavra error
     * 
     * @param string $statement
     * @throws Exception
     */
    private function _errorExists($statement) {
        if (preg_match('/error/i', $statement)) {
            throw new Exception('Ocorreu um erro na conversão do PDF!' . $statement);
        }
    }

    /**
     * @return TPDocumento
     */
    public function garbageCollection() {
        @unlink($this->filename);
        return $this;
    }

    /**
     * @return array
     * @param string $digital
     */
    public function listPNG($digital) {

        $pngs = scandir($this->generateRepositoryForDigital($digital));

        unset($pngs[0]);
        unset($pngs[1]);

        return $pngs;
    }

    /**
     * @return TPDocumento
     * @param string $digital
     * @param Base $persist
     */
    public function registerPNGDB($digital, $persist) {

        $pngs = $this->listPNG($digital);

        $repository = $this->generateRepositoryForDigital($digital);

        $next = -1;
        $query = 'INSERT INTO TB_DOCUMENTOS_IMAGEM (DIGITAL,FLG_PUBLICO,MD5,DES_HASH_FILE,IMG_WIDTH,IMG_HEIGHT,IMG_BYTES,IMG_TYPE,ORDEM,ID_USUARIO,ID_UNIDADE) VALUES ';

        foreach ($pngs as $png) {

            $query .= '(?,?,?,?,?,?,?,?,?,?,?),';
            $md5 = CFUtils::random();

            rename("{$repository}/{$png}", "{$repository}/{$md5}.png");

            $information = getimagesize("{$repository}/{$md5}.png");

            $row[] = $digital;
            $row[] = 1;
            $row[] = $md5;
            $row[] = hash_file('md5', "{$repository}/{$md5}.png");
            $row[] = $information[0];
            $row[] = $information[1];
            $row[] = filesize("{$repository}/{$md5}.png");
            $row[] = 8;
            $row[] = ++$next;
            $row[] = Controlador::getInstance()->usuario->ID;
            $row[] = Controlador::getInstance()->usuario->ID_UNIDADE;
        }

        $stmt = $persist->prepare(trim($query, ','));
        $stmt->execute($row);

        return $this;
    }

    /**
     * @return void
     * @param array $_REQUEST
     */
    public function registerDeadlines($arrAllRequest) {
        try {
            $lastIdControlePrazosDemanda = CFModelControlePrazosDemanda::factory()->insert(array(
                'NU_PROC_DIG_REF' => $arrAllRequest['DIGITAL'],
                'ID_USUARIO_ORIGEM' => $arrAllRequest['ID_USUARIO'],
                'ID_UNID_ORIGEM' => $arrAllRequest['ID_UNID_CAIXA_SAIDA'],
                'ID_UNID_DESTINO' => $arrAllRequest['ID_UNID_CAIXA_ENTRADA'],
                'DT_PRAZO' => $arrAllRequest['DT_PRAZO'],
                'FG_STATUS' => 'AR',
                'TX_SOLICITACAO' => $arrAllRequest['SOLICITACAO'],
            ));

            $sttt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO EXT__SNAS__TB_CONTROLE_PRAZOS (ID,NU_PROC_DIG_REF_PAI) VALUES (?,?)");
            $sttt->bindParam(1, $lastIdControlePrazosDemanda, PDO::PARAM_INT);
            $sttt->bindParam(2, $arrAllRequest['DIGITAL_PAI'], PDO::PARAM_STR);
            $sttt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param string $path
     * @return string
     */
    public static function pathToUrl($path) {
        $pathHttp = __CAM_IMAGENS__ . preg_replace('/.*(' . __DIR_IMAGENS__ . ')\//i', '', $path);
        return $pathHttp;
    }

}