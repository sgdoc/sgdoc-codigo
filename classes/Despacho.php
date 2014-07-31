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

class Despacho {

    public $despacho;

    /**
     * 
     */
    public function __set($var, $value) {
        $this->despacho->{$var} = $value;
    }

    /**
     * 
     */
    public function __get($var) {
        if (property_exists($this->despacho, $var)) {
            return $this->despacho->{$var};
        } else {
            return null;
        }
    }

    /**
     * 
     */
    public function __construct($array = array()) {
        /* Padronizar caixa baixa pra indice de array */
        $array = array_change_key_case(($array), CASE_LOWER);
        $usuario = Zend_Auth::getInstance()->getIdentity();
        /* Variaveis do usuario */
        $this->despacho->usuario = $usuario->NOME;
        $this->despacho->id_usuario = $usuario->ID;
        $this->despacho->id_unidade = $usuario->ID_UNIDADE_ORIGINAL;
        $this->despacho->diretoria = DaoUnidade::getUnidade($this->despacho->id_unidade, 'nome');

        /* Variaveis do despacho especifico */
        foreach ($array as $key => $value) {
            $this->despacho->{$key} = $value;
        }
    }

    /**
     * @return void
     * @param string $digital
     */
    public function generateFolhaDespachoDocumento($digital) {

        define("FPDF_FONTPATH", "bibliotecas/fpdf/font/");
        include("bibliotecas/fpdf/fpdf.php");

        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
            SELECT 
                C.DIGITAL, C.DT_DOCUMENTO, 
                C.TIPO,C.ORIGEM, C.INTERESSADO, 
                DA.ASSUNTO,C.NUMERO, to_char(C.DT_ENTRADA, 'dd/mm/yyyy'::text) AS DT_ENTRADA
            FROM TB_DOCUMENTOS_CADASTRO C 
                INNER JOIN TB_DOCUMENTOS_ASSUNTO DA ON DA.ID = C.ID_ASSUNTO 
            WHERE DIGITAL = ? LIMIT 1
        ");
        $stmt->bindParam(1, $digital, PDO::PARAM_STR);
        $stmt->execute();

        $documento = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($documento)) {
            print 'Documento não encontrado!';
            return;
        }

        $pdf = new FPDF();

        $pdf->Open();
        $pdf->AddPage();
        $pdf->SetFont("Arial", '', 10);
        $pdf->Image("imagens/" . __LOGO_JPG__, 95, 8, 20, 20);
        $pdf->Ln(2);
        $pdf->Cell(185, 8, "Folha de Despachos", 0, 0, 'R');

        $pdf->Ln(20);

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->SetFont("Arial", "B", 7);
        $pdf->Cell(180, 5, utf8_decode(__CABECALHO_ORGAO__), 0, 0, 'C');
        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->Cell(180, 5, '', 0, 0, 'R');
        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->SetFont("Arial", "B", 6);
        $pdf->Cell(180, 5, utf8_decode("Informações do Documento"), 0, 0, 'C');
        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->SetFont("Arial", "B", 7);
        $pdf->Cell(15, 5, "DIGITAL: ", 0, 0, 'L');
        $pdf->SetFont("Arial", '', 7);
        $pdf->Cell(120, 5, $digital, 0, 0, 'L');
        
        $pdf->SetFont("Arial", "B", 7);
        $pdf->Cell(30, 5, "Data do Documento:  ", 0, 0, 'L');
        $pdf->SetFont("Arial", '', 9);
        $pdf->Cell(25, 5, Util::formatDate($documento['DT_DOCUMENTO']), 0, 0, 'L');
        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->SetFont("Arial", "B", 7);
        $pdf->Cell(15, 5, "Origem:", 0, 0, 'L');
        $pdf->SetFont("Arial", '', 7);
        $pdf->Cell(120, 5, utf8_decode($documento['ORIGEM']), 0, 0, 'L');
//        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $dtEntrada = ($documento['DT_ENTRADA'] == '01/01/0001')? utf8_decode('Não Informada') : $documento['DT_ENTRADA'];
        $pdf->SetFont("Arial", "B", 7);
        $pdf->Cell(30, 5, "Data de Entrada:  ", 0, 0, 'L');
        $pdf->SetFont("Arial", '', 9);
        $pdf->Cell(25, 5, $dtEntrada, 0, 0, 'L');
        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $pdf->SetFont("Arial", "B", 7);
        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->Cell(15, 5, "Assunto:", 0, 0, 'L');
        $pdf->SetFont("Arial", '', 5);
        $pdf->MultiCell(165, 5, utf8_decode($documento['ASSUNTO']), 0);
        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->SetFont("Arial", "B", 8);
        $pdf->Cell(180, 5, "Despachos", 0, 0, 'C');
        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->Cell(180, 5, '', 0, 0, 'C');
        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $pdf->SetFont("Arial", '', 7);
        $pdf->Cell(5, 64, "1", 1, 0, 'C');
        $pdf->Cell(90, 64, '', 1, 0, 'C');
        $pdf->Cell(90, 64, '', 1, 0, 'C');
        $pdf->Cell(5, 64, "2", 1, 1, 'C');

