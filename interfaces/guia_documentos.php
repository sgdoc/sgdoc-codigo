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
$pdf->Cell(152, 5, DaoUnidade::getUnidade(null, 'nome'), 0, 0, 'L');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(21, 5, utf8_decode("DESTINATÁRIO:"), 0, 0, 'L');
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(159, 5, utf8_decode(Session::get('_digitais_recibo_destinatario')), 0, 0, 'L');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(10, 5, "LOCAL:", 0, 0, 'L');
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(170, 5, utf8_decode(Session::get('_digitais_recibo_local')), 0, 0, 'L');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(16, 5, utf8_decode("ENDEREÇO:"), 0, 0, 'L');
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(164, 5, utf8_decode(Session::get('_digitais_recibo_endereco')), 0, 0, 'L');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(16, 5, "TELEFONE:", 0, 0, 'L');
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(164, 5, utf8_decode(Session::get('_digitais_recibo_telefone')), 0, 0, 'L');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(7, 5, "CEP:", 0, 0, 'L');
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(173, 5, utf8_decode(Session::get('_digitais_recibo_cep')), 0, 0, 'L');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(18, 5, "PRIORIDADE:", 0, 0, 'L');
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(162, 5, utf8_decode(Session::get('_digitais_recibo_prioridade')), 0, 0, 'L');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Ln(10);

$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont("Arial", "B", 6);
$pdf->Cell(180, 5, utf8_decode("RELAÇÃO DE DOCUMENTOS"), 0, 0, 'C');
$pdf->Cell(5, 5, "", 0, 1, 'L');

$pdf->Ln(5);

$pdf->SetFont("Arial", "B", 6);
$pdf->Cell(5, 5, "#", 1, 0, 'C');
$pdf->Cell(10, 5, "DIGITAL ", 1, 0, 'C');
$pdf->Cell(15, 5, "DATA", 1, 0, 'C');
$pdf->Cell(30, 5, "TIPO", 1, 0, 'C');
$pdf->Cell(30, 5, "NUMERO", 1, 0, 'C');
$pdf->Cell(100, 5, "ORIGEM", 1, 1, 'C');

foreach (Tramite::getDigitaisGuiaRecibo() as $key => $digital) {

    try {

        

        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT TIPO,NUMERO,ORIGEM,ASSUNTO,DT_DOCUMENTO FROM TB_DOCUMENTOS_CADASTRO WHERE DIGITAL = ? LIMIT 1");
        $stmt->bindParam(1, $digital, PDO::PARAM_INT);

        $stmt->execute();
        $out = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($out)) {

            $pdf->SetFont("Arial", "", 6);
            $pdf->Cell(5, 5, ++$cont, 1, 'C');
            $pdf->Cell(10, 5, $digital, 1, 0, 'C');
            $pdf->Cell(15, 5, Util::formatDate($out['DT_DOCUMENTO']), 1, 0, 'C');
            $pdf->Cell(30, 5, $out['TIPO'], 1, 0, 'C');
            $pdf->Cell(30, 5, $out['NUMERO'], 1, 0, 'C');
            $pdf->SetFont("Arial", "", 5);
            $pdf->Cell(100, 5, $out['ORIGEM'], 1, 1, 'C');
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