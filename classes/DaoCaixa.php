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
 * @author jhonatan.flach
 */
class DaoCaixa
{

    /**
     * 
     */
    public static function getCaixa ($caixa = false, $field = false)
    {
        try {

            

            /**
             * @todo BugFix Melhorar performance...
             * @todo Melhorar este metodo...
             */
            $campos = ($field) ? $field : strtolower('ID,NU_CAIXA,DT_CADASTRO,ID_CLASSIFICACAO,ID_USUARIO,ID_UNIDADE,NU_ANO_CAIXA,ST_FINALIZADA,ST_ATIVO');

            if (!$caixa) {
                throw new Exception($e);
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT {$campos} FROM TB_CAIXAS WHERE ID = ? LIMIT 1");
            $stmt->bindParam(1, $caixa, PDO::PARAM_INT);
            $stmt->execute();

            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {

                $out = array_change_key_case(($out), CASE_LOWER);

                if (!$field) {
                    return $out;
                }
                return $out[$field];
            }

            return false;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * 
     */
    public static function uniqueCaixa (Caixa $caixa)
    {
        try {
            
            
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $caixa->nu_caixa = str_pad($caixa->nu_caixa, 7, '0', STR_PAD_LEFT);
            $caixa->st_ativo = 1;

            // checar se já existe uma caixa cadastrada com este número, nesta unidade
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT count(*) as num FROM TB_CAIXAS WHERE NU_CAIXA = ? AND ID_UNIDADE = ? AND ST_ATIVO = ? AND ID != ?");
            $stmt->bindParam(1, $caixa->caixa->nu_caixa, PDO::PARAM_STR);
            $stmt->bindParam(2, $caixa->caixa->id_unidade, PDO::PARAM_INT);
            $stmt->bindParam(3, $caixa->caixa->st_ativo, PDO::PARAM_INT);
            $stmt->bindParam(4, $caixa->caixa->id, PDO::PARAM_INT);
            $stmt->execute();

            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($out['NUM'] > 0) {
                // Já está cadastrado, retornar sem sucesso
                return new Output(array('success' => 'false', 'error' => 'Já existe caixa cadastrada nesta unidade com este número'));
            } else {
                // Não está cadastrado, retornar sucesso
                return new Output(array('success' => 'true'));
            }
        } catch (Exception $e) {
            // Erro, retornar sem sucesso
            return new Output(array('success' => 'false', 'error' => 'Erro ao tentar verificar duplicidade da caixa'));
        }
    }

    /**
     * 
     */
    public static function inserirCaixa (Caixa $caixa)
    {
        try {

            
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $caixa->nu_caixa = str_pad($caixa->nu_caixa, 7, '0', STR_PAD_LEFT);
            $usuario = Zend_Auth::getInstance()->getIdentity()->ID;
            $data_cadastro = Zend_Date::now()->get(Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_CAIXAS (NU_CAIXA, ID_CLASSIFICACAO, DT_CADASTRO, ID_USUARIO, ID_UNIDADE, NU_ANO_CAIXA, ST_FINALIZADA)
            VALUES (?,?,?,?,?,?,0)");
            $stmt->bindParam(1, $caixa->caixa->nu_caixa, PDO::PARAM_STR);
            if (!$caixa->caixa->id_classificacao || $caixa->caixa->id_classificacao == 'null') {
                $valor = 0;
                $stmt->bindParam(2, $valor, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(2, $caixa->caixa->id_classificacao, PDO::PARAM_INT);
            }

            $stmt->bindParam(3, $data_cadastro, PDO::PARAM_STR);
            $stmt->bindParam(4, $usuario, PDO::PARAM_INT);
            $stmt->bindParam(5, $caixa->caixa->id_unidade, PDO::PARAM_INT);
            $stmt->bindParam(6, $caixa->caixa->nu_ano_caixa, PDO::PARAM_STR);
            $stmt->execute();

            new Log('TB_CAIXAS', Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_CAIXAS_ID_SEQ'), Zend_Auth::getInstance()->getIdentity()->ID, 'inserir');

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => 'Caixa cadastrada com sucesso!'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollBack();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * 
     */
    public static function alterarCaixa (Caixa $caixa)
    {
        try {

            
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $caixa->nu_caixa = str_pad($caixa->nu_caixa, 7, '0', STR_PAD_LEFT);

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_CAIXAS SET NU_CAIXA=?, ID_CLASSIFICACAO=?, ID_UNIDADE=?, NU_ANO_CAIXA=?
            WHERE ID = ?");
            $stmt->bindParam(1, $caixa->caixa->nu_caixa, PDO::PARAM_STR);
            $stmt->bindParam(2, $caixa->caixa->id_classificacao, PDO::PARAM_INT);
            $stmt->bindParam(3, $caixa->caixa->id_unidade, PDO::PARAM_INT);
            $stmt->bindParam(4, $caixa->caixa->nu_ano_caixa, PDO::PARAM_STR);
            $stmt->bindParam(5, $caixa->caixa->id, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_CAIXAS', $caixa->caixa->id, Zend_Auth::getInstance()->getIdentity()->ID, 'alterar :: ' . print_r($caixa, true));

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => 'Informações alteradas com sucesso!'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollBack();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * 
     */
    public static function deleteCaixa ($id, $status)
    {
        try {

            
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_CAIXAS SET ST_ATIVO = ? WHERE ID = ?");
            $stmt->bindParam(1, $status, PDO::PARAM_INT);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_CAIXAS', $id, Zend_Auth::getInstance()->getIdentity()->ID, 'excluir');

            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true', 'message' => 'Caixa excluída com sucesso!'));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * 
     */
    public static function alterarFinalizada ($id, $finalizada)
    {
        try {

            
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_CAIXAS SET ST_FINALIZADA = ? WHERE ID = ?");
            $stmt->bindParam(1, $finalizada, PDO::PARAM_INT);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($finalizada) {
                new Log('TB_CAIXAS', $id, Zend_Auth::getInstance()->getIdentity()->ID, 'finalizar');
            } else {
                new Log('TB_CAIXAS', $id, Zend_Auth::getInstance()->getIdentity()->ID, 'reabrir');
            }

            $mudanca = $finalizada ? "finalizada" : "reaberta";

            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true', 'message' => 'Caixa ' . $mudanca . ' com sucesso!'));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * 
     */
    public static function colocarDocumento ($id_caixa, $id_documento)
    {
        try {

            
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            // checar se documento já está em qualquer caixa
            $stmt1 = Controlador::getInstance()->getConnection()->connection->prepare("SELECT count(ID_DOCUMENTO) as num FROM TB_CAIXAS_DOCUMENTOS WHERE ID_DOCUMENTO = ?");
            $stmt1->bindParam(1, $id_documento, PDO::PARAM_INT);
            $stmt1->execute();

            $num = $stmt1->fetch(PDO::FETCH_ASSOC);

            if ($num['NUM'] > 0) {
                // Já está cadastrado, retornar sem sucesso
                return new Output(array('success' => 'false', 'error' => 'Documento já se encontra em uma caixa'));
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_CAIXAS_DOCUMENTOS (ID_CAIXA, ID_DOCUMENTO)
            VALUES (?,?)");
            $stmt->bindParam(1, $id_caixa, PDO::PARAM_INT);
            $stmt->bindParam(2, $id_documento, PDO::PARAM_INT);
            $res = $stmt->execute();

            // checar se já existe uma caixa cadastrada com este número, nesta unidade
            $stmt3 = Controlador::getInstance()->getConnection()->connection->prepare("SELECT NU_CAIXA, DS_UNIDADE FROM VW_CAIXAS WHERE ID = ?");
            $stmt3->bindParam(1, $id_caixa, PDO::PARAM_INT);
            $stmt3->execute();

            $out = $stmt3->fetch(PDO::FETCH_ASSOC);
            $usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
            $data = date("d/m/Y " . " - " . "H:i:s");

            $tramite = "Adicionado na caixa {$out['NU_CAIXA']} da diretoria {$out['DS_UNIDADE']} por {$usuario} em {$data}.";
            $stmt2 = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_CADASTRO SET ULTIMO_TRAMITE = ? WHERE ID = ?");
            $stmt2->bindParam(1, $tramite, PDO::PARAM_STR);
            $stmt2->bindParam(2, $id_documento, PDO::PARAM_INT);
            $stmt2->execute();

            new CaixasHistoricos($id_caixa, $id_documento, Zend_Auth::getInstance()->getIdentity()->ID, 'ADICIONOU');

            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true', 'message' => 'Documento adicionado na caixa com sucesso!'));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * 
     */
    public static function retirarDocumento ($id)
    {
        try {

            
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            // pegar dados do documento
            $stmt1 = Controlador::getInstance()->getConnection()->connection->prepare("SELECT * FROM TB_CAIXAS_DOCUMENTOS WHERE ID = ?");
            $stmt1->bindParam(1, $id, PDO::PARAM_INT);
            $stmt1->execute();

            $doc = $stmt1->fetch(PDO::FETCH_ASSOC);

            $id_caixa = $doc['ID_CAIXA'];
            $id_documento = $doc['ID_DOCUMENTO'];

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("DELETE FROM TB_CAIXAS_DOCUMENTOS WHERE ID = ?");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();

            // checar se já existe uma caixa cadastrada com este número, nesta unidade
            $stmt3 = Controlador::getInstance()->getConnection()->connection->prepare("SELECT NU_CAIXA, DS_UNIDADE FROM VW_CAIXAS WHERE ID = ?");
            $stmt3->bindParam(1, $id_caixa, PDO::PARAM_INT);
            $stmt3->execute();

            $out = $stmt3->fetch(PDO::FETCH_ASSOC);
            $usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
            $data = date("d/m/Y " . " - " . "H:i:s");

            $tramite = "Retirado da caixa {$out['NU_CAIXA']} da diretoria {$out['DS_UNIDADE']} por {$usuario} em {$data}.";
            $stmt2 = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_CADASTRO SET ULTIMO_TRAMITE = ? WHERE ID = ?");
            $stmt2->bindParam(1, $tramite, PDO::PARAM_STR);
            $stmt2->bindParam(2, $id_documento, PDO::PARAM_INT);
            $stmt2->execute();

            new CaixasHistoricos($id_caixa, $id_documento, Zend_Auth::getInstance()->getIdentity()->ID, 'REMOVEU');

            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true', 'message' => 'Documento retirado da caixa com sucesso!'));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

}