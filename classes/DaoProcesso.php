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
class DaoProcesso {

    /**
     * 
     */
    public static function getProcesso($processo, $campo = false) {
        try {

            

            $campo = $campo ? $campo : '*';
            $condicao = filter_var($processo, FILTER_VALIDATE_INT) ? 'ID' : 'NUMERO_PROCESSO';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT {$campo} FROM TB_PROCESSOS_CADASTRO WHERE {$condicao} = ?");
            $stmt->bindParam(1, $processo, PDO::PARAM_STR);
            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                /* Padronizar com caixa baixa o os indices dos arrays */
                $out = array_change_key_case($out, CASE_LOWER);
                if ($campo === '*') {
                    return $out;
                }
                return $out[$campo];
            }

            return false;
        } catch (PDOException $e) {
            throw new BasePDOException($e);
        }
    }

    /**
     * 
     */
    public static function alterarProcesso(Processo $processo) {
        try {

            
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $processo->processo->fg_prazo = $processo->processo->fg_prazo == 'true' ? 1 : 0;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_PROCESSOS_CADASTRO SET 
                ASSUNTO = ?,
                ASSUNTO_COMPLEMENTAR = ?,
                INTERESSADO = ?,
                ORIGEM = ?,
                DT_AUTUACAO = ?,
                DT_PRAZO = ?,
                FG_PRAZO = ?,
                PROCEDENCIA = ?
                WHERE NUMERO_PROCESSO = ?");

            $stmt->bindParam(1, $processo->processo->assunto, PDO::PARAM_INT); //DATA_ENTRADA' ,
            $stmt->bindParam(2, $processo->processo->assunto_complementar, PDO::PARAM_STR); //TIPO' ,
            $stmt->bindParam(3, $processo->processo->interessado, PDO::PARAM_INT); //NUMERO' ,
            $stmt->bindParam(4, $processo->processo->origem, PDO::PARAM_INT); //ORIGEM' ,
            $stmt->bindParam(5, Util::formatDate($processo->processo->dt_autuacao), PDO::PARAM_STR); //INTERESSADO' ,
            $stmt->bindParam(6, Util::formatDate($processo->processo->dt_prazo), PDO::PARAM_STR); //ASSUNTO' ,
            $stmt->bindParam(7, $processo->processo->fg_prazo, PDO::PARAM_INT); //INTERNO = 1 , ExTERNO = 0' ,
            $stmt->bindParam(8, $processo->processo->procedencia, PDO::PARAM_STR); //CARGO' ,
            $stmt->bindParam(9, $processo->processo->numero_processo, PDO::PARAM_STR); //ASSINATURA' ,
            $stmt->execute();

            $acao = "Cadastrado complementado.";
            $destino = "XXXXX";
            $usuario = Controlador::getInstance()->usuario;
            $id_usuario = $usuario->ID;
            $nome_usuario = $usuario->NOME;
            $id_unidade = $usuario->ID_UNIDADE;
            $oDiretoria = DaoUnidade::getUnidade($id_unidade);
            $diretoria = $oDiretoria['nome'];
            $tx_diretoria = $oDiretoria['nome'] . ' - ' . $oDiretoria['sigla'];


            $stmm = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_PROCESSOS"
                    . " (NUMERO_PROCESSO,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                    . " VALUES(?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
            $stmm->bindParam(1, $processo->processo->numero_processo, PDO::PARAM_STR);
            $stmm->bindParam(2, $id_usuario, PDO::PARAM_INT);
            $stmm->bindParam(3, $nome_usuario, PDO::PARAM_STR);
            $stmm->bindParam(4, $id_unidade, PDO::PARAM_INT);
            $stmm->bindParam(5, $diretoria, PDO::PARAM_STR);
            $stmm->bindParam(6, $acao, PDO::PARAM_STR);
            $stmm->bindParam(7, $tx_diretoria, PDO::PARAM_STR);
            $stmm->bindParam(8, $destino, PDO::PARAM_STR);
            $stmm->execute();

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true'));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * 
     * @param string $numeroProcesso
     * @return mixed
     * @throws BasePDOException
     */
    public static function getVwProcesso($numeroProcesso) {
        $numeroProcesso = htmlentities($numeroProcesso);
        try {
            
            

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT p.DIGITAL,
                                           p.NUMERO_PROCESSO,
                                           p.ASSUNTO,
                                           p.NM_ASSUNTO,
                                           p.INTERESSADO, 
                                           p.NM_INTERESSADO,
                                           p.ORIGEM,
                                           p.DS_ORIGEM,
                                           p.DT_AUTUACAO,
                                           p.FG_PRAZO
                                      FROM VW_PROCESSOS p 
                                     WHERE NUMERO_PROCESSO = '{$numeroProcesso}'
                                     LIMIT 1");
            
            $stmt->bindParam(1, $numeroProcesso, PDO::PARAM_STR);
            
            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            return $out;
        } catch (PDOException $e) {
            return $e;
        }
    }

    /**
     * DaoDocumento::getHistoricoDespachos(array $where)
     * Entradas:
     * $where['numero_processo'] -> numero da digital,
     * $where['...'] -> ...,
     * 
     * @param array $where
     * @return array|boolean
     * @throws BasePDOException
     */
    public static function getHistoricoDespachos(array $where) {
        try {
            $sql = "SELECT ID,
                           NUMERO_PROCESSO,
                           ID_USUARIO,
                           USUARIO, 
                           ID_UNIDADE,
                           DIRETORIA,
                           DT_CADASTRO, 
                           DT_DESPACHO, 
                           TEXTO_DESPACHO, 
                           COMPLEMENTO, 
                           ASSINATURA_DESPACHO
                      FROM VW_DESPACHOS_PROCESSOS
                     WHERE ST_ATIVO = 1";

            if (isset($where['numero_processo']) && !empty($where['numero_processo'])) {
                $sql .= " AND NUMERO_PROCESSO = ?";
            }
            
            
            
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam(1, $where['numero_processo'], PDO::PARAM_STR);

            $stmt->execute();
            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($out) > 0) {
                return $out;
            }
            return false;
        } catch (PDOException $e) {
            throw new BasePDOException($e);
        }
    }

    /**
     * DaoDocumento::getHistoricoComentarios(array $where)
     * Entradas:
     * $where['numero_processo'] -> numero da digital,
     * $where['...'] -> ...,
     * 
     * @param array $where
     * @return array|boolean
     * @throws BasePDOException
     */
    public static function getHistoricoComentarios(array $where) {
        try {
            $sql = "SELECT ID, 
                           NUMERO_PROCESSO,
                           DT_CADASTRO, 
                           TEXTO_COMENTARIO, 
                           USUARIO, 
                           DIRETORIA
                      FROM VW_COMENTARIOS_PROCESSOS
                     WHERE ST_ATIVO = 1";

            if (isset($where['numero_processo']) && !empty($where['numero_processo'])) {
                $sql .= " AND NUMERO_PROCESSO = ?";
            }
            
            
            
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam(1, $where['numero_processo'], PDO::PARAM_STR);

            $stmt->execute();
            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($out) > 0) {
                return $out;
            }
            return false;
        } catch (PDOException $e) {
            throw new BasePDOException($e);
        }
    }

    /**
     * DaoDocumento::getHistoricoTramite(array $where)
     * Entradas:
     * $where['numero_processo'] -> numero da digital,
     * $where['...'] -> ...,
     * 
     * @param array $where
     * @return array|boolean
     * @throws BasePDOException
     */
    public static function getHistoricoTramite(array $where) {
        try {
            $sql = "SELECT ID, 
                           NUMERO_PROCESSO,
                           USUARIO, 
                           DIRETORIA, 
                           ACAO, 
                           ORIGEM, 
                           DESTINO, 
                           DT_TRAMITE
                      FROM VW_HISTORICO_TRAMITE_PROCESSOS
                     WHERE ST_ATIVO = 1";

            if (isset($where['numero_processo']) && !empty($where['numero_processo'])) {
                $sql .= " AND NUMERO_PROCESSO = ?";
            }
            
            
            
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam(1, $where['numero_processo'], PDO::PARAM_STR);

            $stmt->execute();
            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($out) > 0) {
                return $out;
            }
            return false;
        } catch (PDOException $e) {
            throw new BasePDOException($e);
        }
    }

}