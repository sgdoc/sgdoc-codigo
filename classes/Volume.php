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
class Volume extends Base {

    private $processo;
    private $idProcesso;
    private $data_abertura;
    private $post = array();
    private $print;

    /**
     * @return Volume
     */
    public static function factory($array) {
        return new self($array);
    }

    /**
     * 
     */
    public function __construct(array $array) {
        $this->setProcesso($array['processo']);
        $this->setIdProcesso($this->getProcFromDb($array['processo']));
        $_SESSION['PROCESSO_TERMO'] = $array['processo'];

        $this->post = $array;
        parent::__construct();
    }

    /**
     * 
     */
    public function hasOpened() {


        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID_PROCESSO_CADASTRO, DT_ABERTURA FROM TB_PROCESSOS_VOLUME
                                WHERE  ID_PROCESSO_CADASTRO = ? AND DT_ENCERRAMENTO IS NULL AND ST_ATIVO = 1");
        $stmt->bindValue(1, $this->getIdProcesso());
        $stmt->execute();
        $resul = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($resul)) {
            return false;
        } else {
            $this->data_abertura = $resul['DT_ABERTURA'];
            return true;
        }
    }

    /**
     * 
     */
    public function prepareOpen() {

        $out = new Output();

        try {
            if (!$this->hasOpened()) {
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT MAX(NU_VOLUME) AS volume,
                    MAX(FL_FINAL) AS final  FROM TB_PROCESSOS_VOLUME
                                WHERE ID_PROCESSO_CADASTRO = ? AND ST_ATIVO = 1 ");
                $stmt->bindValue(1, $this->getIdProcesso());
                $stmt->execute();
                $fetch = $stmt->fetch(PDO::FETCH_OBJ);

                if (empty($fetch->VOLUME)) {
                    $fetch->VOLUME = 1;
                } else {
                    $fetch->VOLUME++;
                }

                $fetch->SUCCESS = 'true';
                $fetch->FOLHA = ++$fetch->FINAL;
                $fetch->ACTION = 'open';
            } else {
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT A.NU_VOLUME AS volume, A.FL_INICIAL AS inicial, A.FL_FINAL AS final,
                                                A.DT_ABERTURA AS ABERTURA FROM TB_PROCESSOS_VOLUME AS A
                                                WHERE A.ID_PROCESSO_CADASTRO = ? AND A.DT_ENCERRAMENTO IS NULL
                                                AND A.ST_ATIVO = 1");
                $stmt->bindValue(1, $this->getIdProcesso());
                $stmt->execute();

                $fetch = $stmt->fetch(PDO::FETCH_OBJ);
                $fetch->ABERTURA = Util::formatDate($fetch->ABERTURA);
                $fetch->SUCCESS = 'true';
                $fetch->ACTION = 'close';
            }

            $out = new Output(array_change_key_case((Array) $fetch));
        } catch (PDOException $e) {
            throw $e;
        }

        $this->print = $out;
        return $out;
    }

    /**
     * 
     */
    public function closeVolume() {

        $out = new Output();

        Controlador::getInstance()->getConnection()->connection->beginTransaction();

        $id_usuario = Zend_Auth::getInstance()->getIdentity()->ID;
        $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
        $id_unidade = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
        $id_unidade_historico = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
        $diretoria = DaoUnidade::getUnidade($id_unidade_historico, 'nome');
        $objDiretoria = DaoUnidade::getUnidade($id_unidade);
        $tx_diretoria = $objDiretoria['nome'] . ' - ' . $objDiretoria['sigla'];

        try {
            if ($this->hasOpened()) {
                if (strtotime($this->data_abertura) > strtotime(Util::formatDate($this->post['data_encerrar']))) {
                    $out->success = 'false';
                    $out->message = "Data de encerramento deve ser posterior a data de abertura";
                } else {
                    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_PROCESSOS_VOLUME SET
                                                ID_USUARIO = ?, DT_ENCERRAMENTO = ?, FL_FINAL = ?
                                                WHERE ID_PROCESSO_CADASTRO = ? AND DT_ENCERRAMENTO IS NULL AND ST_ATIVO = 1");
                    $stmt->bindParam(1, $id_usuario, PDO::PARAM_INT);
                    $stmt->bindValue(2, Util::formatDate($this->post['data_encerrar']), PDO::PARAM_STR);
                    $stmt->bindParam(3, $this->post['folha_final'], PDO::PARAM_INT);
                    $stmt->bindValue(4, $this->getIdProcesso(), PDO::PARAM_INT);
                    $stmt->execute();

                    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_PROCESSOS
                                            (NUMERO_PROCESSO, ID_USUARIO, USUARIO, ID_UNIDADE, DIRETORIA, ACAO, ORIGEM, DESTINO, DT_TRAMITE)
                                            VALUES(?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
                    $stmt->bindValue(1, $this->getProcesso(), PDO::PARAM_STR);
                    $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
                    $stmt->bindParam(3, $nome_usuario, PDO::PARAM_STR);
                    $stmt->bindParam(4, $id_unidade_historico, PDO::PARAM_INT);
                    $stmt->bindParam(5, $diretoria, PDO::PARAM_STR);
                    $stmt->bindValue(6, "Encerrado {$this->post['volume']}º volume, finalizando na folha {$this->post['folha_final']}.", PDO::PARAM_STR);
                    $stmt->bindParam(7, $tx_diretoria, PDO::PARAM_STR);
                    $stmt->bindValue(8, "XXXXX", PDO::PARAM_STR);
                    $stmt->execute();
                    Controlador::getInstance()->getConnection()->connection->commit();

                    $out->success = 'true';
                    $out->processo = $this->getProcesso();
                }
            } else {
                $out->success = 'false';
                /**
                 * Corrigir
                 */
                $out->message = "Não foi possível encerrar o volume: Este volume nao esta aberto!";
            }
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            $out->success = 'false';
            $out->message = $e->getMessage();
        }
        $this->print = $out;
        return $out;
    }

    /**
     * 
     */
    public function openVolume() {

        $out = new Output();

        Controlador::getInstance()->getConnection()->connection->beginTransaction();

        $id_usuario = Zend_Auth::getInstance()->getIdentity()->ID;
        $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
        $id_unidade = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
        $id_unidade_historico = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
        $diretoria = DaoUnidade::getUnidade($id_unidade_historico, 'nome');
        $objDiretoria = DaoUnidade::getUnidade($id_unidade);
        $tx_diretoria = $objDiretoria['nome'] . ' - ' . $objDiretoria['sigla'];

        try {
            if (!$this->hasOpened()) {
                $object = $this->prepareOpen();

                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_PROCESSOS_VOLUME
                                            (ID_PROCESSO_CADASTRO, NU_VOLUME, FL_INICIAL, DT_ABERTURA, ID_USUARIO,ID_UNIDADE)
                                            VALUES(?,?,?,?,?,?)");
                $stmt->bindValue(1, $this->getIdProcesso(), PDO::PARAM_INT);
                $stmt->bindParam(2, $object->volume, PDO::PARAM_INT);
                $stmt->bindParam(3, $object->folha, PDO::PARAM_INT);
                $stmt->bindValue(4, Util::formatDate($this->post["pedido_abertura"]), PDO::PARAM_STR);
                $stmt->bindParam(5, $id_usuario, PDO::PARAM_INT);
                $stmt->bindParam(6, $id_unidade, PDO::PARAM_INT);
                $stmt->execute();

                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_PROCESSOS
                                        (NUMERO_PROCESSO, ID_USUARIO, USUARIO, ID_UNIDADE, DIRETORIA, ACAO, ORIGEM, DESTINO, DT_TRAMITE)
                                        VALUES(?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
                $stmt->bindValue(1, $this->getProcesso(), PDO::PARAM_STR);
                $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
                $stmt->bindParam(3, $nome_usuario, PDO::PARAM_STR);
                $stmt->bindParam(4, $id_unidade_historico, PDO::PARAM_INT);
                $stmt->bindParam(5, $diretoria, PDO::PARAM_STR);
                $stmt->bindValue(6, "Aberto {$object->volume}º volume, iniciando-se na folha {$object->folha}.", PDO::PARAM_STR);
                $stmt->bindParam(7, $tx_diretoria, PDO::PARAM_STR);
                $stmt->bindValue(8, "XXXXX", PDO::PARAM_STR);
                $stmt->execute();
                Controlador::getInstance()->getConnection()->connection->commit();

                $out->success = 'true';
                $out->processo = $this->getProcesso();
            } else {
                $out->success = 'false';
                $out->message = 'Volume já se encontra aberto!';
            }
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            $out = new Output(array('success' => 'false', 'message' => 'Não foi possível abrir o volume deste processo!'));
        }
        $this->print = $out;
        return $out;
    }

    /**
     * 
     */
    public function setProcesso($processo) {
        $this->processo = $processo;
    }

    /**
     * 
     */
    public function getProcesso() {
        return $this->processo;
    }

    /**
     * 
     */
    public function setIdProcesso($idProcesso) {
        $this->idProcesso = $idProcesso;
    }

    /**
     * 
     */
    public function getIdProcesso() {
        return $this->idProcesso;
    }

    /**
     * 
     */
    public function out() {
        return $this->print->toArray();
    }

    /**
     * 
     */
    public function getProcFromDb($processo) {

        $campo = is_int($processo) ? 'NUMERO_PROCESSO' : 'ID';
        $where = is_int($processo) ? 'ID' : 'NUMERO_PROCESSO';

        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT $campo FROM TB_PROCESSOS_CADASTRO WHERE $where = ? LIMIT 1");
        $stmt->bindParam(1, $processo, PDO::PARAM_STR);
        $stmt->execute();
        $resul = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resul[$campo];
    }

    /**
     * @return Output
     */
    public function listar() {

        $stmt = Controlador::getInstance()->getConnection()->connection->prepare('SELECT V.ID AS id, V.NU_VOLUME AS volume, V.FL_FINAL AS final, V.FL_INICIAL AS inicial, V.DT_ABERTURA AS abertura, V.DT_ENCERRAMENTO AS encerramento FROM TB_PROCESSOS_VOLUME AS V
             WHERE ID_PROCESSO_CADASTRO = ?
             AND ST_ATIVO = 1
             AND DT_ENCERRAMENTO IS NOT NULL');
        $stmt->bindParam(1, $this->getIdProcesso());
        $stmt->execute();
        $resul = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $out['success'] = 'false';

        foreach ($resul as $value) {
            $value['QUANT'] = $value['FINAL'] - $value['INICIAL'] + 1;
            $value['ABERTURA'] = Util::formatDate($value['ABERTURA']);
            $value['ENCERRAMENTO'] = Util::formatDate($value['ENCERRAMENTO']);
            $out['data'][] = array_change_key_case($value, CASE_LOWER);
        }

        if (!empty($out)) {
            $out['success'] = 'true';
        }

        $this->print = $out;
        return new Output($out);
    }

    /**
     * 
     */
    public function limparVolumes() {
        try {
            // inicia transação
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            // a funcionalidade deve adicionar um registro no log para cada volume que foi excluído, 
            // e um registro no log no momento da criação do novo volume, excluir os registros e criar o novo volume.
            // deletando todos os comentários
                       
            $sql = "DELETE FROM TB_PROCESSOS_VOLUME 
                    WHERE ID_PROCESSO_CADASTRO = ?";

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam(1, $this->idProcesso, PDO::PARAM_INT);

            $stmt->execute();

            // Inserindo registro no log informando que os volumes foram limpos
            new Log('TB_PROCESSOS_VOLUME', $this->idProcesso, Session::get('_usuario')->id, 'Limpou volumes');
            
            $sql = "DELETE FROM tb_comentarios_processos 
                        where texto_comentario ilike 'O volume%'
                          AND numero_processo = ?";

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam(1, $this->processo, PDO::PARAM_STR);

            $stmt->execute();

            new Log('TB_COMENTARIOS_PROCESSOS', $this->idProcesso, Session::get('_usuario')->id, 'Limpou comentários vinculados aos volumes');



            // Excluindo do histórico de trâmite de processos informações sobre abertura/encerramento de volumes
            $sql2 = "DELETE FROM TB_HISTORICO_TRAMITE_PROCESSOS WHERE ACAO iLIKE '%VOLUME%' AND NUMERO_PROCESSO = ?";

            $stmt2 = Controlador::getInstance()->getConnection()->connection->prepare($sql2);
            $stmt2->bindParam(1, $this->processo, PDO::PARAM_STR);

            $stmt2->execute();

            // Inserindo registro no log informando que os históricos de trâmite foram limpos
            new Log('TB_HISTORICO_TRAMITE_PROCESSOS', $this->idProcesso, Session::get('_usuario')->id, 'Limpou histórico de volumes no trâmite');

            // Inserir registro informando a abertura do volume inicial do processo
            $sql3 = "INSERT INTO TB_PROCESSOS_VOLUME 
                        (ID_PROCESSO_CADASTRO,NU_VOLUME,FL_INICIAL,DT_ABERTURA,ID_USUARIO,FG_ATIVO,ST_ATIVO,ID_UNIDADE)
                    SELECT
                        PC.ID,
                        1,
                        1,
                        PC.DT_AUTUACAO,
                        PC.USUARIO,
                        1,
                        1,
                        PC.ID_UNIDADE_USUARIO
                    FROM TB_PROCESSOS_CADASTRO PC
                    WHERE PC.ID = ? LIMIT 1";

            $stmt3 = Controlador::getInstance()->getConnection()->connection->prepare($sql3);
            $stmt3->bindParam(1, $this->idProcesso, PDO::PARAM_INT);

            $stmt3->execute();

            // Inserir registro de log informando a abertura do volume inicial
            new Log('TB_PROCESSOS_VOLUME', $this->idProcesso, Session::get('_usuario')->id, 'Abriu volume inicial');

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollBack();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * @return Output
     */
    public function comment() {

        $out = array();

        $unidade = DaoUnidade::getUnidade($this->post['setor'], 'nome');
        $usuario = DaoUsuario::getUsuario((int) $this->post['usuario']);
        $mensagem = "O volume {$this->post['volume']} deste processo está aos cuidados de {$usuario['nome']} - {$unidade}.";

        Controlador::getInstance()->getConnection()->connection->beginTransaction();

        $id_usuario = Zend_Auth::getInstance()->getIdentity()->ID;
        $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
        $id_unidade_historico = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
        $diretoria = DaoUnidade::getUnidade($id_unidade_historico, 'nome');

        try {

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                INSERT INTO TB_COMENTARIOS_PROCESSOS 
                    (NUMERO_PROCESSO, ID_USUARIO, USUARIO, DT_CADASTRO, TEXTO_COMENTARIO, ID_UNIDADE, DIRETORIA)
                VALUES
                    (?,?,?,CLOCK_TIMESTAMP(),?,?,?)
            ");
            $stmt->bindValue(1, $this->getProcesso());
            $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(3, $nome_usuario, PDO::PARAM_STR);
            $stmt->bindParam(4, $mensagem);
            $stmt->bindParam(5, $id_unidade_historico, PDO::PARAM_INT);
            $stmt->bindParam(6, $diretoria, PDO::PARAM_STR);
            $stmt->execute();
            $last_id = Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_COMENTARIOS_PROCESSOS_ID_SEQ');

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare('UPDATE TB_PROCESSOS_VOLUME SET ID_COMENTARIO = ? WHERE ID = ?');
            $stmt->bindParam(1, $last_id);
            $stmt->bindParam(2, $this->post['idvolume']);
            $stmt->execute();

            Controlador::getInstance()->getConnection()->connection->commit();

            $out = array('success' => 'true');

            $this->print = $out;
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            $out = array('success' => 'false', 'message' => 'Não foi possível registrar o comentário deste volume!');
        }

        return new Output($out);
    }

}