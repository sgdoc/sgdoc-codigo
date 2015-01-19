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
class DocumentoDemanda extends Documento {

    /**
     *  Verificar se o documento informado esta na area de trabalho do usuario logado 
     */
    public static function validarDocumentoAreaDeTrabalho($digital, $unidade = false) {
        try {

            $unidade = $unidade ? $unidade : Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT 1 FROM TB_DOCUMENTOS_CADASTRO WHERE DIGITAL = ? AND ID_UNID_AREA_TRABALHO = ? LIMIT 1");
            $stmt->bindParam(1, $digital, PDO::PARAM_STR);
            $stmt->bindParam(2, $unidade, PDO::PARAM_INT);
            $stmt->execute();

            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            throw new BasePDOException($e);
        }
    }

}
