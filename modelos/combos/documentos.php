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

try {

    new Base();

    $caixa = (isset($_POST['caixa'])) ? $_POST['caixa'] : 1;

    if ($caixa) {
        // Foi passada uma caixa, pegar classificacoes permitidas
        $stmt0 = Controlador::getInstance()->getConnection()->connection->prepare("
            SELECT ID_CLASSIFICACAO 
            FROM TB_CAIXAS 
            WHERE ID = ? LIMIT 1
        ");
        $stmt0->bindParam(1, $caixa, PDO::PARAM_INT);
        $stmt0->execute();
        $out = $stmt0->fetch(PDO::FETCH_ASSOC);

        $classificacao = $out['ID_CLASSIFICACAO'];

        /**
         * @todo Limitação a 1000 registros, feita por segurança pois a maior parte
         * dos registros encontra-se na classificação A CLASSIFICAR, e o número de
         * registros retornáveis neste caso é muito grande, causando estouro de memória
         * e não faz sentido para a finalidade do SCRIPT de COMBOS. Analisar solução
         * permantente.
         */
        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
            SELECT 
                VW_DOC_ARQ.ID, 
                VW_DOC_ARQ.DIGITAL, 
                VW_DOC_ARQ.ASSUNTO 
            FROM 
                VW_DOCUMENTOS_ARQUIVO VW_DOC_ARQ JOIN TB_CLASSIFICACAO cla on VW_DOC_ARQ.ID_CLASSIFICACAO = cla.ID
            WHERE 
                ? IN (cla.ID_CLASSIFICACAO_PAI, cla.ID)
            ORDER BY 
                VW_DOC_ARQ.DIGITAL ASC
            LIMIT 1000
        ");//LIMITADO PROVISORIAMENTE A 1000 REGISTROS
        $stmt->bindParam(1, $classificacao, PDO::PARAM_STR);
    } else {
        // Não foi definida, pegar tudo
        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
            SELECT 
                ID, 
                DIGITAL, 
                ASSUNTO 
            FROM 
                VW_DOCUMENTOS_ARQUIVO 
            ORDER BY 
                DIGITAL ASC
            LIMIT 1000
        ");//LIMITADO PROVISORIAMENTE A 1000 REGISTROS
    }

    $stmt->execute();

    $out = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out = array_change_key_case(($out), CASE_LOWER);

    if (count($out) == 0) {
        $novo[] = array('' => 'Nenhum documento com a classificação correta');
    } else {
        $novo[] = array('' => 'Selecione o documento');
    }

    foreach ($out as $key => $value) {
        $novo[] = array($value['ID'] => $value['DIGITAL']);
    }
    print(json_encode($novo));
    
} catch (PDOException $e) {
    echo $e->getMessage();
}