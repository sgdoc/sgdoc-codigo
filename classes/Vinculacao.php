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
class Vinculacao extends Base {

    /**
     * @var array
     */
    public $ponteiro = array();

    /**
     * @var array
     */
    public $raiz = array();

    /**
     * Remover anexo do processo
     * @return Output
     * @param string $numero_processo
     * @param string $anexo
     */
    public function removerAnexoProcesso($numero_processo, $anexo) {
        try {

            /* Inicar transacao */
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            /* Verificar se o documento ja esta anexado ou apenso à outro documento */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT PV.ID AS ID_VINCULACAO,PV.ID_HISTORICO_TRAMITE_PAI,PV.ID_HISTORICO_TRAMITE_FILHO
                        FROM TB_PROCESSOS_VINCULACAO PV 
                        WHERE PV.ID_PROCESSO_PAI = ? AND PV.ID_PROCESSO_FILHO = ?
                        AND ID_VINCULACAO = 1
                        AND FG_ATIVO = 1 LIMIT 1");
            $stmt->bindParam(1, DaoProcesso::getProcesso($numero_processo, 'id'), PDO::PARAM_INT);
            $stmt->bindParam(2, DaoProcesso::getProcesso($anexo, 'id'), PDO::PARAM_INT);
            $stmt->execute();

            $historicos = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($historicos['ID_VINCULACAO']) {
                $sttt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_PROCESSOS_VINCULACAO SET FG_ATIVO = 0 WHERE ID = ?");
                $sttt->bindParam(1, $historicos['ID_VINCULACAO'], PDO::PARAM_INT);
                $sttt->execute();
            }

            if ($historicos['ID_HISTORICO_TRAMITE_PAI']) {
                $stmp = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_HISTORICO_TRAMITE_PROCESSOS SET ST_ATIVO = 0 WHERE ID = ?");
                $stmp->bindParam(1, $historicos['ID_HISTORICO_TRAMITE_PAI'], PDO::PARAM_INT);
                $stmp->execute();
            }

            if ($historicos['ID_HISTORICO_TRAMITE_FILHO']) {
                $stmf = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_HISTORICO_TRAMITE_PROCESSOS SET ST_ATIVO = 0 WHERE ID = ?");
                $stmf->bindParam(1, $historicos['ID_HISTORICO_TRAMITE_FILHO'], PDO::PARAM_INT);
                $stmf->execute();
            }

            /* Comitar */
            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => "Processo desanexado com sucesso!"));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * Remover peca do processo
     * @return Output
     * @param string $numero_processo
     * @param string $digital
     */
    public function removerPecaProcesso($numero_processo, $digital) {
        try {

            $allow = AclFactory::checaPermissao(
                            Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999/* alterar esse id para 1000 e adicionar no CPA */));

            if (strlen($digital) != 7 && !$allow) {
                // peça principal não pode ser removida
                return new Output(array('success' => 'false', 'error' => "O usuário não pode remover a peça principal do processo!"));
            }

            $digital = str_replace('X', '', $digital);

            /* Inicar transacao */
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            /* Verificar se o documento ja esta anexado ou apenso à outro documento */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID_PROCESSOS_CADASTRO FROM TB_PROCESSOS_DOCUMENTOS WHERE ID_DOCUMENTOS_CADASTRO = ? AND ID_PROCESSOS_CADASTRO = ? LIMIT 1");
            $stmt->bindParam(1, DaoDocumento::getDocumento($digital, 'id'), PDO::PARAM_INT);
            $stmt->bindParam(2, DaoProcesso::getProcesso($numero_processo, 'id'), PDO::PARAM_INT);
            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);


