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

class Relatorio {

    /**
     * @todo Verificar se necessario continuar a funcionalidade de gráfico de gantt
     */
    public static function listGantt(/* $digital */) {
        try {

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT DISTINCT DC.DIGITAL, DC.ID_ASSUNTO, P.SQ_PRAZO, TRIM(DC.INTERESSADO) AS INTERESSADO, 
                                    DC.DT_DOCUMENTO, P.DT_PRAZO, P.DT_RESPOSTA, TRIM(A.ASSUNTO) AS NM_ASSUNTO FROM TB_DOCUMENTOS_CADASTRO DC 
                                        INNER JOIN TB_DOCUMENTOS_ASSUNTO A ON A.ID = DC.ID_ASSUNTO
                                        INNER JOIN TB_CONTROLE_PRAZOS P ON P.NU_PROC_DIG_REF = DC.DIGITAL 
                                    WHERE DC.DT_DOCUMENTO >= '2011-11-01' AND P.DT_PRAZO <= '2013-01-24'");

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print $e->getMessage();
        }
    }

}