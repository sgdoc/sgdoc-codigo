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

class CFModelDocumentoDemanda extends CFModelDocumento {

    /**
     * Associa um documento a outro na tabela TB_DOCUMENTOS_VINCULACAO
     * @author Bruno Pedreira
     * Data: 07/12/2013
     * @param int $idDocumentoPai
     * @param int $idDocumentoFilho
     * @param int $idUsuario
     * @param int $idHistoricoTramitePai
     * @param int $idHistoricoTramiteFilho
     * @param int $idVinculacao
     * @param int $idUnidade
     * @return type
     * @throws PDOException
     */
    public function associarDocumentos($idDocumentoPai, $idDocumentoFilho, $idUsuario, $idUnidade, $usuario, $unidade, $origem, $destino) {
        try {

            $stmt = $this->_conn->prepare("INSERT INTO tb_documentos_vinculacao 
                                              (id_documento_pai, id_documento_filho, id_usuario, 
                                              id_historico_tramite_pai, id_historico_tramite_filho, 
                                              id_vinculacao, id_unidade, dt_ativacao) 
                                              VALUES (?,?,?,?,?,?,?,?)");

            $idHistoricoTramitePai = $this->criarHistoricoDocumentoAssociado($idDocumentoPai, $idDocumentoFilho, $idUsuario, $usuario, $idUnidade, $unidade, $origem, $destino, true);
            $idHistoricoTramiteFilho = $this->criarHistoricoDocumentoAssociado($idDocumentoPai, $idDocumentoFilho, $idUsuario, $usuario, $idUnidade, $unidade, $origem, $destino, false);

            $idVinculacao = 3;
            $dtAtivacao = date('Y-m-d');

            $stmt->bindParam(1, $idDocumentoPai, PDO::PARAM_INT);
            $stmt->bindParam(2, $idDocumentoFilho, PDO::PARAM_INT);
            $stmt->bindParam(3, $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(4, $idHistoricoTramitePai, PDO::PARAM_INT);
            $stmt->bindParam(5, $idHistoricoTramiteFilho, PDO::PARAM_INT);
            $stmt->bindParam(6, $idVinculacao, PDO::PARAM_INT); //Tipo de vinculação: 3 - Associado
            $stmt->bindParam(7, $idUnidade, PDO::PARAM_INT);
            $stmt->bindParam(8, $dtAtivacao, PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Cria um histórico de associação do documento.
     * @author Bruno Pedreira
     * Data: 11/12/2013
     * @param int $idDocumento
     * @param int $idUsuario
     * @param string $usuario
     * @param int $idUnidade
     * @param string $diretoria
     * @param string $origem
     * @param string $destino
     * @throws Exception
     */
    public function criarHistoricoDocumentoAssociado($idDocumentoPai, $idDocumentoFilho, $idUsuario, $usuario, $idUnidade, $diretoria, $origem, $destino, $isPai) {

        try {
            $idDocumento = ($isPai) ? $idDocumento = $idDocumentoPai : $idDocumento = $idDocumentoFilho;

            $digital = current(CFModelDocumento::factory()->find($idDocumento))->DIGITAL;

            $acao = "";

            $_REQUEST['DIGITAL'] = $digital;
            $_REQUEST['ID_USUARIO'] = $idUsuario;
            $_REQUEST['USUARIO'] = $usuario;
            $_REQUEST['ID_UNIDADE'] = $idUnidade;
            $_REQUEST['DIRETORIA'] = $diretoria;
            $_REQUEST['ORIGEM'] = $origem;
            $_REQUEST['DESTINO'] = $destino;

            if ($isPai != true) {
                $digitalPai = current(CFModelDocumento::factory()->find($idDocumentoPai))->DIGITAL;
                $acao = "Associado ao documento principal {$digitalPai}.";
            } else {
                $digitalFilho = current(CFModelDocumento::factory()->find($idDocumentoFilho))->DIGITAL;
                $acao = "O documento {$digitalFilho} foi associado.";
            }

            return CFModelDocumentoHistoricoTramite::factory()->insert(array(
                        'DIGITAL' => $_REQUEST['DIGITAL'],
                        'ID_USUARIO' => $_REQUEST['ID_USUARIO'],
                        'USUARIO' => $_REQUEST['USUARIO'],
                        'ID_UNIDADE' => $_REQUEST['ID_UNIDADE'],
                        'DIRETORIA' => $_REQUEST['DIRETORIA'],
                        'ACAO' => $acao,
                        'ORIGEM' => $_REQUEST['ORIGEM'],
                        'DESTINO' => $_REQUEST['DESTINO'],
                        'DT_TRAMITE' => date('Y-m-d H:i:s'),
                        'ST_ATIVO' => 1
            ));
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function retrieveCamposExtraPrioridadeByDigital($digital) {
        try {
            $stmt = $this->_conn->prepare("SELECT Campos.id as ID
                                                , Prioridade.prioridade
                                                , Campos.id_prioridade as ID_CAMPO
                                           FROM sgdoc.ext__snas__tb_documentos_campos as Campos
                                                INNER JOIN sgdoc.tb_prioridade as Prioridade on (Campos.id_prioridade = Prioridade.id)
                                            AND Campos.digital = ?
                                            AND Campos.ativo   = 1
                                            AND Campos.tipo    = 'PR'
                                           ORDER BY Prioridade.prioridade");
            $stmt->bindParam(1, $digital, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @author Michael Fernandes <cerberosnash@gmail.com>
     * @param integer $id
     */
    public function retrieveConteudoDocumentoById($id) {
        try {
            $stmt = $this->_conn->prepare("SELECT conteudo FROM SGDOC.ext__snas__tb_documentos_conteudo WHERE id = ? LIMIT 1");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();

            $out = $stmt->fetch(PDO::FETCH_OBJ);

            if ($out instanceof stdClass) {
                return $out->CONTEUDO;
            }

            return '';
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function retrieveCamposExtraParticipantesByDigital($digital) {
        try {
            $stmt = $this->_conn->prepare("SELECT Campos.id as ID
                                                , Pessoa.nm_pessoa as NOME,
                                                Campos.id_pessoa as ID_CAMPO
                                           FROM sgdoc.ext__snas__tb_documentos_campos as Campos
                                                INNER JOIN sgdoc.tb_pessoa as Pessoa on (Campos.id_pessoa = Pessoa.id_pessoa)
                                            AND Campos.digital = ?
                                            AND Campos.ativo   = 1
                                            AND Campos.tipo    = 'PA'
                                           ORDER BY Pessoa.nm_pessoa");
            $stmt->bindParam(1, $digital, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw $e;
        }
    }

}
