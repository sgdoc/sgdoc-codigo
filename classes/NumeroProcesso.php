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
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */
class NumeroProcesso
{

    private $unidade = 'xxxxx'; //codigo da unidade com 5 digitos
    private $sequencial = 'xxxxxx'; // sequencial referenciado ao codigo da unidade com 6 digitos
    private $ano = 'xxxx'; // ano com 4 digitos
    private $campo = array();
    private $valor = array();
    private $soma1 = 0;
    private $soma2 = 0;
    private $resto1 = 0;
    private $resto2 = 0;
    private $dv1 = 'x';
    private $dv2 = 'x';
    private $listar = "";
    private $numero_processo = "";
    private $numeros = "";

    /**
     * 
     */
    public function __construct ($unidade, $sequencial, $ano)
    {
        $this->unidade = $unidade;
        $this->sequencial = $sequencial;
        $this->ano = $ano;
        $this->listar = $unidade . "." . $sequencial . "/" . $ano;
        $this->criarDV1();
        $this->criarDV2();
    }

    /**
     * 
     */
    public function mostrarNumeroProcesso ()
    {
        $this->numero_processo = $this->listar . "-" . $this->dv1 . $this->dv2;
        return $this->numero_processo;
    }

    /**
     * 
     */
    private function criarDV1 ()
    {
        /* INICIO - Bloco verificador - DV1 */

// limpar string
        for ($i = 0; $i <= 16; $i++) {
            if ($i != 5 && $i != 12) {
                $this->numeros .= substr($this->listar, $i, 1);
            }
        }

        for ($i = 0; $i <= 16; $i++) {
            $this->campo[$i] = substr($this->numeros, $i, 1);
        }

        $posicao = 2;
        for ($i = 14; $i >= 0; $i--) {
            $this->valor[$i] = ($this->campo[$i] * $posicao);
            $posicao++;
        }

        for ($i = 0; $i <= 16; $i++) {
            $this->soma1 = $this->valor[$i] + $this->soma1;
        }

        $this->resto1 = $this->soma1 % 11;
        $aux = $this->dv1 = (11 - $this->resto1);
        if (strlen($aux) > 1) {
            $this->dv1 = substr($aux, 1, 1);
        } else {
            $this->dv1 = $aux;
        }


        /* FINAL - Bloco verificador - DV1 */
    }

    /**
     * 
     */
    private function criarDV2 ()
    {
        /* INICIO - Bloco verificador - DV2 */

        $this->numeros = "";

        for ($i = 16; $i >= 0; $i--) {
            $this->numeros .= $this->campo[$i];
        }

        $this->numeros = $this->dv1 . $this->numeros;

        $posicao = 2;
        for ($i = 0; $i <= 15; $i++) {
            $this->campo[$i] = substr($this->numeros, $i, 1);
            $this->valor[$i] = ($this->campo[$i] * $posicao);
            $posicao++;
        }

        for ($i = 0; $i <= 16; $i++) {
            $this->soma2 = $this->valor[$i] + $this->soma2;
        }

        $this->resto2 = $this->soma2 % 11;
        $aux = $this->dv2 = (11 - $this->resto2);
        if (strlen($aux) > 1) {
            $this->dv2 = substr($aux, 1, 1);
        } else {
            $this->dv2 = $aux;
        }

        /* FINAL - Bloco verificador - DV2 */
    }

}