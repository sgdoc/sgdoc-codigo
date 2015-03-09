<?php

/*
 * Copyright 2008 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 * */

class AutoCompleteDemanda extends AutoComplete {


    /**
     * @author Bruno Pedreira
     * @todo Filtro para combo de prioridades da demanda.
     * @param type $query
     * @param type $mirror
     * @return null
     */
    public static function filterPrioridadesDemandaFullText($query, $mirror = false) {
        try {
            $query_nome = "%{$query}%";
            $field = ($mirror) ? 'nome' : 'id';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare(" 
                SELECT {$field} as id, prioridade as value 
                FROM TB_PRIORIDADE 
                WHERE 
                     fn_remove_acentuacao(PRIORIDADE) ILIKE fn_remove_acentuacao(?)
                ORDER BY prioridade
            ");
            $stmt->bindParam(1, $query_nome, PDO::PARAM_STR);
            $stmt->execute();

            $uppers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($uppers as $upper) {
                $lowers[] = array_change_key_case($upper, CASE_LOWER);
            }

            return $lowers;
        } catch (PDOException $e) {
            print($e->getMessage());
            return null;
        }
    }
    
    /**
     * @author Bruno Pedreira
     * @todo Filtro para combo de prioridades da demanda.
     * @param type $query
     * @param type $mirror
     * @return null
     */
    public static function filterParticipanteFullText($query, $mirror = false) {
        try {
            $query_nome = "%{$query}%";
            $field = ($mirror) ? 'nome' : 'id_pessoa';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare(" 
                SELECT id_pessoa as id, nm_pessoa as value, 1 as P
                FROM TB_PESSOA 
                WHERE fn_remove_acentuacao(NM_PESSOA) ILIKE fn_remove_acentuacao(?)
                --ORDER BY nm_pessoa
                --UNION ALL
                --SELECT id as id, nome as value, 1 as U
                --FROM TB_USUARIOS 
                --WHERE fn_remove_acentuacao(nome) ILIKE fn_remove_acentuacao(?)
                --ORDER BY nome
            ");
            $stmt->bindParam(1, $query_nome, PDO::PARAM_STR);
            //$stmt->bindParam(2, $query_nome, PDO::PARAM_STR);
            $stmt->execute();

            $uppers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($uppers as $upper) {
                $lowers[] = array_change_key_case($upper, CASE_LOWER);
            }

            return $lowers;
        } catch (PDOException $e) {
            print($e->getMessage());
            return null;
        }
    }
    
    /**
     * 
     */
    public static function filterUnidadesInternasFullText($query, $mirror = false) {
        try {
            $query_nome = "%{$query}%";
            $field = ($mirror) ? 'nome' : 'id';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT {$field} as id, nome as value 
                FROM TB_UNIDADES 
                WHERE 
                    (ID != UOP) 
                    AND fn_remove_acentuacao(SIGLA) ILIKE fn_remove_acentuacao(?)
                    OR fn_remove_acentuacao(NOME) ILIKE fn_remove_acentuacao(?) 
                ORDER BY value
            ");
            $stmt->bindParam(1, $query_nome, PDO::PARAM_STR);
            $stmt->bindParam(2, $query_nome, PDO::PARAM_STR);
            $stmt->execute();

            $uppers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($uppers as $upper) {
                $lowers[] = array_change_key_case($upper, CASE_LOWER);
            }

            return $lowers;
        } catch (PDOException $e) {
            print($e->getMessage());
            return null;
        }
    }

    /**
     * 
     */
    public static function filterUnidadesByTipoFullText($query, $tipo) {
        try {
            $query_nome = "%{$query}%";

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT id as id, nome as value 
                FROM TB_UNIDADES 
                WHERE 
                    (ID != UOP) AND (SIGLA ILIKE ? OR NOME ILIKE ?) AND TIPO = ? 
                ORDER BY value
            ");
            $stmt->bindParam(1, $query_nome, PDO::PARAM_STR);
            $stmt->bindParam(2, $query_nome, PDO::PARAM_STR);
            $stmt->bindParam(3, $tipo, PDO::PARAM_STR);
            $stmt->execute();

            $uppers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($uppers as $upper) {
                $lowers[] = array_change_key_case($upper, CASE_LOWER);
            }

            return $lowers;
        } catch (PDOException $e) {
            print($e->getMessage());
            return null;
        }
    }

    /**
     * 
     */
    public static function filterUnidadesTramiteSIC($query) {
        try {
            $query = "%{$query}%";

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT ID as id, nome as value 
                FROM TB_UNIDADES 
                WHERE (ID != UOP) AND SIGLA ILIKE ? OR NOME ILIKE ? 
                ORDER BY value
            ");
            $stmt->bindParam(1, $query, PDO::PARAM_STR);
            $stmt->bindParam(2, $query, PDO::PARAM_STR);
            $stmt->execute();

            $uppers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($uppers as $upper) {
                $lowers[] = array_change_key_case($upper, CASE_LOWER);
            }

            return $lowers;
        } catch (PDOException $e) {
            print($e->getMessage());
            return null;
        }
    }

    /**
     * 
     */
    public static function filterPessoaFisicaFullText($query, $mirror = false) {
        try {
            $query = "%{$query}%";
            $field = ($mirror) ? 'nm_pessoa' : 'id_pessoa';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT {$field} as id, nm_pessoa as value 
                FROM TB_PESSOA 
                WHERE TP_PESSOA = 'PF' AND ( NM_PESSOA ILIKE ? OR SG_PESSOA ILIKE ?) 
                ORDER BY value
            ");
            $stmt->bindParam(1, $query, PDO::PARAM_STR);
            $stmt->bindParam(2, $query, PDO::PARAM_STR);
            $stmt->execute();

            $uppers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($uppers as $upper) {
                $lowers[] = array_change_key_case($upper, CASE_LOWER);
            }

            return $lowers;
        } catch (PDOException $e) {
            print($e->getMessage());
            return null;
        }
    }

    /**
     * 
     */
    public static function filterPessoaJuridicaFullText($query, $mirror = false) {
        try {
            $query = "%{$query}%";
            $field = ($mirror) ? 'nm_pessoa' : 'id_pessoa';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT {$field} as id, nm_pessoa as value 
                FROM TB_PESSOA 
                WHERE TP_PESSOA = 'PJ' AND ( NM_PESSOA ILIKE ? OR SG_PESSOA ILIKE ?) 
                ORDER BY value
            ");
            $stmt->bindParam(1, $query, PDO::PARAM_STR);
            $stmt->bindParam(2, $query, PDO::PARAM_STR);
            $stmt->execute();

            $uppers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($uppers as $upper) {
                $lowers[] = array_change_key_case($upper, CASE_LOWER);
            }

            return $lowers;
        } catch (PDOException $e) {
            print($e->getMessage());
            return null;
        }
    }

    /**
     * 
     */
    public static function filterUnidadesGenericasFullText($query, $type, $mirror = false) {
        try {
            $query = "%{$query}%";
            $field = ($mirror) ? 'nm_pessoa' : 'sg_pessoa';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT {$field} as id, nm_pessoa as value 
                FROM TB_PESSOA 
                WHERE TP_PESSOA = ? AND ( NM_PESSOA ILIKE ? OR SG_PESSOA ILIKE ?) 
                ORDER BY value
            ");
            $stmt->bindParam(1, $type, PDO::PARAM_STR);
            $stmt->bindParam(2, $query, PDO::PARAM_STR);
            $stmt->bindParam(3, $query, PDO::PARAM_STR);
            $stmt->execute();

            $uppers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($uppers as $upper) {
                $lowers[] = array_change_key_case($upper, CASE_LOWER);
            }

            return $lowers;
        } catch (PDOException $e) {
            print($e->getMessage());
            return null;
        }
    }

    /**
     * 
     */
    public static function filterProcessosOrigensExternasFullText($query) {
        try {
            $query = "%{$query}%";

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT id, origem as value 
                FROM TB_PROCESSOS_ORIGEM 
                WHERE ORIGEM ILIKE ? 
                ORDER BY value
            ");
            $stmt->bindParam(1, $query, PDO::PARAM_STR);
            $stmt->execute();

            $uppers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($uppers as $upper) {
                $lowers[] = array_change_key_case($upper, CASE_LOWER);
            }

            return $lowers;
        } catch (PDOException $e) {
            print($e->getMessage());
            return null;
        }
    }

    /**
     * 
     */
    public static function filterDocumentosDigital($query, $caixa = null) {
        try {
            $query = "{$query}%";

            if ($caixa) {
                // Foi passada uma caixa, pegar classificacoes permitidas
                $stmt0 = Controlador::getInstance()->getConnection()->connection->prepare("
                    SELECT ID_CLASSIFICACAO FROM TB_CAIXAS WHERE ID = ? LIMIT 1
                ");
                $stmt0->bindParam(1, $caixa, PDO::PARAM_INT);
                $stmt0->execute();
                $out = $stmt0->fetch(PDO::FETCH_ASSOC);

                $classificacao = $out['ID_CLASSIFICACAO'];

                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                    SELECT VW_DOC_ARQ.ID, VW_DOC_ARQ.DIGITAL, VW_DOC_ARQ.ASSUNTO 
                    FROM VW_DOCUMENTOS_ARQUIVO VW_DOC_ARQ 
                        JOIN TB_CLASSIFICACAO cla on VW_DOC_ARQ.ID_CLASSIFICACAO = cla.ID
                    WHERE 
                        ? IN (cla.ID_CLASSIFICACAO_PAI, cla.ID) 
                        AND VW_DOC_ARQ.DIGITAL ILIKE ? 
                    ORDER BY VW_DOC_ARQ.DIGITAL ASC
                ");
                $stmt->bindParam(1, $classificacao, PDO::PARAM_STR);
                $stmt->bindParam(2, $query, PDO::PARAM_STR);
            } else {
                // Não foi definida, pegar tudo
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                    SELECT ID, DIGITAL, ASSUNTO 
                    FROM VW_DOCUMENTOS_ARQUIVO 
                    WHERE DIGITAL ILIKE ? 
                    ORDER BY DIGITAL ASC
                ");
                $stmt->bindParam(1, $query, PDO::PARAM_STR);
            }
            $stmt->execute();

            $uppers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($uppers as $upper) {
                $lowers[] = array_change_key_case($upper, CASE_LOWER);
            }

            return $lowers;
        } catch (PDOException $e) {
            print($e->getMessage());
            return null;
        }
    }

}