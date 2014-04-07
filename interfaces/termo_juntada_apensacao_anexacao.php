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

$auth = Zend_Auth::getInstance()->getStorage()->read();

$nuprocesso = $_GET['numero_processo'];
$tipo_acao = $_GET['tipo_acao'];
$file = '';
$tipo_vinc = 0;

/*
 * 1 - anexar
 * 2 - apensar - TB_VINCULACAO
 */

$dir = __BASE_PATH__ . "/rtf/";
if ($tipo_acao == "apensar") {
    $file = "juntada_apensacao.rtf";
    $tipo_vinc = 2/* apenso - TB_VINCULACAO */;
} else if ($tipo_acao == "anexar") {
    $file = "juntada_anexacao.rtf";
    $tipo_vinc = 1/* anexo - TB_VINCULACAO */;
}

$iterator = new ArrayIterator(processosVinc($nuprocesso, $tipo_vinc, $auth->ID));

$cont = 0;
$apensos = null;
$datafile = array();

while ($iterator->valid()) {
    $current = $iterator->current();
    $datafile['data'] = format($current['DT_INCLUSAO_FORM']);
    $datafile['processo'] = $current['NU_PROCESSO'];
    $datafile['solicitante'] = $current['SETOR'];

    if ($cont < $iterator->count() - 1) {
        $cont == 0 ? $apensos .= $current['NM_APENSO'] : $apensos .= ", " . $current['NM_APENSO'];
    } else {
        $iterator->count() == 1 ? $apensos .= $current['NM_APENSO'] : $apensos .= " e " . $current['NM_APENSO'];
    }

    $datafile['apensados'] = $apensos;
    $iterator->next();
    $cont++;
}

$fp = fopen($dir . $file, "r");
$output = fread($fp, filesize($dir . $file));
fclose($fp);

$output = str_replace("<<DATA>>", $datafile['data'], $output);
$output = str_replace("<<DIRETORIA>>", $datafile['solicitante'], $output);
$output = str_replace("<<NUMERO_PROCESSO>>", $datafile['processo'], $output);
$output = str_replace("<<APENSADOS>>", $datafile['apensados'], $output);
$output = str_replace("<<DIRETORIA_LOGADO>>", DaoUnidade::getUnidade($auth->ID_UNIDADE, 'nome'), $output);
$output = str_replace("<<NOME_COMPLETO>>", strtoupper($auth->NOME), $output);

/**
 * Output
 */
header("Content-type: application/msword; charset=iso-8859-1;");
header("Content-Disposition: inline, filename=$file");
print($output);

/**
 * @todo Refatorar...
 * @deprecated
 */
function processosVinc ($processo, $tipo_vinc, $idUsuario)
{
    $id = getIdProcesso($processo);
    
    

    $sql = "SELECT MAX(BLOCO_IMPRESSAO) AS BLOCO 
              FROM TB_PROCESSOS_VINCULACAO 
             WHERE ID_PROCESSO_PAI = ? 
               AND ID_VINCULACAO = ?";

    $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
    $stmt->bindParam(1, $id);
    $stmt->bindParam(2, $tipo_vinc);
    $stmt->execute();
    $bloco = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT A.*
                 , P.NUMERO_PROCESSO AS NU_PROCESSO
                 , C.NUMERO_PROCESSO AS NM_APENSO
                 , U.NOME AS SETOR
              FROM TB_PROCESSOS_VINCULACAO AS A
        INNER JOIN TB_PROCESSOS_CADASTRO AS P 
                ON A.ID_PROCESSO_PAI = P.ID
        INNER JOIN TB_PROCESSOS_CADASTRO AS C 
                ON A.ID_PROCESSO_FILHO = C.ID
        INNER JOIN TB_UNIDADES AS U 
                ON A.ID_SOLICITANTE = U.ID
             WHERE A.ID_PROCESSO_PAI = ? 
               AND A.ST_ATIVO = 1 
               AND FG_ATIVO = 1 
               AND A.BLOCO_IMPRESSAO = ? 
               AND ID_VINCULACAO = ? 
               AND ID_USUARIO = ?";

    $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
    $stmt->bindParam(1, $id);
    $stmt->bindParam(2, $bloco['BLOCO']);
    $stmt->bindParam(3, $tipo_vinc);
    $stmt->bindParam(4, $idUsuario);
    $stmt->execute();
    $resul = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resul;
}

/**
 * @todo Refatorar...
 * @deprecated
 */
function getIdProcesso ($processo)
{
    
    

    $sql = "SELECT ID 
              FROM TB_PROCESSOS_CADASTRO 
             WHERE NUMERO_PROCESSO = ? 
             LIMIT 1";

    $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);

    $stmt->bindParam(1, $processo);
    $stmt->execute();
    $resul = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resul['ID'];
}

/**
 * @todo Refatorar...
 * @deprecated
 */
function format ($data)
{
    $format = str_replace("00:00:00", '', $data);
    $format = explode("-", $format);
    $format = implode("/", array_reverse($format));
    return $format;
}
