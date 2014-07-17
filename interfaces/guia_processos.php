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
 * @todo Encapsular...
 */
define("FPDF_FONTPATH", "bibliotecas/fpdf/font/");
include("bibliotecas/fpdf/fpdf.php");

$pdf = new FPDF();
$pdf->Open();
$pdf->AddPage();
$pdf->SetFont("Arial", "", 8);
$pdf->Image("imagens/" . __LOGO_JPG__, 95, 8, 20, 20);
$pdf->Ln(2);
$pdf->Cell(185, 8, "GUIA DE RECIBO", 0, 0, 'R');

$pdf->Ln(20);

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(180, 5, utf8_decode(__CABECALHO_ORGAO__), 0, 0, 'C');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(180, 5, '', 0, 0, 'C');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Ln(10);

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 6);
$pdf->Cell(180, 5, utf8_decode("INFORMAÇÕES DO TRAMITE"), 0, 0, 'C');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Ln(5);

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(23, 5, "TRAMITADO POR: ", 0, 0, 'L');
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(172, 5, utf8_decode(DaoUsuario::getUsuario(null, 'nome')), 0, 0, 'L');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(18, 5, "DATA - HORA: ", 0, 0, 'L');
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(178, 5, date("d/m/Y " . " - " . "H:i:s"), 0, 0, 'L');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(28, 5, "ORIGEM DO TRAMITE:", 0, 0, 'L');
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(152, 5, utf8_decode(DaoUnidade::getUnidade(null, 'nome')), 0, 0, 'L');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(21, 5, utf8_decode("DESTINATÁRIO:"), 0, 0, 'L');
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(159, 5, utf8_decode(Session::get('_processos_recibo_destinatario')), 0, 0, 'L');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(10, 5, "LOCAL:", 0, 0, 'L');
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(170, 5, utf8_decode(Session::get('_processos_recibo_local')), 0, 0, 'L');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(16, 5, utf8_decode("ENDEREÇO:"), 0, 0, 'L');
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(164, 5, utf8_decode(Session::get('_processos_recibo_endereco')), 0, 0, 'L');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(16, 5, "TELEFONE:", 0, 0, 'L');
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(164, 5, utf8_decode(Session::get('_processos_recibo_telefone')), 0, 0, 'L');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(7, 5, "CEP:", 0, 0, 'L');
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(173, 5, utf8_decode(Session::get('_processos_recibo_cep')), 0, 0, 'L');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(18, 5, "PRIORIDADE:", 0, 0, 'L');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(162, 5, utf8_decode(Session::get('_processos_recibo_prioridade')), 0, 0, 'L');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Ln(10);

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 6);
$pdf->Cell(180, 5, utf8_decode("RELAÇÃO DE PROCESSOS"), 0, 0, 'C');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Ln(5);

$pdf->SetFont("Arial", "B", 6);
$pdf->Cell(5, 5, "#", 1, 0, 'C');
$pdf->Cell(30, 5, "N. PROCESSO ", 1, 0, 'C');
$pdf->Cell(70, 5, "INTERESSADO", 1, 0, 'C');
$pdf->Cell(70, 5, "ASSUNTO", 1, 0, 'C');
$pdf->Cell(15, 5, "AUTUACAO", 1, 1, 'C');


foreach (Tramite::getProcessosGuiaRecibo() as $key => $processo) {

    try {

        

        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT NUMERO_PROCESSO,I.INTERESSADO AS INTERESSADO,A.ASSUNTO AS ASSUNTO, DT_AUTUACAO
                FROM TB_PROCESSOS_CADASTRO PC
                INNER JOIN TB_PROCESSOS_ASSUNTO A ON A.ID = PC.ASSUNTO
                INNER JOIN TB_PROCESSOS_INTERESSADOS I ON I.ID = PC.INTERESSADO
                WHERE NUMERO_PROCESSO = ? LIMIT 1");
        $stmt->bindParam(1, $processo, PDO::PARAM_INT);
        $stmt->execute();
        $out = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($out)) {

            $pdf->SetFont("Arial", "", 6);
            $pdf->Cell(5, 5, ++$cont, 1, 'C');
            $pdf->Cell(30, 5, $processo, 1, 0, 'C');
            $pdf->Cell(70, 5, $out['INTERESSADO'], 1, 0, 'C');
            $pdf->Cell(70, 5, $out['ASSUNTO'], 1, 0, 'C');
            $pdf->SetFont("Arial", "", 5);
            $pdf->Cell(15, 5, Util::formatDate($out['DT_AUTUACAO']), 1, 1, 'C');
        }
    } catch (PDOException $e) {
        throw new Exception($e);
    }
}

$pdf->Ln(10);

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 6);
$pdf->Cell(180, 5, "COMPROVANTE DE RECEBIMENTO", 0, 0, 'C');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->ln(5);

$pdf->SetFont("Arial", "", 7);
$pdf->Cell(95, 20, "ASSINATURA: _____________________________________________________", 0, 0, 'C');
$pdf->Cell(95, 20, utf8_decode("DATA: ________/ _______/ ________    HORÁRIO: ____ : ____ "), 0, 1, 'C');

$pdf->Output("Guia de Recibo.pdf", "I");