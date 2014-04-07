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

session_start();

include "../administrador/class.phpmailer.php";
include "../classes/LogError.php";
include "../classes/BasePDOException.php";
include "../classes/Config.php";
include "../classes/Connection.php";
include "../classes/Base.php";
include "../classes/Documento.php";
include "../classes/Session.php";
include "../classes/Usuario.php";

/**
 * @author Carlos Eduardo Santos
 */
class graficos
{

    /**
     *
     */
    public function grafico_por_periodo ()
    {
        $dados = array();

        $documento = new Documento();

        $dia = date('d', time());
        $mes = date('m', time());
        $ano = date('Y', time());

        $dataInicio = mktime(0, 0, 0, $mes, $dia - 7, $ano);
        $dataInicio = date('Y-m-d', $dataInicio);

        $documento->dataInicio = $dataInicio;
        $documento->dataAtual = $dataAtual = "{$ano}-{$mes}-{$dia}";
        $daoDocumento = new DaoDocumento();
        $dados = $daoDocumento->getQtdeDocumentoImagensPorPeriodo($documento);

        echo json_encode($dados);
        exit();
    }

    /**
     *
     */
    public function grafico_diario ()
    {
        $dados = array();
        if (isset($_REQUEST['datInclusao'])) {
            $documento = new Documento();
            $documento->datInclusao = $_REQUEST['datInclusao'];
            $daoDocumento = new DaoDocumento();
            $dados = $daoDocumento->getQtdeDocumentoImagensDataInclusao($documento);
        }
        echo json_encode($dados);
        exit();
    }

}

/**
 * 
 */
if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0) {
    $grafico = new graficos;
    if (isset($_POST['grafico_por_periodo'])) {
        $grafico->grafico_por_periodo();
    }
    if (isset($_POST['grafico_diario'])) {
        $grafico->grafico_diario();
    }
}