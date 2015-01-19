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

$resul = getTermo($nuprocesso, $idUsuario);
$datafile = array();
setlocale(LC_ALL, array('pt_BR', 'pt_BR.iso88591', 'pt_BR.utf8'));

if (!empty($resul)) {
    $datafile['data'] = format($resul['DT_ENCERRAMENTO']);
    $datafile['volume'] = $resul['NU_VOLUME'];
    $datafile['diretoria'] = $resul['DIRETORIA'];
    $datafile['processo'] = $resul['NUMERO_PROCESSO'];
    $datafile['folhas'] = $resul['FOLHAS'];
    $datafile['proximo'] = $resul['PROX_VOLUME'];
}

$dir = __BASE_PATH__ . "/rtf/";
$file = "encerramento_volume.rtf";
$fp = fopen($dir . $file, "r");
$output = fread($fp, filesize($dir . $file));
fclose($fp);

$output = str_replace("<<DIRETORIA>>", $datafile['diretoria'], $output);
$output = str_replace("<<DATA>>", $datafile['data'], $output);
$output = str_replace("<<VOLUME>>", $datafile['volume'], $output);
$output = str_replace("<<NUMERO_PROCESSO>>", $datafile['processo'], $output);
$output = str_replace("<<FOLHAS>>", $datafile['folhas'], $output);
$output = str_replace("<<PROX_VOLUME>>", $datafile['proximo'], $output);
$output = str_replace("<<DIRETORIA_LOGADO>>", DaoUnidade::getUnidade( $auth->ID_UNIDADE, 'nome'), $output);
$output = str_replace("<<NOME_COMPLETO>>", utf8_decode( strtoupper( $auth->NOME) ), $output);

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
function getTermo($processo, $idUsuario) {
    $id = Util::RecuperaIdProcesso($processo);
    
    /**
     * TODO: modificar estrutura de usuario.ID_UNIDADE
     * AJUSTE REALIZADO NO SQL PARA RETORNAR SOMENTE UMA DAS UNIDADES, PORÉM
     * FALTA AINDA PERSISTIR EM TB_PROCESSOS_CADASTRO A INFORMAÇÃO DA UNIDADE
     * E REFAZER A CONSULTA
     */
    $sql = "
        SELECT
            A.*
            , U.NUMERO_PROCESSO
            , ( SELECT NOME 
                FROM TB_UNIDADES UN
                    INNER JOIN TB_USUARIOS_UNIDADES UU ON UU.ID_UNIDADE = UN.ID
                WHERE (UN.ID != UOP) AND U.ID = UU.ID_USUARIO
                LIMIT 1) AS DIRETORIA 
        FROM TB_PROCESSOS_VOLUME AS A
            INNER JOIN TB_PROCESSOS_CADASTRO AS U ON A.ID_PROCESSO_CADASTRO = U.ID
            INNER JOIN TB_USUARIOS AS B ON A.ID_USUARIO = B.ID
        WHERE A.ID_PROCESSO_CADASTRO = ? 
            AND A.DT_ENCERRAMENTO IS NOT NULL 
            AND A.ID_USUARIO =  ? 
            AND (A.ST_ATIVO = 1 AND A.FG_ATIVO = 1) 
        ORDER BY ID DESC 
        LIMIT 1
    ";

    $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);

    $stmt->bindParam(1, $id);
    $stmt->bindParam(2, $idUsuario);
    $stmt->execute();
    $resul = $stmt->fetch(PDO::FETCH_ASSOC);
    $resul['FOLHAS'] = $resul['FL_FINAL'] - $resul['FL_INICIAL'] + 1;
    $resul['PROX_VOLUME'] = $resul['NU_VOLUME'] + 1;

    return $resul;
}

/**
 * @todo Refatorar...
 * @deprecated
 */
function format($data) {
    $data2 = strtotime($data);
    $dados = getdate($data2);
    $dia = $dados['mday'];
    $mes2 = $dados['mon'];
    $ano = $dados['year'];
    $retorno = strftime("%e dia(s) do mes de %B de %Y", mktime(0, 0, 0, $mes2, $dia, $ano));
    return str_replace("março", "marco", $retorno);
}