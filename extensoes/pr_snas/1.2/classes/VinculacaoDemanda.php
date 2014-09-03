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
include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.2/classes/DocumentoDemanda.php');

/**
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */
class VinculacaoDemanda extends Vinculacao {

    /**
     * Operacao Vincular documentos a documentos
     * @return Output
     * @param string $pai
     * @param string $filho
     * @param integer $vinculacao
     */
    public function vincularDocumento($pai, $filho, $vinculacao) {

        try {
            /* Validar se os documentos pai e filho ainda estao na area de trabalho do usuario */
            /* Inicar transacao */
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $dt_ativacao = date('Y-m-d');
            //$operacao = ($vinculacao == 1) ? 'anexado' : 'apensado';

            switch ($vinculacao) {
                case 1:
                    $operacao = 'anexado';
                    break;
                case 2:
                    $operacao = 'apensado';
                    break;
                case 3:
                    $operacao = 'associado';
                    break;
                default:
                    $operacao = 'erro';
                    break;
            }

            if ($operacao != 'erro') {

                $informacoes = array('USUARIO' => Zend_Auth::getInstance()->getIdentity()->ID,
                    'NOME_USUARIO' => Zend_Auth::getInstance()->getIdentity()->NOME,
                    'ID_UNIDADE_HISTORICO' => Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE,
                    'DIRETORIA' => current(CFModelUnidade::factory()->find(Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE))->NOME,
                    'ID_DOCUMENTO' => 0,
                    'TIPO_DOCUMENTO' => '',
                    'ID_PAI' => 0,
                    'OPERACAO' => $operacao,
                    'ACAO' => '');

                //var_dump($informacoes,1);
                $acao0 = "Este documento foi {$operacao} ao documento {$pai}";
                $acao1 = "O documento {$filho} foi {$operacao}.";
                $acao2 = "Este documento foi {$operacao} ao documento {$pai}.";

                /* Historico - Documento Pai */
                $informacoes['ID_DOCUMENTO'] = $pai;
                $informacoes['ACAO'] = $acao1;
                $this->historicoDocumento($informacoes);

                /* Limpa o aray informacao para o próximo registro com id_pai */
                $informacoes['ID_PAI'] = Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_HISTORICO_TRAMITE_DOCUMENTOS_ID_SEQ');
                $informacoes['ID_DOCUMENTO'] = $filho;
                $informacoes['ACAO'] = $acao0;
                $this->historicoDocumento($informacoes);

                /**/
                $id_filho = Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_HISTORICO_TRAMITE_DOCUMENTOS_ID_SEQ');

                /* Ultimo tramite - Documento Filho */
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_CADASTRO SET ULTIMO_TRAMITE = ? WHERE DIGITAL = ?");
                $stmt->bindParam(1, $acao2, PDO::PARAM_STR);
                $stmt->bindParam(2, $filho, PDO::PARAM_STR);
                $stmt->execute();

                $id_unidade = Controlador::getInstance()->usuario->ID_UNIDADE;

                /* Vincular */
                $sttm = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_DOCUMENTOS_VINCULACAO (ID_DOCUMENTO_PAI,ID_DOCUMENTO_FILHO,ID_USUARIO,ID_HISTORICO_TRAMITE_PAI,ID_HISTORICO_TRAMITE_FILHO,ID_VINCULACAO,DT_ATIVACAO,ID_UNIDADE) VALUES (?,?,?,?,?,?,?,?)");
                $sttm->bindParam(1, DaoDocumento::getDocumento($pai, 'id'), PDO::PARAM_INT);
                $sttm->bindParam(2, DaoDocumento::getDocumento($filho, 'id'), PDO::PARAM_INT);
                $sttm->bindParam(3, $informacoes['USUARIO'], PDO::PARAM_INT);
                $sttm->bindParam(4, $informacoes['ID_PAI'], PDO::PARAM_INT);
                $sttm->bindParam(5, $id_filho, PDO::PARAM_INT);
                $sttm->bindParam(6, $vinculacao, PDO::PARAM_INT);
                $sttm->bindParam(7, $dt_ativacao, PDO::PARAM_INT);
                $sttm->bindParam(8, $id_unidade, PDO::PARAM_INT);
                $sttm->execute();

                /* Comitar */
                Controlador::getInstance()->getConnection()->connection->commit();

                return new Output(array('success' => 'true', 'message' => "Documento {$operacao} com sucesso!"));
            } else {
                throw new PDOException();
            }
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * @author Bruno Pedreira
     * Data: 19/12/2013
     * @param array $informacoes
     * @todo Armazena o histórico dos documentos    
     */
    protected function historicoDocumento($informacoes) {

        if (is_array($informacoes) && !is_null($informacoes)) {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS
                                                                (DIGITAL,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE) 
                                                                VALUES (?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP(0))");


            $stmt->bindParam(1, $informacoes['ID_DOCUMENTO'], PDO::PARAM_STR);
            $stmt->bindParam(2, $informacoes['USUARIO'], PDO::PARAM_INT);
            $stmt->bindParam(3, $informacoes['NOME_USUARIO'], PDO::PARAM_STR);
            $stmt->bindParam(4, $informacoes['ID_UNIDADE_HISTORICO'], PDO::PARAM_INT);
            $stmt->bindParam(5, $informacoes['DIRETORIA'], PDO::PARAM_STR);
            $stmt->bindParam(6, $informacoes['ACAO'], PDO::PARAM_STR);
            $stmt->bindParam(7, $origem = "XXXXX", PDO::PARAM_STR);
            $stmt->bindParam(8, $destino = "XXXXX", PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    /**
     * @param type $pai
     * @param type $filho
     * @param type $vinculacao
     * @return \Output
     */
    public function desvincularDocumento($pai, $filho, $vinculacao) {
        try {
            /* Valida se o vínculo é associação (que não tem regra de validação de área de trabalho)
             * OU
             * Validar se os documentos pai e filho ainda estao na area de trabalho do usuario
             * OU
             * Privilegio total para usuarios com permissao 
             */
            $controller = Controlador::getInstance();
            $auth = $controller->usuario;

            // permissao desanexar documento = 310114
            if ($vinculacao == 3 || DocumentoDemanda::validarDocumentoAreaDeTrabalho($pai) ||
                    ($vinculacao == 1 &&
                    AclFactory::checaPermissao($controller->acl, $auth, DaoRecurso::getRecursoById(31120616)))) {
                if ($vinculacao == 3 || DocumentoDemanda::validarDocumentoAreaDeTrabalho($filho) ||
                        ($vinculacao == 1 &&
                        AclFactory::checaPermissao($controller->acl, $auth, DaoRecurso::getRecursoById(31120616)))) {

                    switch ($vinculacao) {
                        case 1:
                            $operacao = 'desanexado';
                            break;
                        case 2:
                            $operacao = 'desapensado';
                            break;
                        case 3:
                            $operacao = 'desassociado';
                            break;
                        default:
                            break;
                    }

                    /* Inicar transacao */
                    Controlador::getInstance()->getConnection()->connection->beginTransaction();

                    /* Verificar se existe um vinculo existe ativo e quais sao os historicos de tramite */
                    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT
                            DV.ID AS ID_VINCULO,
                            HF.ID AS ID_HISTORICO_FILHO,
                             HP.ID AS ID_HISTORICO_PAI,
                             DCF.DIGITAL AS FILHO,
                             DCP.DIGITAL AS PAI
                            FROM TB_DOCUMENTOS_VINCULACAO DV
                             INNER JOIN TB_DOCUMENTOS_CADASTRO DCP ON DV.ID_DOCUMENTO_PAI = DCP.ID
                             INNER JOIN TB_DOCUMENTOS_CADASTRO DCF ON DV.ID_DOCUMENTO_FILHO = DCF.ID
                             LEFT JOIN TB_HISTORICO_TRAMITE_DOCUMENTOS HP ON DV.ID_HISTORICO_TRAMITE_PAI = HP.ID
                             LEFT JOIN TB_HISTORICO_TRAMITE_DOCUMENTOS HF ON DV.ID_HISTORICO_TRAMITE_FILHO = HF.ID
                            WHERE
                             DCP.DIGITAL = ? AND
                             DCF.DIGITAL = ? AND
                             DV.ID_VINCULACAO = ? AND
                             DV.FG_ATIVO = 1 AND DV.ST_ATIVO = 1 LIMIT 1");
                    $stmt->bindParam(1, $pai, PDO::PARAM_STR);
                    $stmt->bindParam(2, $filho, PDO::PARAM_STR);
                    $stmt->bindParam(3, $vinculacao, PDO::PARAM_INT);
                    $stmt->execute();
                    $out = $stmt->fetch(PDO::FETCH_ASSOC);

                    /* Se desanexacao */
                    if (!empty($out) && $vinculacao == 1/* Desanexacao */) {
                        /* Desvincular */
                        $sthp = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_VINCULACAO SET FG_ATIVO = 0 WHERE ID = ?");
                        $sthp->bindParam(1, $out['ID_VINCULO'], PDO::PARAM_INT);
                        $sthp->execute();
                        /* Log Vinculo */
                        new Log('TB_DOCUMENTOS_VINCULACAO', $out['ID_VINCULO'], $auth->ID, 'desanexar');

                        /* Remover historico tramite pai */
                        if (!is_null($out['ID_HISTORICO_PAI'])) {
                            $sthp = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_HISTORICO_TRAMITE_DOCUMENTOS SET ST_ATIVO = 0 WHERE ID = ?");
                            $sthp->bindParam(1, $out['ID_HISTORICO_PAI'], PDO::PARAM_INT);
                            $sthp->execute();
                            /* Log Historico Pai */
                            new Log('HISTORICO_TRAMITE', $out['ID_HISTORICO_PAI'], $auth->ID, 'excluir');
                        }
                        /* Remover historico tramite filho */
                        if (!is_null($out['ID_HISTORICO_FILHO'])) {
                            $sthf = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_HISTORICO_TRAMITE_DOCUMENTOS SET ST_ATIVO = 0 WHERE ID = ?");
                            $sthf->bindParam(1, $out['ID_HISTORICO_FILHO'], PDO::PARAM_INT);
                            $sthf->execute();
                            /* Log Historico Filho */
                            new Log('HISTORICO_TRAMITE', $out['ID_HISTORICO_FILHO'], $auth->ID, 'excluir');
                        }
                    }

                    /* Se desapensacao */
                    if (!empty($out) && $vinculacao == 2/* Desapensacao */) {

                        /* Variaveis do historico de tramite */
                        $acao_filho = "Este documento foi {$operacao} do documento {$pai}"; // historico filho
                        $acao_pai = "O documento {$filho} foi {$operacao}."; // historico pai
                        $ultimo_tramite = "Este documento foi {$operacao} do documento {$pai}."; //ultimo tramite filho
                        $destino = "XXXXX";
                        $origem = "XXXXX";

                        $id_usuario = $auth->ID;
                        $id_unidade = $auth->ID_UNIDADE_ORIGINAL;
                        $nome_usuario = $auth->NOME;
                        $diretoria = DaoUnidade::getUnidade($id_unidade, 'nome');

                        /* Desvincular */
                        $sthp = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_VINCULACAO SET FG_ATIVO = 0 WHERE ID = ?");
                        $sthp->bindParam(1, $out['ID_VINCULO'], PDO::PARAM_INT);
                        $sthp->execute();

                        /* Adicionar historico tramite pai */
                        $sthp = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS"
                                . " (DIGITAL,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                                . " VALUES (?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP(0))");
                        $sthp->bindParam(1, $pai, PDO::PARAM_STR); //digital pai
                        $sthp->bindParam(2, $id_usuario, PDO::PARAM_INT); //id usuario
                        $sthp->bindParam(3, $nome_usuario, PDO::PARAM_STR); //nome usuario
                        $sthp->bindParam(4, $id_unidade, PDO::PARAM_INT); //id unidade
                        $sthp->bindParam(5, $diretoria, PDO::PARAM_STR); //nome diretoria
                        $sthp->bindParam(6, $acao_pai, PDO::PARAM_STR); //acao pai
                        $sthp->bindParam(7, $origem, PDO::PARAM_STR); //origem
                        $sthp->bindParam(8, $destino, PDO::PARAM_STR); //destino
                        $sthp->execute();


                        /* Adicionar historico tramite filho */
                        $sthf = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS"
                                . " (DIGITAL,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                                . " VALUES (?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP(0))");
                        $sthf->bindParam(1, $filho, PDO::PARAM_STR); //digital filho
                        $sthf->bindParam(2, $id_usuario, PDO::PARAM_INT); //id usuario
                        $sthf->bindParam(3, $nome_usuario, PDO::PARAM_STR); //nome usuario
                        $sthf->bindParam(4, $id_unidade, PDO::PARAM_INT); //id unidade
                        $sthf->bindParam(5, $diretoria, PDO::PARAM_STR); //nome diretoria
                        $sthf->bindParam(6, $acao_filho, PDO::PARAM_STR); //acao filho
                        $sthf->bindParam(7, $origem, PDO::PARAM_STR); //origem
                        $sthf->bindParam(8, $destino, PDO::PARAM_STR); //destino
                        $sthf->execute();

                        /* Ultimo tramite - Documento Filho */
                        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_CADASTRO SET ULTIMO_TRAMITE = ? WHERE DIGITAL = ?");
                        $stmt->bindParam(1, $ultimo_tramite, PDO::PARAM_STR); //acao
                        $stmt->bindParam(2, $filho, PDO::PARAM_STR); //digital filho
                        $stmt->execute();
                    }

                    /* Se desassociação */
                    if (!empty($out) && $vinculacao == 3/* Desassociação */) {

                        /* Variaveis do historico de tramite */
                        $acao_filho = "Este documento foi {$operacao} do documento {$pai}"; // historico filho
                        $acao_pai = "O documento {$filho} foi {$operacao}."; // historico pai
                        $ultimo_tramite = "Este documento foi {$operacao} do documento {$pai}."; //ultimo tramite filho
                        $destino = "XXXXX";
                        $origem = "XXXXX";

                        $id_usuario = $auth->ID;
                        $id_unidade = $auth->ID_UNIDADE_ORIGINAL;
                        $nome_usuario = $auth->NOME;
                        $diretoria = DaoUnidade::getUnidade($id_unidade, 'nome');

                        /* Desvincular */
                        $sthp = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_VINCULACAO SET FG_ATIVO = 0 WHERE ID = ?");
                        $sthp->bindParam(1, $out['ID_VINCULO'], PDO::PARAM_INT);
                        $sthp->execute();

                        /* Adicionar historico tramite pai */
                        $sthp = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS"
                                . " (DIGITAL,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                                . " VALUES (?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP(0))");
                        $sthp->bindParam(1, $pai, PDO::PARAM_STR); //digital pai
                        $sthp->bindParam(2, $id_usuario, PDO::PARAM_INT); //id usuario
                        $sthp->bindParam(3, $nome_usuario, PDO::PARAM_STR); //nome usuario
                        $sthp->bindParam(4, $id_unidade, PDO::PARAM_INT); //id unidade
                        $sthp->bindParam(5, $diretoria, PDO::PARAM_STR); //nome diretoria
                        $sthp->bindParam(6, $acao_pai, PDO::PARAM_STR); //acao pai
                        $sthp->bindParam(7, $origem, PDO::PARAM_STR); //origem
                        $sthp->bindParam(8, $destino, PDO::PARAM_STR); //destino
                        $sthp->execute();


                        /* Adicionar historico tramite filho */
                        $sthf = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS"
                                . " (DIGITAL,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                                . " VALUES (?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP(0))");
                        $sthf->bindParam(1, $filho, PDO::PARAM_STR); //digital filho
                        $sthf->bindParam(2, $id_usuario, PDO::PARAM_INT); //id usuario
                        $sthf->bindParam(3, $nome_usuario, PDO::PARAM_STR); //nome usuario
                        $sthf->bindParam(4, $id_unidade, PDO::PARAM_INT); //id unidade
                        $sthf->bindParam(5, $diretoria, PDO::PARAM_STR); //nome diretoria
                        $sthf->bindParam(6, $acao_filho, PDO::PARAM_STR); //acao filho
                        $sthf->bindParam(7, $origem, PDO::PARAM_STR); //origem
                        $sthf->bindParam(8, $destino, PDO::PARAM_STR); //destino
                        $sthf->execute();

                        /* Ultimo tramite - Documento Filho */
                        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_CADASTRO SET ULTIMO_TRAMITE = ? WHERE DIGITAL = ?");
                        $stmt->bindParam(1, $ultimo_tramite, PDO::PARAM_STR); //acao
                        $stmt->bindParam(2, $filho, PDO::PARAM_STR); //digital filho
                        $stmt->execute();
                    }

                    /* Comitar */
                    Controlador::getInstance()->getConnection()->connection->commit();

                    return new Output(array('success' => 'true', 'message' => "Documento {$operacao} com sucesso!"));
                } else {
                    /* Retorna quando o documento filho nao esta na area de trabalho do usuario */
                    return new Output(array('success' => 'false', 'error' => "O documento {$filho} não está na sua área de trabalho!"));
                }
            } else {
                /* Retorna quando o documento pai nao esta na area de trabalho do usuario */
                return new Output(array('success' => 'false', 'error' => "O documento {$pai} não está na sua área de trabalho!"));
            }
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollBack();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * @todo Recupera documentos associados do tipo associado.
     * @return Possíveis documentos do tipo Monitoramento
     * @param string $digital
     */
    public static function getDocumentosPassiveisVinculacaoAssociada($digital = null) {
        try {



            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT D.DIGITAL AS DIGITAL
                    FROM TB_DOCUMENTOS_CADASTRO D
                        LEFT JOIN TB_PROCESSOS_DOCUMENTOS PD ON PD.ID_DOCUMENTOS_CADASTRO = D.ID
                    WHERE D.DIGITAL != ? 
                    AND PD.ID_DOCUMENTOS_CADASTRO IS NULL
                    AND D.TIPO = 'MONITORAMENTO'
                    GROUP BY D.DIGITAL ORDER BY D.DIGITAL");

            $stmt->bindParam(1, Controlador::getInstance()->usuario->ID_UNIDADE, PDO::PARAM_INT);
            $stmt->bindParam(2, $digital, PDO::PARAM_STR);
            $stmt->execute();
            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $out;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @todo Verifica se existe vínculo entre os documentos.
     * @param int $digitalPai
     * @param int $digitalFilho
     * @return bool
     */
    public static function isDocumentosVinculados($digitalPai, $digitalFilho) {

        $documentoPai = DaoDocumento::getDocumento($digitalPai);
        $documentoFilho = DaoDocumento::getDocumento($digitalFilho);

        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("select st_ativo from tb_documentos_vinculacao 
                                                                                  where id_documento_pai   = ?
                                                                                    and id_documento_filho = ? ");


        $stmt->bindParam(1, $documentoPai["id"], PDO::PARAM_INT);
        $stmt->bindParam(2, $documentoFilho["id"], PDO::PARAM_INT);
        $stmt->execute();

        return (boolean) $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
