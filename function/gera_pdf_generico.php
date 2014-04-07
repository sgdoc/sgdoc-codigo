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

require_once("funcoes.php");

define("FPDF_FONTPATH", __BASE_PATH__ . "/bibliotecas/fpdf/font/");

require_once(__BASE_PATH__ . "/bibliotecas/fpdf/fpdf.php");
require_once(__BASE_PATH__ . "/bibliotecas/barcode/code128.php");

/**
 * @author Jhonatan Flach
 */
class PDFSic extends PDF_Code128 {

    public $logo;
    public $titulo;
    public $documento;
    public $texto;
    public $rodape;

    /**
     * 
     */
    public function __construct(Documento $doc) {
        parent::__construct();
        $this->logo = __BASE_PATH__ . "/imagens/" . __LOGO_JPG__;
        $this->titulo = "Informação requisitada pela CGU";
        $this->documento = $doc;
        $texto_superior = "Ao cumprimentá-lo, e no intuito de atendermos às determinações da Lei 12.527/2011 - LEI DE ACESSO À INFORMAÇÃO - LAI, levamos ao conhecimento de V.Sa que no dia {$this->documento->data_documento} foi recebido neste Serviço de Atendimento ao Cidadão - SIC uma mensagem via eletrônica solicitando o seguinte:";

        $this->rodape = "Lembramos à V.Sa que se encerrará em {$this->documento->prazo} o prazo final para que as informações sejam remetidas ao SIC com o intuito de que seja encaminhado ao demandante.";

        $this->texto = $texto_superior . "\n\n" . $this->documento->assunto_complementar;
    }

    /**
     * 
     */
    function Header() {
        //Logo
        $this->SetFont("Arial", "", 11);
        $this->Image($this->logo, 95, 8, 20, 20);
        $this->Ln(2);
        // A linha abaixo mostra a posição ideal para se colocar a digital
        // $pdf->Cell(185, 8, $titulo, 0, 0, 'R');
        $this->Ln(20);
        $this->Cell(5, 5, "", 0, 0, 'C');
        $this->SetFont("Arial", "B", 9);
        $this->Cell(180, 5, __CABECALHO_ORGAO__, 0, 0, 'C');
        $this->Cell(5, 5, "", 0, 1, 'L');
        //Line break
        $this->Ln();
    }

    /**
     * 
     */
    function Footer() {
        //Position at 1.5 cm from bottom
        $this->SetY(-15);
        //Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        //Page number
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

/**
 * 
 */
function geraPdfSic(Documento $documento) {

    //ENDEREÇO ONDE SERÁ GERADO O PDF
    $end_final = __CAM_UPLOAD__ . '/' . aleatorio() . ".pdf";
    $tipo_pdf = "F";

    //PREPARA PARA GERAR O PDF
    $pdf = new PDFSic($documento);

    $pdf->SetFont('times', '', 12);
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->ln(2);

    $pdf->SetFont('Arial', '', 9);
    $pdf->Text(170, 12, __ETIQUETA__);
    $pdf->Code128(165, 14, $documento->digital, 30, 9);
    $pdf->Text(173, 27, $documento->digital);


    $pdf->Cell(5, 5, "", 0, 0, 'C');
    $pdf->Cell(180, 5, "", 0, 0, 'R');
    $pdf->Cell(5, 5, "", 0, 1, 'L');

    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(180, 5, "Informações do Documento", 0, 1, 'C');

    $pdf->Cell(5, 5, "", 0, 0, 'C');
    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(16, 5, "DIGITAL: ", 0, 0, 'L');
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(100, 5, $pdf->documento->digital, 0, 0, 'L');
    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(27, 5, "Data do Documento:  ", 0, 0, 'L');
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(40, 5, $pdf->documento->data_documento, 0, 1, 'R');

    $pdf->Cell(5, 5, "", 0, 0, 'C');
    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(16, 5, "Origem:", 0, 0, 'L');
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(100, 5, $pdf->documento->origem, 0, 0, 'L');
    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(27, 5, "Número Solicitação:  ", 0, 0, 'L');
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(40, 5, $pdf->documento->numero, 0, 1, 'R');

    $pdf->Cell(5, 5, "", 0, 0, 'C');
    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(16, 5, "Solicitante:", 0, 0, 'L');
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(100, 5, $pdf->documento->nome, 0, 0, 'L');

    if (!is_null($pdf->documento->cpf)) {
        // Existe CPF/CNPJ para esta solicitação
        // Descobrir qual deles
        $rotulo = "CPF:  ";

        if (strlen($pdf->documento->cpf) > 14) {
            // CNPJ
            $rotulo = "CNPJ:  ";
        }
        $pdf->SetFont("Arial", "B", 8);
        $pdf->Cell(27, 5, $rotulo, 0, 0, 'L');
        $pdf->SetFont("Arial", "", 8);
        $pdf->Cell(40, 5, $pdf->documento->cpf, 0, 0, 'R');
    } else if (!is_null($pdf->documento->email)) {
        // Existe email para esta solicitação
        $pdf->SetFont("Arial", "B", 8);
        $pdf->Cell(27, 5, "Email: ", 0, 0, 'L');
        $pdf->SetFont("Arial", "", 8);
        $pdf->Cell(40, 5, $pdf->documento->email, 0, 0, 'R');
    }

    $pdf->Ln();

    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(5, 5, "", 0, 0, 'C');
    $pdf->Cell(15, 5, "Assunto:", 0, 0, 'L');
    $pdf->SetFont("Arial", "", 8);
    $pdf->MultiCell(165, 5, DaoAssuntoDocumento::getAssunto($pdf->documento->assunto, 'assunto'), 0, 1);

    $pdf->Ln();

    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(180, 5, "Informações da Solicitação", 0, 1, 'C');

    $pdf->Ln();

//EXIBE OS REGISTROS
    $diretoria = DaoUnidade::getUnidade($pdf->documento->diretoria, 'nome');

    $pdf->Cell(8, 5, "", 0, 0, 'C');
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(40, 5, "Ao ponto focal - {$diretoria}", 0, 1, 'L');

    $pdf->Ln();

    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(8, 5, "", 0, 0, 'L');
    $pdf->Write(5, $pdf->texto);

    $pdf->Ln();
    $pdf->Ln();

    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(8, 5, "", 0, 0, 'L');
    $pdf->Write(5, $pdf->rodape);


//SAIDA DO PDF
    $pdf->Output("$end_final", "$tipo_pdf");
    return $end_final;
}