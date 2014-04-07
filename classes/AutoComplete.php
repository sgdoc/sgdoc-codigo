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

class AutoComplete extends Base {

    /**
     * 
     */
    public static function filterAssuntosProcessosFullText($query) {
        try {
            $busca = "";
            $num = intval($query);
            if ($num != 0) {
                $busca = "ID = ?";
                $query = (int) $query;
            } else {
                $busca = "ASSUNTO ILIKE ?";
                $query = "%{$query}%";
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT id, assunto as value 
                FROM TB_PROCESSOS_ASSUNTO 
                WHERE ({$busca}) AND HOMOLOGADO = 1 
                ORDER BY (value) ASC
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
    public static function filterAssuntosDocumentosFullText($query) {
        try {
            $busca = "";
            $num = intval($query);
            if ($num != 0) {
                $busca = "ID = ?";
                $query = (int) $query;
            } else {
                $busca = "ASSUNTO ILIKE ?";
                $query = "%{$query}%";
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT id, assunto as value 
                FROM TB_DOCUMENTOS_ASSUNTO 
                WHERE ({$busca}) AND HOMOLOGADO = 1 
                ORDER BY value ASC
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
     * @param string $query
     * @param integer $idUnidade
     * @return array|null
     */
    public static function filterRecebidoPorFullText($query, $idUnidade) {
        try {
            $query = "%{$query}%";

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT u.id, u.nome AS value
                FROM tb_privilegios AS p
                    LEFT JOIN tb_usuarios_unidades AS uu ON uu.id_unidade = p.id_unidade
                    LEFT JOIN tb_usuarios AS u ON u.id = uu.id_usuario
                    LEFT JOIN tb_privilegios_usuarios AS pu ON pu.id_usuario = u.id
                WHERE p.id_unidade = {$idUnidade}
                    AND p.id_recurso = 201
                    AND u.nome ILIKE ?
                    AND (pu.permissao IS NULL OR pu.permissao = 1)
                GROUP BY u.id
                ORDER BY u.nome ASC");

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
    public static function filterInteressadosProcessosFullText($query) {
        try {
            $query = "%{$query}%";

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT id, interessado as value 
                FROM TB_PROCESSOS_INTERESSADOS 
                WHERE (CNPJ_CPF ILIKE ? OR INTERESSADO ILIKE ?) 
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
    public static function filterUnidadesInternasFullText($query, $mirror = false) {
        try {
            $query_nome = "%{$query}%";
            $field = ($mirror) ? 'nome' : 'id';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT {$field} as id, nome as value 
                FROM TB_UNIDADES 
                WHERE st_ativo = 1 AND 
                    (ID != UOP) 
                    AND (fn_remove_acentuacao(SIGLA) ILIKE fn_remove_acentuacao(?)
                    OR fn_remove_acentuacao(NOME) ILIKE fn_remove_acentuacao(?)) 
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
                WHERE st_ativo = 1 AND 
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