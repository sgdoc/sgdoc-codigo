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

class Etiqueta extends Base {

    private $etiquetas;
    private $nome_pdf;
    private $tipo_pdf;
    private $paginas = 0;
    private $digitais = array();
    private $pdf;
    private $count;

    /**
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->etiquetas = 65; //Quantidade de etiquetas por página
        $this->nome_pdf = 'Etiquetas.pdf';
        $this->tipo_pdf = 'I'; //Tipo de saida do pdf
        $this->count = 0;
    }

    /**
     * @return void
     */
    private function setPaginas($registros) {
        $this->paginas = ceil($registros / $this->etiquetas);
    }

    /**
     * @return Output
     * @param integer $paginas
     * @param integer $unidade
     */
    public static function ativarEtiquetas($paginas, $unidade) {
        new Base();

        $query = '';
        $digital = '';

        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
            SELECT LOTE, ID 
            FROM TB_DIGITAL 
            ORDER BY ID 
            DESC LIMIT 1
        ");
        $stmt->execute();

        $out = $stmt->fetch(PDO::FETCH_ASSOC);

        /* Setar as variaveis */
        $inicio = (string) str_pad(($out['ID'] + 1), 7, "0", STR_PAD_LEFT);
        $paginas = ($paginas * 65); //total de etiquetas
        $marcador = ($paginas + $out['ID'] + 1);
        $lote = $out['LOTE'] + 1;

        $query = "
            INSERT INTO TB_DIGITAL 
            (DIGITAL,LOTE,ID_UNIDADE) VALUES (?,?,?)
        ";
        $stmd = Controlador::getInstance()->getConnection()->connection->prepare($query);

        /* Loop para a insercao dos registros na tabela DIGITAL */
        for ($contador = ($out['ID'] + 1); $contador < $marcador; ++$contador) {
            $digital = (string) str_pad($contador, 7, "0", STR_PAD_LEFT);
            $stmd->execute(array($digital, $lote, $unidade));
        }

        return new Output(array('success' => 'true', 'lote' => $lote, 'inicio' => $inicio, 'fim' => $digital));
    }

    /**
     * @return void
     */
    private function setDigitais($idUnidade, $lote) {

        $extraQuery = '';

        if (!is_null($lote)) {
            print $extraQuery = "AND LOTE = ?";
        }

        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT DIGITAL FROM TB_DIGITAL WHERE USO != '1' AND ID_UNIDADE = ? AND ID_USUARIO IS NULL {$extraQuery}");
        $stmt->bindParam(1, $idUnidade, PDO::PARAM_INT);

        if (!is_null($lote)) {
            $stmt->bindParam(2, $lote, PDO::PARAM_STR);
        }

        $stmt->execute();

        $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->setPaginas(count($out));

        foreach ($out as $value) {
            $this->digitais[] = $value['DIGITAL'];
        }
    }

    /**
     * @return void
     */
    private function prepararPdf() {
        $this->pdf = new PDF_Code128('P', 'cm', 'A4');
        $this->pdf->SetMargins(0, 0, 0, 0);
        $this->pdf->SetTitle('Folha de Etiquetas');
        $this->pdf->SetFont("Arial", "", 9);
        $this->pdf->Open();
    }

    /**
     * @return void
     * @param integer $linha
     */
    private function gerarLinhas($linha) {

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

        for ($ws = 1; $ws <= 5; $ws++) {

            $valor = $this->digitais[$this->count];

            if ($this->digitais[$this->count]) {

                if ($ws != 5) {

                    $this->pdf->Text($xTexto, $yTexto, __ETIQUETA__);
                    $this->pdf->Code128($xImagem, $yImagem, $valor, 3, 0.9);

                    if ($linha == 1) {
                        $this->pdf->Text($xTexto + 0.3, ($yTexto + 1.3), $valor);
                    } else {
                        $this->pdf->Text($xTexto + 0.3, ($yTexto + 1.3), $valor);
                    }

                    $this->count++;

                    if ($ws != 4) {

                        if ($ws == 1) {
                            $xImagem = $xImagem + 4.2;
                            $xTexto = $xTexto + 4.2;
                        } else {
                            if ($ws != 2) {
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

                    $this->pdf->Text($xTexto, $yTexto, __ETIQUETA__);
                    $this->pdf->Code128($xImagem, $yImagem, $valor, 3, 0.9);

                    if ($linha == 1) {
                        $this->pdf->Text($xTexto + 0.3, ($yTexto + 1.4), $valor);
                    } else {
                        $this->pdf->Text($xTexto + 0.3, ($yTexto + 1.3), $valor);
                    }

                    $this->count++;
                }
            }
        }
    }

    /**
     * @return void
     */
    public function gerarEtiquetas($idUnidade, $lote = NULL) {

        $this->prepararPdf();
        $this->setDigitais($idUnidade, $lote);

        for ($i = 1; $i <= $this->paginas; $i++) {

            $this->pdf->AddPage();

            for ($x = 1; $x <= 13; $x++) {

                $this->gerarLinhas($x);
            }
        }

        $this->pdf->Output($this->nome_pdf, $this->tipo_pdf);
    }

}