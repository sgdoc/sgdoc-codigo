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
include("bibliotecas/barcode/code128.php");

class RecuperarEtiquetas {

    public $pdf = null;
    public $digitais = array();
    public $cont = 0;
    public $etiquetas = 65;
    public $slot = 0;
    public $outputType = '';

    /**
     * @return void
     */
    public function __construct($array) {

        $this->cont = count($array['DIGITAL']);

        for ($i = 1; $i <= $this->cont; $i++) {
            $this->digitais[] = $array['DIGITAL'][$i - 1];
        }

        $this->pdf = new PDF_Code128('P', 'cm', 'A4');
        $this->pdf->SetMargins(0, 0, 0, 0);
        $this->pdf->SetTitle('Folha de Etiquetas');
        $this->pdf->SetFont("Arial", "", 9);
        $this->pdf->Open();

        $this->paginas = ceil($this->cont / $this->etiquetas);
    }

    /**
     * @return void
     */
    public function outputPDF() {
        $this->pdf->Output($this->outputType);
    }

    /**
     * @return void
     */
    public function buildPaginas() {
        /**
         * Construir pagina
         */
        for ($pagina = 1; $pagina <= $this->paginas; $pagina++) {
            /**
             * Add Page
             */
            $this->pdf->AddPage();

            for ($linha = 1; $linha <= 13; $linha++) {
                $this->addEtiqueta($this->pdf, $linha, $this->digitais);
            }
        }
    }

    /**
     * @return void
     * @param FPDF $pdf
     * @param integer $linha
     * @param array $digitais
     */
    public function addEtiqueta($pdf, $linha, $digitais) {

        $yImagem = 0.0;
        $yTexto = 0.0;

        switch ($linha) {
            case 1:
                $yImagem = 1.8;
                $yTexto = $yImagem - 0.1;
                break;

            case 2:
                $yImagem = 3.8;
                $yTexto = $yImagem - 0.1;
                break;

            case 3:
                $yImagem = 6.0;
                $yTexto = $yImagem - 0.1;
                break;

            case 4:
                $yImagem = 8.1;
                $yTexto = $yImagem - 0.1;
                break;

            case 5:
                $yImagem = 10.3;
                $yTexto = $yImagem - 0.1;
                break;

            case 6:
                $yImagem = 12.3;
                $yTexto = $yImagem - 0.1;
                break;

            case 7:
                $yImagem = 14.5;
                $yTexto = $yImagem - 0.1;
                break;

            case 8:
                $yImagem = 16.5;
                $yTexto = $yImagem - 0.1;
                break;

            case 9:
                $yImagem = 18.7;
                $yTexto = $yImagem - 0.1;
                break;

            case 10:
                $yImagem = 20.8;
                $yTexto = $yImagem - 0.1;
                break;

            case 11:
                $yImagem = 22.9;
                $yTexto = $yImagem - 0.1;
                break;

            case 12:
                $yImagem = 25.0;
                $yTexto = $yImagem - 0.1;
                break;

            case 13:
                $yImagem = 27.2;
                $yTexto = $yImagem - 0.1;
                break;
        }

        $xImagem = 0.9;
        $xTexto = 1.5;


        for ($slot = 1; $slot <= 5; $slot++) {
            if (isset($digitais[$this->slot])) {

                /**
                 * Valor do Digital
                 */
                $valor = $digitais[$this->slot];

                if ($slot != 5) {
                    $pdf->Text($xTexto, $yTexto, " " . __ETIQUETA__);
                    $pdf->Code128($xImagem, $yImagem, $valor, 3, 0.9);
                    if ($linha == 1) {
                        $pdf->Text($xTexto + 0.3, ($yTexto + 1.3), $valor);
                    } else {
                        $pdf->Text($xTexto + 0.3, ($yTexto + 1.3), $valor);
                    }
                    $this->slot++;

                    if ($slot != 4) {

                        if ($slot == 1) {
                            $xImagem = $xImagem + 4.2;
                            $xTexto = $xTexto + 4.2;
                        } else {
                            if ($slot != 2) {
                                $xImagem = $xImagem + 4.2 - 0.1;
                                $xTexto = $xTexto + 4.2 - 0.1;
                            } else {
                                $xImagem = $xImagem + 4.2 - 0.4;
                                $xTexto = $xTexto + 4.2 - 0.4;
                            }
                        }
                    }
                } else {
                    $xImagem = $xImagem + 4.0;
                    $xTexto = $xTexto + 4.0;
                    $yImagem = $yImagem;
                    $yTexto = $yTexto;
                    $pdf->Text($xTexto, $yTexto, __ETIQUETA__);
                    $pdf->Code128($xImagem, $yImagem, $valor, 3, 0.9);
                    if ($linha == 1) {
                        $pdf->Text($xTexto + 0.3, ($yTexto + 1.4), $valor);
                    } else {
                        $pdf->Text($xTexto + 0.3, ($yTexto + 1.3), $valor);
                    }
                    $this->slot++;
                }
            }
        }
    }

}