        $pdf->SetFont("Arial", '', 7);
        $pdf->Cell(5, 64, "3", 1, 0, 'C');
        $pdf->Cell(90, 64, '', 1, 0, 'C');
        $pdf->Cell(90, 64, '', 1, 0, 'C');
        $pdf->Cell(5, 64, "4", 1, 1, 'C');

        $pdf->SetFont("Arial", '', 7);
        $pdf->Cell(5, 64, "5", 1, 0, 'C');
        $pdf->Cell(90, 64, '', 1, 0, 'C');
        $pdf->Cell(90, 64, '', 1, 0, 'C');
        $pdf->Cell(5, 64, "6", 1, 1, 'C');

        $pdf->Output("Folha de Despacho - {$digital}", "I");
    }

    /**
     * @return void
     * @param string $numero_processo
     */
    public function generateFolhaDespachoProcesso($numero_processo) {

        define("FPDF_FONTPATH", "bibliotecas/fpdf/font/");
        include("bibliotecas/fpdf/fpdf.php");

        
        

        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT PC.NUMERO_PROCESSO, PC.DT_AUTUACAO AS AUTUACAO, TB_PROCESSOS_INTERESSADOS.INTERESSADO,
                                    TB_PROCESSOS_INTERESSADOS.CNPJ_CPF as CNPJ_CPF, PA.ASSUNTO
                                FROM TB_PROCESSOS_CADASTRO PC INNER JOIN TB_PROCESSOS_ASSUNTO PA ON PA.ID = PC.ASSUNTO
                                    INNER JOIN TB_PROCESSOS_INTERESSADOS ON TB_PROCESSOS_INTERESSADOS.ID = PC.INTERESSADO
                                WHERE PC.NUMERO_PROCESSO = ? LIMIT 1");
        $stmt->bindParam(1, $numero_processo, PDO::PARAM_STR);
        $stmt->execute();

        $processo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($processo)) {
            print 'Processo não encontrado!';
            return;
        }

        $pdf = new FPDF();

        $pdf->Open();
        $pdf->AddPage();
        $pdf->SetFont("Arial", '', 10);
        $pdf->Image("imagens/" . __LOGO_JPG__, 95, 8, 20, 20);
        $pdf->Ln(2);
        $pdf->Cell(185, 8, "Folha de Despachos", 0, 0, 'R');

        $pdf->Ln(20);

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->SetFont("Arial", "B", 10);
        $pdf->Cell(180, 5, utf8_decode(__CABECALHO_ORGAO__), 0, 0, 'C');
        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->Cell(180, 5, '', 0, 0, 'R');
        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->SetFont("Arial", "B", 9);
        $pdf->Cell(180, 5, utf8_decode("Informações do Processo"), 0, 0, 'C');
        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->SetFont("Arial", "B", 9);
        $pdf->Cell(35, 5, utf8_decode("Número do Processo:"), 0, 0, 'L');
        $pdf->SetFont("Arial", '', 9);
        $pdf->Cell(90, 5, $numero_processo, 0, 0, 'L');
        $pdf->SetFont("Arial", "B", 9);
        $pdf->Cell(30, 5, utf8_decode("Data da Autuação:"), 0, 0, 'L');
        $pdf->SetFont("Arial", '', 9);
        $pdf->Cell(25, 5, Util::formatDate($processo['AUTUACAO']), 0, 0, 'L');
        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->SetFont("Arial", "B", 9);
        $pdf->Cell(20, 5, "Interessado:", 0, 0, 'L');
        $pdf->SetFont("Arial", '', 9);
        $pdf->Cell(160, 5, utf8_decode($processo['INTERESSADO']), 0, 0, 'L');
        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $pdf->SetFont("Arial", "B", 9);
        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->Cell(15, 5, "Assunto:", 0, 0, 'L');
        $pdf->SetFont("Arial", '', 9);
        $pdf->Cell(165, 5, utf8_decode($processo['ASSUNTO']), 0, 0, 'L');
        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->SetFont("Arial", "B", 9);
        $pdf->Cell(180, 5, "Despachos", 0, 0, 'C');
        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $pdf->Cell(5, 5, '', 0, 0, 'C');
        $pdf->Cell(180, 5, '', 0, 0, 'C');
        $pdf->Cell(5, 5, '', 0, 1, 'L');

        $pdf->SetFont("Arial", '', 7);
        $pdf->Cell(5, 64, "1", 1, 0, 'C');
        $pdf->Cell(90, 64, '', 1, 0, 'C');
        $pdf->Cell(90, 64, '', 1, 0, 'C');
        $pdf->Cell(5, 64, "2", 1, 1, 'C');

        $pdf->SetFont("Arial", '', 7);
        $pdf->Cell(5, 64, "3", 1, 0, 'C');
        $pdf->Cell(90, 64, '', 1, 0, 'C');
        $pdf->Cell(90, 64, '', 1, 0, 'C');
        $pdf->Cell(5, 64, "4", 1, 1, 'C');

        $pdf->SetFont("Arial", '', 7);
        $pdf->Cell(5, 64, "5", 1, 0, 'C');
        $pdf->Cell(90, 64, '', 1, 0, 'C');
        $pdf->Cell(90, 64, '', 1, 0, 'C');
        $pdf->Cell(5, 64, "6", 1, 1, 'C');

        $pdf->Output("bibliotecas/fpdf", "i");
    }

}