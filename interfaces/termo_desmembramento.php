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

$idUsuario = $auth->ID;
$nuprocesso = $_GET['numero_processo'];
$idtermo = $_GET['termo'];

$resul = getTermo($nuprocesso, $idtermo, $idUsuario);
$datafile = array();
setlocale(LC_ALL, array('pt_BR', 'pt_BR.iso88591', 'pt_BR.utf8'));

if (!empty($resul)) {
    $datafile['data'] = formatd($resul['DT_ACAO']);
    $datafile['solicitante'] = $resul['SOLICITANTE'];
    $datafile['processo'] = $resul['PPROCESSO'];
    $datafile['pecas'] = format($resul['NUMERO_PECA']);
    $datafile['processo_'] = $resul['NPROCESSO'];
}

$dir = __BASE_PATH__ . "/rtf/";
$file = "termo_desmembramento.rtf";
$fp = fopen($dir . $file, "r");
$output = fread($fp, filesize($dir . $file));
fclose($fp);

$output = str_replace("<<DATA>>", $datafile['data'], $output);
$output = str_replace("<<DIRETORIA>>", $datafile['solicitante'], $output);
$output = str_replace("<<NUMERO_PROCESSO>>", $datafile['processo'], $output);
$output = str_replace("<<NUMERO_PROCESSO_>>", $datafile['processo_'], $output);
$output = str_replace("<<PECAS>>", $datafile['pecas'], $output);
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
function getTermo($processo, $idtermo, $idUsuario)
{
    $id = Util::RecuperaIdProcesso($processo);
    
    

    $sql = "SELECT D.*
                 , P.NUMERO_PROCESSO AS PPROCESSO
                 , E.NUMERO_PROCESSO AS NPROCESSO
                 , (SELECT NOME 
                      FROM TB_UNIDADES 
                     WHERE (ID != UOP) AND SIGLA = U.SIGLA LIMIT 1) AS SOLICITANTE
              FROM TB_PROCESSOS_DESMEMBRAMENTO AS D
        INNER JOIN TB_PROCESSOS_CADASTRO AS P 
                ON D.NUMERO_PROCESSO = P.ID
        INNER JOIN TB_PROCESSOS_CADASTRO AS E 
                ON D.NOVO_PROCESSO = E.ID
        INNER JOIN TB_UNIDADES AS U 
                ON D.ID_SOLICITANTE = U.ID
             WHERE D.NUMERO_PROCESSO = ? 
               AND ID_USUARIO = ? 
               AND ID_DESMEM = ? 
          ORDER BY ID_DESMEM DESC 
             LIMIT 1";

    $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
    $stmt->bindParam(1, $id);
    $stmt->bindParam(2, $idUsuario);
    $stmt->bindParam(3, $idtermo);
    $stmt->execute();
    $resul = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resul;
}

/**
 * @todo Refatorar...
 * @deprecated
 */
function formatd ($data)
{
    $data2 = strtotime($data);
    $dados = getdate($data2);
    $dia = $dados['mday'];
    $mes2 = $dados['mon'];
    $ano = $dados['year'];
    $retorno = strftime("%e de %B de %Y", mktime(0, 0, 0, $mes2, $dia, $ano));
    return str_replace("março", "marco", $retorno);
}

/**
 * @todo Refatorar...
 * @deprecated
 */
function format ($value)
{
    $pecas = str_replace("-", " a ", $value);
    $exp = str_replace(",", ", ", $pecas);
    return $exp;
}