            if (!empty($out)) {

                /* Variaveis do historico de tramite */
                $acao_documento = "Este documento foi removido do processo $numero_processo.";
                $acao_processo = "O Documento $digital foi removido deste processo.";
                $ultimo_tramite = "Este documento foi removido do processo $numero_processo.";
                $destino = "XXXXX";
                $origem = "XXXXX";

                $id_usuario = Zend_Auth::getInstance()->getIdentity()->ID;
                $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
                $id_unidade_historico = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
                $diretoria = DaoUnidade::getUnidade($id_unidade_historico, 'nome');

                /* Historico - Documento */
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS"
                        . " (DIGITAL,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                        . " VALUES (?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
                $stmt->bindParam(1, $digital, PDO::PARAM_STR);
                $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
                $stmt->bindParam(3, $nome_usuario, PDO::PARAM_STR);
                $stmt->bindParam(4, $id_unidade_historico, PDO::PARAM_INT);
                $stmt->bindParam(5, $diretoria, PDO::PARAM_STR);
                $stmt->bindParam(6, $acao_documento, PDO::PARAM_STR);
                $stmt->bindParam(7, $origem, PDO::PARAM_STR);
                $stmt->bindParam(8, $destino, PDO::PARAM_STR);
                $stmt->execute();

                /* Historico - Processo */
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_PROCESSOS"
                        . "(NUMERO_PROCESSO, ID_USUARIO, USUARIO, ID_UNIDADE, DIRETORIA, ACAO, ORIGEM, DESTINO, DT_TRAMITE)"
                        . " VALUES(?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
                $stmt->bindParam(1, $numero_processo, PDO::PARAM_STR);
                $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
                $stmt->bindParam(3, $nome_usuario, PDO::PARAM_STR);
                $stmt->bindParam(4, $id_unidade_historico, PDO::PARAM_INT);
                $stmt->bindParam(5, $diretoria, PDO::PARAM_STR);
                $stmt->bindParam(6, $acao_processo, PDO::PARAM_STR);
                $stmt->bindParam(7, $origem, PDO::PARAM_STR);
                $stmt->bindParam(8, $destino, PDO::PARAM_STR);
                $stmt->execute();

                /* Ultimo tramite - Documento */
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_CADASTRO SET ULTIMO_TRAMITE = ? WHERE DIGITAL = ?");
                $stmt->bindParam(1, $ultimo_tramite, PDO::PARAM_STR);
                $stmt->bindParam(2, $digital, PDO::PARAM_STR);
                $stmt->execute();

                /* Adicionar Peça */
                $sttm = Controlador::getInstance()->getConnection()->connection->prepare("DELETE FROM TB_PROCESSOS_DOCUMENTOS WHERE ID_PROCESSOS_CADASTRO = ? AND ID_DOCUMENTOS_CADASTRO = ?");
                $sttm->bindParam(1, DaoProcesso::getProcesso($numero_processo, 'id'), PDO::PARAM_INT);
                $sttm->bindParam(2, DaoDocumento::getDocumento($digital, 'id'), PDO::PARAM_INT);
                $sttm->execute();

                /* Comitar */
                Controlador::getInstance()->getConnection()->connection->commit();

                return new Output(array('success' => 'true', 'message' => "Peça removida com sucesso!"));
            } else {
                return new Output(array('success' => 'false', 'error' => "O documento {$digital} não é peça do processo {$numero_processo}!"));
            }
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * Adicionar peca no processo
     * @return Output
     * @param string $numero_processo
     * @param string $digital
     * @param boolean $checar
     */
    public function adicionarPecaProcesso($numero_processo, $digital, $checar = true) {
        try {

            if ($checar) {
                /* Validar se os documentos pai e filho ainda estao na area de trabalho do usuario */
                $passou_proc = false;
                $passou_doc = false;
                if (Processo::validarProcessoAreaDeTrabalho($numero_processo)) {
                    $passou_proc = true;
                    if (Documento::validarDocumentoAreaDeTrabalho($digital)) {
                        $passou_doc = true;
                    }
                }
            } else {
                $passou_doc = true;
                $passou_proc = true;
            }

            if ($passou_proc == true) {
                if ($passou_doc == true) {
                    /* Inicar transacao */
                    Controlador::getInstance()->getConnection()->connection->beginTransaction();

                    /* Verificar se o documento ja esta anexado ou apenso à outro documento */
                    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID_PROCESSOS_CADASTRO FROM TB_PROCESSOS_DOCUMENTOS WHERE ID_DOCUMENTOS_CADASTRO = ? LIMIT 1");
                    $stmt->bindParam(1, DaoDocumento::getDocumento($digital, 'id'), PDO::PARAM_INT);
                    $stmt->execute();
                    $out = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (empty($out)) {

                        /* Variaveis do historico de tramite */
                        $acao_documento = "Este documento foi adicionado ao processo $numero_processo."; // historico filho
                        $acao_processo = "O Documento $digital foi adicionado neste processo."; // historico pai
                        $ultimo_tramite = "Este documento foi adicionado ao processo $numero_processo."; //ultimo tramite filho
                        $destino = "XXXXX";
                        $origem = "XXXXX";

                        $id_usuario = Zend_Auth::getInstance()->getIdentity()->ID;
                        $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
                        $id_unidade_historico = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
                        $id_unidade_usuario = Controlador::getInstance()->usuario->ID_UNIDADE;
                        $diretoria = DaoUnidade::getUnidade($id_unidade_historico, 'nome');

                        /* Historico - Documento */
                        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS"
                                . " (DIGITAL,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                                . " VALUES (?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
                        $stmt->bindParam(1, $digital, PDO::PARAM_STR);
                        $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
                        $stmt->bindParam(3, $nome_usuario, PDO::PARAM_STR);
                        $stmt->bindParam(4, $id_unidade_historico, PDO::PARAM_INT);
                        $stmt->bindParam(5, $diretoria, PDO::PARAM_STR);
                        $stmt->bindParam(6, $acao_documento, PDO::PARAM_STR);
                        $stmt->bindParam(7, $origem, PDO::PARAM_STR);
                        $stmt->bindParam(8, $destino, PDO::PARAM_STR);
                        $stmt->execute();

                        /* Historico - Processo */
                        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_PROCESSOS"
                                . " (NUMERO_PROCESSO,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                                . " VALUES (?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
                        $stmt->bindParam(1, $numero_processo, PDO::PARAM_STR);
                        $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
                        $stmt->bindParam(3, $nome_usuario, PDO::PARAM_STR);
                        $stmt->bindParam(4, $id_unidade_historico, PDO::PARAM_INT);
                        $stmt->bindParam(5, $diretoria, PDO::PARAM_STR);
                        $stmt->bindParam(6, $acao_processo, PDO::PARAM_STR);
                        $stmt->bindParam(7, $origem, PDO::PARAM_STR);
                        $stmt->bindParam(8, $destino, PDO::PARAM_STR);
                        $stmt->execute();

                        /* Ultimo tramite - Documento */
                        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_CADASTRO SET ULTIMO_TRAMITE = ? WHERE DIGITAL = ?");
                        $stmt->bindParam(1, $ultimo_tramite, PDO::PARAM_STR);
                        $stmt->bindParam(2, $digital, PDO::PARAM_STR);
                        $stmt->execute();

                        /* Adicionar Peça */
                        $sttm = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_PROCESSOS_DOCUMENTOS(ID_PROCESSOS_CADASTRO,ID_DOCUMENTOS_CADASTRO,ID_USUARIOS,ID_UNIDADE_USUARIO) VALUES (?,?,?,?)");
                        $sttm->bindParam(1, DaoProcesso::getProcesso($numero_processo, 'id'), PDO::PARAM_INT);
                        $sttm->bindParam(2, DaoDocumento::getDocumento($digital, 'id'), PDO::PARAM_INT);
                        $sttm->bindParam(3, $this->_usuario->id, PDO::PARAM_INT);
                        $sttm->bindParam(4, $id_unidade_usuario, PDO::PARAM_INT);
                        $sttm->execute();

                        /* Comitar */
                        Controlador::getInstance()->getConnection()->connection->commit();

                        return new Output(array('success' => 'true', 'message' => "Peça adicionada com sucesso!"));
                    } else {
                        $numero_processo = DaoProcesso::getProcesso($out['ID_PROCESSOS_CADASTRO'], 'numero_processo');
                        return new Output(array('success' => 'false', 'error' => "O documento {$digital} já é peça do processo {$numero_processo}!"));
                    }
                } else {
                    /* Retorna quando o documento filho nao esta na area de trabalho do usuario */
                    return new Output(array('success' => 'false', 'error' => "O documento {$digital} não está na sua área de trabalho!"));
                }
            } else {
                /* Retorna quando o documento pai nao esta na area de trabalho do usuario */
                return new Output(array('success' => 'false', 'error' => "O processo {$numero_processo} não está na sua área de trabalho!"));
            }
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     *  Pegar lista de pecas do processo
     * @return type Description
     */
    public static function getPecasProcesso($numero_processo) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT DIGITAL FROM TB_PROCESSOS_DOCUMENTOS PXD
                                                                                        INNER JOIN TB_PROCESSOS_CADASTRO PC ON PC.ID = PXD.ID_PROCESSOS_CADASTRO
                                                                                        INNER JOIN TB_DOCUMENTOS_CADASTRO DC ON DC.ID = PXD.ID_DOCUMENTOS_CADASTRO
                                                                                    WHERE PC.NUMERO_PROCESSO = ? ORDER BY PXD.ID");
            $stmt->bindParam(1, $numero_processo, PDO::PARAM_STR);
            $stmt->execute();
            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $out;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Lista de anexos do processos
     */
    public static function getAnexosProcesso($numero_processo) {
        try {



            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT (SELECT NUMERO_PROCESSO FROM TB_PROCESSOS_CADASTRO WHERE ID = PV.ID_PROCESSO_FILHO LIMIT 1) AS ANEXOS FROM TB_PROCESSOS_CADASTRO PC
                        INNER JOIN TB_PROCESSOS_VINCULACAO PV ON PV.ID_PROCESSO_PAI = PC.ID
                        WHERE PC.NUMERO_PROCESSO = ? AND PV.FG_ATIVO = 1 AND PV.ID_VINCULACAO = 1");
            $stmt->bindParam(1, $numero_processo, PDO::PARAM_STR);
            $stmt->execute();
            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $out;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Pegar lista de tipologias de documentos
     * @return PDOStatement
     * @param string $digital
     */
    public static function getDocumentosPassiveisVinculacao($digital = null) {
        try {



            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT D.DIGITAL AS DIGITAL
                                    FROM TB_DOCUMENTOS_CADASTRO D
                                    LEFT JOIN TB_PROCESSOS_DOCUMENTOS PD ON PD.ID_DOCUMENTOS_CADASTRO = D.ID
                                    WHERE D.ID_UNID_AREA_TRABALHO = ? AND
                                    D.DIGITAL != ? AND
                                    D.ID NOT IN(SELECT VI.ID_DOCUMENTO_FILHO FROM TB_DOCUMENTOS_VINCULACAO VI WHERE VI.ID_DOCUMENTO_FILHO = D.ID AND VI.FG_ATIVO = 1) AND
                                    PD.ID_DOCUMENTOS_CADASTRO IS NULL
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
     *  Setar os documentos principais, afim de obter todos os outros documentos relacionados
     */
    public function setRelacaoDocumentosTramitar($digital) {

        $cont = 0;

        $array = explode(",", $digital);
        $novo = array();
        $aux = array();

        foreach ($array as $value) {
            $cont++;
            $this->setDocumentoRoot($value);
            $novo[$value]['this'] = $value;
            $novo = array_merge($novo, $this->getDocumentosRelacionados());
        }

        foreach ($novo as $key => $value) {
            $aux[$key] = $key;
            foreach ($value as $key => $v) {
                $aux[$v] = $v;
            }
        }

        sort($aux);

        return $aux;
    }

    /**
     *  Retorna todos documentos relacionados com o documento informado
     */
    public function getDocumentosRelacionados($digital = null) 
    {
        $digital = $digital ? $digital : $this->raiz;

        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
            SELECT 
                DF.DIGITAL AS ANEXADO, 
                DP.DIGITAL AS DIGITAL
            FROM TB_DOCUMENTOS_VINCULACAO DV
                INNER JOIN TB_DOCUMENTOS_CADASTRO DP ON DV.ID_DOCUMENTO_PAI = DP.ID
                INNER JOIN TB_DOCUMENTOS_CADASTRO DF ON DV.ID_DOCUMENTO_FILHO = DF.ID
            WHERE DP.DIGITAL = ? 
                AND DP.DIGITAL != '' 
                AND DF.DIGITAL != '' 
                AND DV.FG_ATIVO = 1 
                AND DV.ST_ATIVO = 1
            ORDER BY DV.ID
        ");
        $stmt->bindParam(1, $digital, PDO::PARAM_STR);
        $stmt->execute();
        $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($out)) {
            foreach ($out as $value => $key) {
                $this->ponteiro[$digital][] = $key['ANEXADO'];
                $this->ponteiro[$digital]['this'] = $digital;
                $this->getDocumentosRelacionados($key['ANEXADO']);
            }
        }

        return $this->ponteiro;
    }

    /**
     *  Retorna todos documentos filhos do documento informado apertir do tipo de vinculacao
     */
    public static function getDocumentosVinculados($digital, $vinculacao) 
    {
        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
            SELECT DF.DIGITAL AS FILHO 
            FROM TB_DOCUMENTOS_VINCULACAO DV
                INNER JOIN TB_DOCUMENTOS_CADASTRO DP ON DV.ID_DOCUMENTO_PAI = DP.ID
                INNER JOIN TB_DOCUMENTOS_CADASTRO DF ON DV.ID_DOCUMENTO_FILHO = DF.ID
            WHERE DP.DIGITAL = ? 
                AND DF.DIGITAL != '' 
                AND DP.DIGITAL != '' 
                AND FG_ATIVO = 1 
                AND DV.ID_VINCULACAO = ? 
        ");
        $stmt->bindParam(1, $digital, PDO::PARAM_STR);
        $stmt->bindParam(2, $vinculacao, PDO::PARAM_INT);
        $stmt->execute();
        return $out = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Setar o documento mais relevante da arvore
     */
    public function setDocumentoRoot($digital, $vinculacao = null) {

        $condicao = (is_null($vinculacao)) ? '!=' : '=';

        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT DP.DIGITAL AS PAI FROM TB_DOCUMENTOS_VINCULACAO DV
                                    INNER JOIN TB_DOCUMENTOS_CADASTRO DP ON DV.ID_DOCUMENTO_PAI = DP.ID
                                    INNER JOIN TB_DOCUMENTOS_CADASTRO DF ON DV.ID_DOCUMENTO_FILHO = DF.ID
                                    WHERE DF.DIGITAL = ? AND FG_ATIVO = 1 AND ST_ATIVO = 1 AND ID_VINCULACAO {$condicao} ? LIMIT 1");
        $stmt->bindParam(1, $digital, PDO::PARAM_STR);
        $stmt->bindParam(2, $vinculacao, PDO::PARAM_INT);
        $stmt->execute();
        $out = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($out)) {
            $this->raiz = $out['PAI'];
            $this->setDocumentoRoot($this->raiz, $vinculacao);
        } else {
            $this->raiz = $digital;
        }
        return $this->raiz; //retorna o novo root ou o proprio digital
    }

    /**
     * Setar os processos principais, afim de obter todos os outros processos relacionados
     */
    public function setRelacaoProcessosTramitar($processo) {

        $cont = 0;
        $array = explode(",", $processo);
        $novo = array();
        $aux = array();

        foreach ($array as $value) {
            $cont++;
            $this->setProcessoRoot($value);
            $novo[$value]['this'] = $value;
            $novo = array_merge($novo, $this->getProcessosRelacionados());
        }

        foreach ($novo as $key => $value) {
            $aux[$key] = $key;
            foreach ($value as $key => $v) {
                $aux[$v] = $v;
            }
        }

        sort($aux);

        return $aux;
    }

    /**
     * Retorna todos processos relacionados com o processos informado
     */
    public function getProcessosRelacionados($processo = null) {

        $processo = $processo ? $processo : $this->raiz;

        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT PF.NUMERO_PROCESSO AS ANEXADO, PP.NUMERO_PROCESSO AS PRINCIPAL FROM TB_PROCESSOS_VINCULACAO PV
                                    INNER JOIN TB_PROCESSOS_CADASTRO PP ON PV.ID_PROCESSO_PAI = PP.ID
                                    INNER JOIN TB_PROCESSOS_CADASTRO PF ON PV.ID_PROCESSO_FILHO = PF.ID
                                    WHERE PP.NUMERO_PROCESSO = ? AND PF.NUMERO_PROCESSO != '' AND PP.NUMERO_PROCESSO != '' AND FG_ATIVO = 1");
        $stmt->bindParam(1, $processo, PDO::PARAM_STR);
        $stmt->execute();
        $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($out)) {
            foreach ($out as $value => $key) {
                $this->ponteiro["$processo"][] = $key['ANEXADO'];
                $this->ponteiro["$processo"]['this'] = $processo;
                $this->getProcessosRelacionados($key['ANEXADO']);
            }
        }

        return $this->ponteiro;
    }

    /**
     * Setar o documento mais relevante da arvore
     * @return string
     * @param string $numero_processo
     * @param integer $vinculacao
     */
    public function setProcessoRoot($numero_processo, $vinculacao = null) {

        $condicao = (is_null($vinculacao)) ? '!=' : '=';

        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT PP.NUMERO_PROCESSO AS PAI FROM TB_PROCESSOS_VINCULACAO PV
                                    INNER JOIN TB_PROCESSOS_CADASTRO PP ON PV.ID_PROCESSO_PAI = PP.ID
                                    INNER JOIN TB_PROCESSOS_CADASTRO PF ON PV.ID_PROCESSO_FILHO = PF.ID
                                    WHERE PF.NUMERO_PROCESSO = ? AND PV.ID_VINCULACAO {$condicao} ? AND PV.FG_ATIVO = 1 LIMIT 1");
        $stmt->bindParam(1, $numero_processo, PDO::PARAM_STR);
        $stmt->bindParam(2, $vinculacao, PDO::PARAM_INT);
        $stmt->execute();
        $out = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($out)) {
            $this->raiz = $out['PAI'];
            $this->setProcessoRoot($this->raiz, $vinculacao);
        } else {
            $this->raiz = $numero_processo;
        }
        return $this->raiz; //retorna o novo root ou o proprio digital
    }

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
            if (Documento::validarDocumentoAreaDeTrabalho($pai)) {
                if (Documento::validarDocumentoAreaDeTrabalho($filho)) {

                    /* Inicar transacao */
                    Controlador::getInstance()->getConnection()->connection->beginTransaction();

                    /* Verificar se o documento ja esta anexado ou apenso à outro documento */
                    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID FROM TB_DOCUMENTOS_VINCULACAO WHERE ID_DOCUMENTO_FILHO = ? AND FG_ATIVO = 1 AND ST_ATIVO = 1 LIMIT 1");
                    $stmt->bindParam(1, DaoDocumento::getDocumento($filho, 'id'), PDO::PARAM_INT);
                    $stmt->execute();
                    $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (empty($out)) {

                        $dt_ativacao = date('Y-m-d');
                        $operacao = ($vinculacao == 1) ? 'anexado' : 'apensado';
                        $acao0 = "Este documento foi {$operacao} ao documento {$pai}";
                        $acao1 = "O documento {$filho} foi {$operacao}.";
                        $acao2 = "Este documento foi {$operacao} ao documento {$pai}.";
                        $destino = "XXXXX";
                        $origem = "XXXXX";

                        $id_usuario = Zend_Auth::getInstance()->getIdentity()->ID;
                        $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
                        $id_unidade_historico = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
                        $diretoria = DaoUnidade::getUnidade($id_unidade_historico, 'nome');

                        /* Historico - Documento Pai */
                        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS (DIGITAL,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE) VALUES (?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
                        $stmt->bindParam(1, $pai, PDO::PARAM_STR);
                        $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
                        $stmt->bindParam(3, $nome_usuario, PDO::PARAM_STR);
                        $stmt->bindParam(4, $id_unidade_historico, PDO::PARAM_INT);
                        $stmt->bindParam(5, $diretoria, PDO::PARAM_STR);
                        $stmt->bindParam(6, $acao1, PDO::PARAM_STR);
                        $stmt->bindParam(7, $origem, PDO::PARAM_STR);
                        $stmt->bindParam(8, $destino, PDO::PARAM_STR);
                        $stmt->execute();

                        /**/
                        $id_pai = Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_HISTORICO_TRAMITE_DOCUMENTOS_ID_SEQ');

                        /* Historico - Documento Filho */
                        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS"
                                . " (DIGITAL,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                                . " VALUES (?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
                        $stmt->bindParam(1, $filho, PDO::PARAM_STR);
                        $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
                        $stmt->bindParam(3, $nome_usuario, PDO::PARAM_STR);
                        $stmt->bindParam(4, $id_unidade_historico, PDO::PARAM_INT);
                        $stmt->bindParam(5, $diretoria, PDO::PARAM_STR);
                        $stmt->bindParam(6, $acao0, PDO::PARAM_STR);
                        $stmt->bindParam(7, $origem, PDO::PARAM_STR);
                        $stmt->bindParam(8, $destino, PDO::PARAM_STR);
                        $stmt->execute();

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
                        $sttm->bindParam(3, $id_usuario, PDO::PARAM_INT);
                        $sttm->bindParam(4, $id_pai, PDO::PARAM_INT);
                        $sttm->bindParam(5, $id_filho, PDO::PARAM_INT);
                        $sttm->bindParam(6, $vinculacao, PDO::PARAM_INT);
                        $sttm->bindParam(7, $dt_ativacao, PDO::PARAM_INT);
                        $sttm->bindParam(8, $id_unidade, PDO::PARAM_INT);
                        $sttm->execute();

                        /* Comitar */
                        Controlador::getInstance()->getConnection()->connection->commit();

                        return new Output(array('success' => 'true', 'message' => "Documento {$operacao} com sucesso!"));
                    } else {
                        return new Output(array('success' => 'false', 'error' => 'Este documento já está vinculado a outro documento!'));
                    }
                } else {
                    /* Retorna quando o documento filho nao esta na area de trabalho do usuario */
                    return new Output(array('success' => 'false', 'error' => "O documento {$filho} não está na sua área de trabalho!"));
                }
            } else {
                /* Retorna quando o documento pai nao esta na area de trabalho do usuario */
                return new Output(array('success' => 'false', 'error' => "O documento {$pai} não está na sua área de trabalho!"));
            }
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     *  Operacao Desvincular documentos
     */
    public function desvincularDocumento($pai, $filho, $vinculacao) {
        try {
            /* Validar se os documentos pai e filho ainda estao na area de trabalho do usuario
              OU
              /* Privilegio total para usuarios com permissao */
            $controller = Controlador::getInstance();
            $auth = $controller->usuario;

            // permissao desanexar documento = 310114
            if (Documento::validarDocumentoAreaDeTrabalho($pai) ||
                    ($vinculacao == 1 &&
                    AclFactory::checaPermissao($controller->acl, $auth, DaoRecurso::getRecursoById(3101114)))) {
                if (Documento::validarDocumentoAreaDeTrabalho($filho) ||
                        ($vinculacao == 1 &&
                        AclFactory::checaPermissao($controller->acl, $auth, DaoRecurso::getRecursoById(3101114)))) {

                    $operacao = $vinculacao == 1 ? 'desanexado' : 'desapensado';

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
                                . " VALUES (?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
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
                                . " VALUES (?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
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
     * 
     */
    public static function getProcessosVicunlados($processo, $tipo)/* 1 = Anexacao , 2 = Desanexacao */ {
        try {


            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT PCF.NUMERO_PROCESSO AS PROC FROM TB_PROCESSOS_VINCULACAO V
                                    INNER JOIN TB_PROCESSOS_CADASTRO PCP ON PCP.ID = V.ID_PROCESSO_PAI
                                    INNER JOIN TB_PROCESSOS_CADASTRO PCF ON PCF.ID = V.ID_PROCESSO_FILHO
                                WHERE PCP.NUMERO_PROCESSO = ? AND V.ID_VINCULACAO = ? AND V.FG_ATIVO = 1");
            $stmt->bindParam(1, $processo, PDO::PARAM_STR);
            $stmt->bindParam(2, $tipo, PDO::PARAM_INT);
            $stmt->execute();

            $resul = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $out = array();

            if (!empty($resul)) {
                foreach ($resul as $value) {
                    $out[] = $value['PROC'];
                }
            }
            return $out;
        } catch (PDOException $e) {
            throw $e;
        }
    }

}