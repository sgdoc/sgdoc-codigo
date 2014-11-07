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

include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.2/classes/CFModelDocumentoCamposDemanda.php');

class DaoDocumentoDemanda extends DaoDocumento {

    public static function alterarDocumento(Documento $documento) {
        try {
            $usuario = Controlador::getInstance()->usuario;

            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $documento->documento->fg_prazo = $documento->documento->fg_prazo == 'true' ? 1 : 0;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                UPDATE TB_DOCUMENTOS_CADASTRO 
                SET 
                    DT_DOCUMENTO = ?, DT_ENTRADA = ?, TIPO = ?, NUMERO = ?,  ORIGEM = ?, 
                    INTERESSADO = ?, ID_ASSUNTO = ?,  ASSUNTO_COMPLEMENTAR = ?, CARGO = ?,  
                    ASSINATURA = ?,  DESTINO = ?, RECIBO = ?,  TECNICO_RESPONSAVEL = ?, 
                    PROCEDENCIA = ?, DT_PRAZO = ?, FG_PRAZO = ?, PRIORIDADE = ? 
                WHERE DIGITAL = ?
            ");


            //Trata a prioridade quando for do tipo PAUTA para se ter múltiplas 
            if ($documento->documento->tipo == "ATA" && (($prioridade == 'null') || (strlen(trim($prioridade)) == 0))) {
                $prioridade = NULL;
                $prioridadeType = PDO::PARAM_NULL;
            }

            #10491
            if ($documento->documento->conteudo != '') {

                $sqDocumentoDemanda = current(CFModelDocumento::factory()->findByParam(array('DIGITAL' => $documento->documento->digital)))->ID;

                $stff = Controlador::getInstance()->getConnection()->connection->prepare("SELECT 1 FROM SGDOC.ext__snas__tb_documentos_conteudo WHERE id = ? LIMIT 1");
                $stff->bindParam(1, $sqDocumentoDemanda, PDO::PARAM_INT);
                $stff->execute();

                if (false == $stff->fetch(PDO::FETCH_ASSOC)) {
                    //inserir conteudo...
                    $stff = Controlador::getInstance()->getConnection()->connection->prepare("insert into SGDOC.ext__snas__tb_documentos_conteudo (id,conteudo) values (?,?)");
                    $stff->bindParam(1, $sqDocumentoDemanda, PDO::PARAM_INT);
                    $stff->bindParam(2, $documento->documento->conteudo, PDO::PARAM_STR);
                    $stff->execute();
                } else {
                    //atualizar conteudo...
                    $stff = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE sgdoc.ext__snas__tb_documentos_conteudo set conteudo = ? WHERE id = ?");
                    $stff->bindParam(1, $documento->documento->conteudo, PDO::PARAM_STR);
                    $stff->bindParam(2, $sqDocumentoDemanda, PDO::PARAM_INT);
                    $stff->execute();
                }
            }

            $stmt->bindParam(1, Util::formatDate($documento->documento->dt_documento), PDO::PARAM_STR); //DATA_DOCUMENTO' ,
            $stmt->bindParam(2, Util::formatDate($documento->documento->dt_entrada), PDO::PARAM_STR); //DATA_ENTRADA' ,
            $stmt->bindParam(3, $documento->documento->tipo, PDO::PARAM_STR); //TIPO' ,
            $stmt->bindParam(4, $documento->documento->numero, PDO::PARAM_STR); //NUMERO' ,
            $stmt->bindParam(5, $documento->documento->origem, PDO::PARAM_STR); //ORIGEM' ,
            $stmt->bindParam(6, $documento->documento->interessado, PDO::PARAM_STR); //INTERESSADO' ,
            $stmt->bindParam(7, $documento->documento->assunto, PDO::PARAM_INT); //ID_ASSUNTO' ,
            $stmt->bindParam(8, $documento->documento->assunto_complementar, PDO::PARAM_STR); //ASSUNTO_COMPLEMENTAR' ,
            $stmt->bindParam(9, $documento->documento->cargo, PDO::PARAM_STR); //CARGO' ,
            $stmt->bindParam(10, $documento->documento->assinatura, PDO::PARAM_STR); //ASSINATURA' ,
            $stmt->bindParam(11, $documento->documento->destino, PDO::PARAM_STR); //DESTINO' ,
            $stmt->bindParam(12, $documento->documento->recibo, PDO::PARAM_STR); //RECIBO' ,
            $stmt->bindParam(13, $documento->documento->tecnico_responsavel, PDO::PARAM_STR); //TECNICO_RESPONSAVEL' ,
            $stmt->bindParam(14, $documento->documento->procedencia, PDO::PARAM_STR); //PROCEDENCIA' ,
            $stmt->bindParam(15, Util::formatDate($documento->documento->dt_prazo), PDO::PARAM_STR); //PRAZO' ,
            $stmt->bindParam(16, $documento->documento->fg_prazo, PDO::PARAM_STR); //FG_PRAZO' ,
            $stmt->bindParam(17, $prioridade, $prioridadeType); //PRIORIDADE' ,
            $stmt->bindParam(18, $documento->documento->digital, PDO::PARAM_STR); //DIGITAL' ,
            $stmt->execute();

            $acao = "Cadastrado complementado.";
            $destino = "XXXXX";
            $origem = "XXXXX";

            /* Atualiza prioridade e participantes */

            //tratar PRIORIDADES
            if (isset($documento->documento->extras[PRIORIDADES])) {

                $prioridades = $documento->documento->extras[PRIORIDADES];

                if (is_array($prioridades)) {

                    //desabilitar todos os vinculos do documento com as campos extras
                    CFModelDocumentoCamposDemanda::factory()->disassociateAllByDigital($documento->documento->digital, "PR");

                    for ($i = 0; $i < count($prioridades["id"]); $i++) {
                        if (CFModelDocumentoCamposDemanda::factory()->isExists($documento->documento->digital, $prioridades["id_campo"][$i], "PR")) { //Se existir atualiza
                            CFModelDocumentoCamposDemanda::factory()->updateAssociationWithDigital($documento->documento->digital, $prioridades["id_campo"][$i], 1, "PR");
                        } else { //Se não cria
                            CFModelDocumentoCamposDemanda::factory()->createAssociationWithDigital($documento->documento->digital, $prioridades["id_campo"][$i], "PR");
                        }
                    }
                }
            }
            //tratar PARTICIPANTES
            if (isset($documento->documento->extras[PARTICIPANTES])) {

                $participantes = $documento->documento->extras[PARTICIPANTES];

                if (is_array($participantes)) {

                    //desabilitar todos os vinculos do documento com as campos extras
                    CFModelDocumentoCamposDemanda::factory()->disassociateAllByDigital($documento->documento->digital, "PA");

                    for ($i = 0; $i < count($participantes["id"]); $i++) {
                        if (CFModelDocumentoCamposDemanda::factory()->isExists($documento->documento->digital, $participantes["id_campo"][$i], "PA")) { //Se existir atualiza
                            CFModelDocumentoCamposDemanda::factory()->updateAssociationWithDigital($documento->documento->digital, $participantes["id_campo"][$i], 1, "PA");
                        } else { //Se não cria
                            CFModelDocumentoCamposDemanda::factory()->createAssociationWithDigital($documento->documento->digital, $participantes["id_campo"][$i], "PA");
                        }
                    }
                }
            }

            /**
             * BugFix : Lancava Notice
             */
            $id_usuario = $usuario->ID;
            $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
            $id_unidade = $usuario->ID_UNIDADE;
            $diretoria = DaoUnidade::getUnidade($id_unidade, 'nome');

            $stmm = Controlador::getInstance()->getConnection()->connection->prepare("
                INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS 
                    (DIGITAL, ID_USUARIO, USUARIO, ID_UNIDADE, DIRETORIA, 
                    ACAO, ORIGEM, DESTINO, DT_TRAMITE) 
                VALUES 
                    ( ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP(0) )
            ");
            $stmm->bindParam(1, $documento->documento->digital, PDO::PARAM_STR);
            $stmm->bindParam(2, $id_usuario, PDO::PARAM_INT);
            $stmm->bindParam(3, $nome_usuario, PDO::PARAM_STR);
            $stmm->bindParam(4, $id_unidade, PDO::PARAM_INT);
            $stmm->bindParam(5, $diretoria, PDO::PARAM_STR);
            $stmm->bindParam(6, $acao, PDO::PARAM_STR);
            $stmm->bindParam(7, $origem, PDO::PARAM_STR);
            $stmm->bindParam(8, $destino, PDO::PARAM_STR);
            $stmm->execute();

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true'));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    public static function salvarDocumento(Documento $documento) {
        try {
            $ultimo_tramite = "Área de Trabalho - " . DaoUnidade::getUnidade(null, 'nome');
            $acao = "Documento cadastrado";

            $id_usuario = Zend_Auth::getInstance()->getIdentity()->ID;
            $id_unidade = $documento->documento->id_unid_area_trabalho ?
                    $documento->documento->id_unid_area_trabalho : Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
            $id_unidade_historico = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;

            $destino = "XXXXX";

            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                INSERT INTO TB_DOCUMENTOS_CADASTRO
                    (DT_DOCUMENTO, DT_ENTRADA, TIPO,NUMERO, ORIGEM, INTERESSADO,
                    ID_ASSUNTO, ASSUNTO_COMPLEMENTAR, CARGO,ASSINATURA, DESTINO, 
                    RECIBO, TECNICO_RESPONSAVEL, ID_USUARIO, ID_UNIDADE,
                    PROCEDENCIA, DT_CADASTRO, DIGITAL, DT_PRAZO, ID_UNID_AREA_TRABALHO,
                    ULTIMO_TRAMITE)
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
            ");

            $stmt->bindParam(1, Util::formatDate($documento->documento->data_documento), PDO::PARAM_STR); //DT_DOCUMENTO' ,
            $stmt->bindParam(2, Util::formatDate($documento->documento->data_entrada), PDO::PARAM_STR); //DT_ENTRADA' ,
            $stmt->bindParam(3, $documento->documento->tipo, PDO::PARAM_STR); //TIPO' ,
            $stmt->bindParam(4, $documento->documento->numero, PDO::PARAM_STR); //NUMERO' ,
            $stmt->bindParam(5, $documento->documento->origem, PDO::PARAM_STR); //ORIGEM' ,
            $stmt->bindParam(6, $documento->documento->interessado, PDO::PARAM_STR); //INTERESSADO' ,
            $stmt->bindParam(7, $documento->documento->assunto, PDO::PARAM_INT); //ID_ASSUNTO' ,
            $stmt->bindParam(8, $documento->documento->assunto_complementar, PDO::PARAM_STR); //ASSUNTO_COMPLEMENTAR' ,
            $stmt->bindParam(9, $documento->documento->cargo, PDO::PARAM_STR); //CARGO' ,
            $stmt->bindParam(10, $documento->documento->assinatura, PDO::PARAM_STR); //ASSINATURA' ,
            $stmt->bindParam(11, $documento->documento->destino, PDO::PARAM_STR); //DESTINO' ,
            $stmt->bindParam(12, $documento->documento->recibo, PDO::PARAM_STR); //RECIBO' ,
            $stmt->bindParam(13, $documento->documento->tecnico_responsavel, PDO::PARAM_STR); //TECNICO_RESPONSAVEL' ,
            $stmt->bindParam(14, $id_usuario, PDO::PARAM_INT); //ID_USUARIO' ,
            $stmt->bindParam(15, $id_unidade_historico, PDO::PARAM_INT); //ID_UNIDADE' ,
            $stmt->bindParam(16, $documento->documento->procedencia, PDO::PARAM_STR); //PROCEDENCIA' ,
            $stmt->bindParam(17, date("Y-m-d"), PDO::PARAM_STR); //DT_CADASTRO' ,
            $stmt->bindParam(18, $documento->documento->digital, PDO::PARAM_STR); //DIGITAL' ,
            $stmt->bindParam(19, Util::formatDate($documento->documento->prazo), PDO::PARAM_STR); //PRAZO' ,
            $stmt->bindParam(20, $id_unidade, PDO::PARAM_INT); //ID_UNID_AREA_TRABALHO' ,
            $stmt->bindParam(21, $ultimo_tramite, PDO::PARAM_STR); //'ULTIMO_TRAMITE'
            $stmt->execute();

            $id = Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_DOCUMENTOS_CADASTRO_ID_SEQ');

            $sttt = Controlador::getInstance()->getConnection()->connection->prepare("
                UPDATE TB_DIGITAL SET USO = '1', ID_USUARIO = ? WHERE DIGITAL = ? AND ID_UNIDADE = ?
            ");
            $sttt->bindParam(1, $id_usuario, PDO::PARAM_INT);
            $sttt->bindParam(2, $documento->documento->digital, PDO::PARAM_STR);
            $sttt->bindParam(3, $id_unidade, PDO::PARAM_INT);
            $sttt->execute();

            $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
            $diretoria = DaoUnidade::getUnidade($id_unidade_historico, 'nome');
            $objOrigem = DaoUnidade::getUnidade($id_unidade);
            $tx_origem = $objOrigem['nome'] . ' - ' . $objOrigem['sigla'];

            $stmm = Controlador::getInstance()->getConnection()->connection->prepare("
                INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS 
                    (DIGITAL,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE) 
                VALUES (?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP(0))
            ");
            $stmm->bindParam(1, $documento->documento->digital, PDO::PARAM_STR);
            $stmm->bindParam(2, $id_usuario, PDO::PARAM_INT);
            $stmm->bindParam(3, $nome_usuario, PDO::PARAM_STR);
            $stmm->bindParam(4, $id_unidade_historico, PDO::PARAM_INT);
            $stmm->bindParam(5, $diretoria, PDO::PARAM_STR);
            $stmm->bindParam(6, $acao, PDO::PARAM_STR);
            $stmm->bindParam(7, $tx_origem, PDO::PARAM_STR);
            $stmm->bindParam(8, $destino, PDO::PARAM_STR);
            $stmm->execute();

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'id' => $id));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            LogError::sendReport($e);
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    public static function removerImagensDocumento($documento) {
        try {
            // Remover históricos de tramite
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $stmm = Controlador::getInstance()->getConnection()->connection->prepare("
                DELETE FROM TB_DOCUMENTOS_IMAGEM WHERE DIGITAL = ?
            ");
            $stmm->bindParam(1, $documento->documento->digital, PDO::PARAM_STR);
            $stmm->execute();

            $caminhoLOTE = __CAM_UPLOAD__ . '/' . Util::gerarRaiz($documento->documento->digital, __CAM_UPLOAD__) . '/' .
                    $documento->documento->digital;

            $arquivos = @scandir($caminhoLOTE);
            if (!$arquivos) {
                mkdir($caminhoLOTE);
                $arquivos = scandir($caminhoLOTE);
            }
            unset($arquivos[0]); // remove '.'
            unset($arquivos[1]); // remove '..'

            foreach ($arquivos as $filename) {
                // Percorre a pasta
                if ($filename != '.' && $filename != '..') {
                    // deleta o arquivo
                    unlink($caminhoLOTE . '/' . $filename);
                }
            }
            // remove diretório
            rmdir($caminhoLOTE);

            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true', 'error' => 'Imagens removidas com sucesso'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            LogError::sendReport($e);
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    public static function removerDocumento($documento) {
        try {
            $usuario = Controlador::getInstance()->usuario;

            // Remover históricos de tramite
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $stmm = Controlador::getInstance()->getConnection()->connection->prepare("
                DELETE FROM TB_HISTORICO_TRAMITE_DOCUMENTOS WHERE DIGITAL = ?
            ");
            $stmm->bindParam(1, $documento->documento->digital, PDO::PARAM_STR);
            $stmm->execute();

            // Liberar Digital
            $id_unidade = $usuario->ID_UNIDADE;
            $sttt = Controlador::getInstance()->getConnection()->connection->prepare("
                UPDATE TB_DIGITAL SET USO = '0', ID_USUARIO = NULL WHERE DIGITAL = ? AND ID_UNIDADE = ?
            ");
            $sttt->bindParam(1, $documento->documento->digital, PDO::PARAM_STR);
            $sttt->bindParam(2, $id_unidade, PDO::PARAM_INT);
            $sttt->execute();

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                DELETE FROM TB_DOCUMENTOS_CADASTRO WHERE DIGITAL = ?
            ");
            $stmt->bindParam(1, $documento->documento->digital, PDO::PARAM_STR);
            $stmt->execute();

            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true', 'error' => 'Operações desfeitas com sucesso!'));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            LogError::sendReport($e);
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * Retorna informações de cadastro de um documento
     * @param type $documento
     * @param type $field
     * @return boolean
     * @throws PDOException
     */
    public static function getDocumento($documento = false, $field = false) {
        try {
            /**
             * @todo BugFix Melhorar performance...
             * @todo Melhorar este metodo...
             */
            $campos = ($field) ? $field : strtolower('
                DIGITAL, ID, DT_DOCUMENTO, DT_ENTRADA, TIPO,NUMERO, ORIGEM,
                INTERESSADO, PROCEDENCIA, ID_ASSUNTO, CARGO,ASSINATURA, DESTINO,
                RECIBO, TECNICO_RESPONSAVEL, ID_UNIDADE, DT_PRAZO, FG_PRAZO,
                ULTIMO_TRAMITE, ASSUNTO_COMPLEMENTAR, ID_CLASSIFICACAO,
                ID_UNID_AREA_TRABALHO, PRIORIDADE
            ');
            $condicao = filter_var($documento, FILTER_VALIDATE_INT) ? 'ID' : 'DIGITAL';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT {$campos} FROM TB_DOCUMENTOS_CADASTRO WHERE $condicao = ? LIMIT 1
            ");

            $stmt->bindParam(1, $documento, PDO::PARAM_STR);
            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                /* Padronizar com caixa baixa o os indices dos arrays */
                $out = array_change_key_case($out, CASE_LOWER);
                if (!$field) {
                    return $out;
                }
                return $out[$field];
            }

            return false;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public static function updateStatusDocumentosImagens($digital, $md5, $status) {

        try {
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $stmt = null;

            /**
             * Legenda Status
             * 0 - Confidencial (Pagina)
             * 1 - Publico (Pagina)
             * 2 - Excluido (Pagina)
             * 3 - Confidencial (Documento)
             * 4 - Publico (Documento)
             * 5 - Excluido (Documento)
             */
            if ($status <= 2) {
                /**
                 *  Somente os status da pagina 
                 */
                $sql = '';

                if (!empty($md5)) {
                    $sql = ' AND MD5 = ? ';
                }

                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_IMAGEM SET FLG_PUBLICO = ? WHERE DIGITAL = ? {$sql} AND FLG_PUBLICO != 2");
                $stmt->bindParam(1, $status, PDO::PARAM_INT);
                $stmt->bindParam(2, $digital, PDO::PARAM_STR);
                if (!empty($md5)) {
                    $stmt->bindParam(3, $md5, PDO::PARAM_STR);
                }
            } else {
                /**
                 *  Somente os status do documento 
                 */
                switch ($status) {
                    case 3 : $status = 0;
                        break;
                    case 4 : $status = 1;
                        break;
                    case 5 : $status = 2;
                        break;
                }

                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                    UPDATE TB_DOCUMENTOS_IMAGEM SET FLG_PUBLICO = ? WHERE DIGITAL = ? AND FLG_PUBLICO != 2
                ");
                $stmt->bindParam(1, $status, PDO::PARAM_INT);
                $stmt->bindParam(2, $digital, PDO::PARAM_STR);
            }

            $stmt->execute();

            Controlador::getInstance()->getConnection()->connection->commit();

            return array('success' => 'true', 'message' => Util::fixErrorString('Operação concluída com sucesso!'));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollBack();
            print(json_encode(array('success' => 'false', 'error' => Util::fixErrorString('Ocorreu um erro ao tentar executar a operação!' . $e->getMessage()))));
        }
    }

    public static function numDigitalDisponivelSic() {
        try {
            $id_unidade = DaoUnidade::getUnidade(null, 'id');

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT DIGITAL FROM TB_DIGITAL WHERE ID_UNIDADE = ? AND USO != '1' LIMIT 1
            ");
            $stmt->bindParam(1, $id_unidade, PDO::PARAM_INT);

            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                return new Output(array('success' => 'true', 'digital' => $out['DIGITAL']));
            } else {
                return new Output(array('success' => 'false', 'error' => 'Nenhuma digital disponivel'));
            }
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public static function validarNumSolicitacaoSic($numero) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT ID FROM TB_DOCUMENTOS_CADASTRO WHERE NUMERO = ?
            ");
            $stmt->bindParam(1, $numero, PDO::PARAM_STR);

            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                return new Output(array('success' => 'false', 'error' => 'Número de solicitação já cadastrado no sistema'));
            } else {
                return new Output(array('success' => 'true'));
            }
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Query para o gráfico diário por unidades
     * @param $where
     * @return array;
     */
    public static function getQtdeDocumentoImagensDataInclusao($where) {
        try {
            $sql = "
                SELECT to_char(doim.DAT_INCLUSAO,'dd/mm/yyyy') as data_inclusao,
                           doim.DAT_INCLUSAO datatime_inclusao,
                           count(doim.digital) as total_imagens,
                           un.nome as nome_unidade,
                           un.ID as id_unidade
                      FROM TB_DOCUMENTOS_IMAGEM doim
                INNER JOIN TB_DOCUMENTOS_CADASTRO doca
                        ON doim.DIGITAL = doca.DIGITAL
                INNER JOIN TB_DIGITAL di
                        ON doca.DIGITAL = di.DIGITAL
                INNER JOIN TB_UNIDADES un
                        ON di.id_unidade = un.id
                     WHERE to_char(doim.DAT_INCLUSAO,'dd/mm/yyyy') = '{$where->datInclusao}'
                  GROUP BY nome_unidade, data_inclusao
                  ORDER BY data_inclusao
            ";

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);

            $stmt->execute();
            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($out)) {
                return $out;
            }
            return false;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Query para o gráfico por perido
     * @param $where
     * @return array;
     */
    public static function getQtdeDocumentoImagensPorPeriodo($where) {
        try {
            $sql = "SELECT to_char(doim.DAT_INCLUSAO,'dd/mm/yyyy') as data_inclusao,
                           count(doim.digital) as total_imagens,
                           un.nome as nome_unidade,
                           un.ID as id_unidade
                      FROM TB_DOCUMENTOS_IMAGEM doim
                INNER JOIN TB_DOCUMENTOS_CADASTRO doca
                        ON doim.DIGITAL = doca.DIGITAL
                INNER JOIN TB_DIGITAL di
                        ON doca.DIGITAL = di.DIGITAL
                INNER JOIN TB_UNIDADES un
                        ON di.id_unidade = un.id
                     WHERE to_char(doim.DAT_INCLUSAO,'yyyy-mm-dd 00:00:00') 
                        between '{$where->dataInicio}' and '{$where->dataAtual}'
                  GROUP BY data_inclusao
                  ORDER BY data_inclusao";

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->execute();
            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                return $out;
            }
            return false;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * @return boolean
     * @param string $digital
     */
    public static function checaDocumentoExisteDigital($digital) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT 1 FROM TB_DOCUMENTOS_CADASTRO WHERE DIGITAL = ? LIMIT 1
            ");
            $stmt->bindParam(1, $digital, PDO::PARAM_STR);
            $stmt->execute();

            return ($stmt->fetch(PDO::FETCH_ASSOC) === false) ? false : true;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public static function getHistoricoComentarios(array $where) {
        try {
            $sql = "SELECT ID, 
                           DIGITAL, 
                           DT_CADASTRO, 
                           TEXTO_COMENTARIO, 
                           USUARIO, 
                           DIRETORIA
                      FROM VW_COMENTARIOS_DOCUMENTOS
                     WHERE ST_ATIVO = 1";

            if (isset($where['digital']) && !empty($where['digital'])) {
                $sql .= " AND DIGITAL = ?";
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam(1, $where['digital'], PDO::PARAM_STR);

            $stmt->execute();
            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($out) > 0) {
                return $out;
            }
            return false;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public static function getHistoricoDespachos(array $where) {
        try {
            $sql = "SELECT ID, 
                           DIGITAL, 
                           USUARIO, 
                           DIRETORIA, 
                           DT_CADASTRO, 
                           DT_DESPACHO, 
                           TEXTO_DESPACHO, 
                           COMPLEMENTO, 
                           ASSINATURA_DESPACHO
                      FROM VW_DESPACHOS_DOCUMENTOS
                     WHERE ST_ATIVO = 1";

            if (isset($where['digital']) && !empty($where['digital'])) {
                $sql .= " AND DIGITAL = ?";
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam(1, $where['digital'], PDO::PARAM_STR);

            $stmt->execute();
            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($out) > 0) {
                return $out;
            }
            return false;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public static function getHistoricoTramites(array $where) {
        try {
            $sql = "SELECT ID, 
                           DIGITAL,
                           USUARIO,
                           DIRETORIA,
                           ACAO,
                           ORIGEM,
                           DESTINO,
                           DT_TRAMITE
                      FROM VW_HISTORICO_TRAMITE_DOCUMENTOS
                     WHERE ST_ATIVO = 1";

            if (isset($where['digital']) && !empty($where['digital'])) {
                $sql .= " AND DIGITAL = ?";
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam(1, $where['digital'], PDO::PARAM_STR);

            $stmt->execute();
            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($out) > 0) {
                return $out;
            }
            return false;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /*
     * Atualiza qualquer campo do documento desde que o mesmo exista
     * 
     * **OBS: É necessário colocalo dentro de uma transação que já está ocorrendo
     * 
     * @param string $digital
     * @param array $camposValores
     * 
     * @return boolean
     */

    public static function updateGenerico($digital, $camposValores) {
        try {
            foreach ($camposValores as $campo => $valor) {
                $stmt = null;
                $stmt = Controlador::getInstance()
                        ->getConnection()
                        ->connection
                        ->prepare("UPDATE TB_DOCUMENTOS_CADASTRO SET {$campo} = ? WHERE DIGITAL = ?");

                $stmt->bindParam(1, $valor, PDO::PARAM_STR);
                $stmt->bindParam(2, $digital, PDO::PARAM_STR);

                $stmt->execute();
            }
            return true;
        } catch (PDOException $e) {
            throw $e;
        }
    }

}
