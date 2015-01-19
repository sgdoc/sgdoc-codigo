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
class Tramite extends Base {

    public $relacao;
    public $tramite;

    /**
     * 
     */
    public function __set($var, $value) {
        $this->tramite->$var = $value;
    }

    /**
     * 
     */
    public function __get($var) {
        return $this->tramite->$var;
    }

    /**
     * Setar lista de digitais na sessao para a confeccao da guia de recibo 
     */
    public static function setDigitaisGuiaRecibo($digital) {

        $old = Session::get('_digitais_recibo');

        if (is_array($old)) {
            Session::set('_digitais_recibo', array_merge($old, array($digital)));
        } else {
            Session::set('_digitais_recibo', array($digital));
        }
    }

    /**
     * Pegar lista de digitais salvas na sessao para a confeccao da guia de recibo
     */
    public static function getDigitaisGuiaRecibo() {
        return Session::get('_digitais_recibo');
    }

    /**
     * Setar lista de procesos na sessao para a confeccao da guia de recibo 
     */
    public static function setProcessosGuiaRecibo($digital) {

        $old = Session::get('_processos_recibo');
        if (is_array($old)) {
            Session::set('_processos_recibo', array_merge($old, array($digital)));
        } else {
            Session::set('_processos_recibo', array($digital));
        }
    }

    /**
     *  Pegar lista de processos salvos na sessao para a confeccao da guia de recibo 
     */
    public static function getProcessosGuiaRecibo() {
        return Session::get('_processos_recibo');
    }

    /**
     * 
     */
    public function tramitarDocumento($digital, $destino, $tipo = false, $local = false, $endereco = false, $cep = false, $prioridade = false, $telefone = false) {

        /**
         * Validar informacoes necessarios
         */
        if (!$digital || !$destino || !$tipo || $destino == 'null') {
            throw new Exception('Informações importantes estão ausentes no ato do trâmite!');
        }

        /**
         * Objeto Vinculacao
         */
        $vinculacao = new Vinculacao();

        /**
         * Setar variaveis globais de tramite 
         */
        $this->tramite->nm_unidade = DaoUnidade::getUnidade(Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE, 'nome');
        $this->tramite->nm_unidade_original = DaoUnidade::getUnidade(Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL, 'nome');
        $this->tramite->dt_hoje = date("d/m/Y - H:i:s");

        /**
         *  Setar variaveis especificas de tramite de acordo com o tipo do tramite 
         */
        switch ($tipo) {
            case 'I':
                $objDestino = DaoUnidade::getUnidade($destino);
                $this->tramite->id_destino = $objDestino['id'];
                $this->tramite->nm_destino = $objDestino['nome'] . ' - ' . $objDestino['sigla'];
                $this->tramite->caixa_entrada = $this->tramite->id_destino;
                $this->tramite->caixa_saida = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
                $this->tramite->ultimo_tramite = "Encaminhado por " . $this->_usuario->nome . " - " . $this->tramite->nm_unidade . " para " . $objDestino['nome'] . " em " . $this->tramite->dt_hoje;

                /**
                 * Verificar se variaveis do tramite nao estao vazias 
                 */
                if (!$this->iisset($this->tramite)) {
                    throw new Exception('Informações importantes estão ausentes no ato do tramite!');
                } else {
                    /**
                     * Setar todos os documento relacionados com os documentos primarios informados 
                     */
                    $this->relacao = $vinculacao->setRelacaoDocumentosTramitar($digital);
                }
                /**
                 * Consolidar o tramite para o destino escolhido para todos os documentos relacionados
                 */
                $out = $this->consolidarTramiteInternoDocumento();
                break;
            case 'E':
                $this->tramite->destinatario = $destino;
                $this->tramite->local = $local;
                $this->tramite->endereco = $endereco ? $endereco : 'Em Branco';
                $this->tramite->cep = $cep ? $cep : 'Em Branco';
                $this->tramite->prioridade = $prioridade;
                $this->tramite->telefone = $telefone ? $telefone : 'Em Branco';
                $this->tramite->nm_destino = "{$destino} - {$local}";
                $this->tramite->ultimo_tramite = "Encaminhado por {$this->_usuario->nome} - {$this->tramite->nm_unidade} para {$destino} - {$local} em {$this->tramite->dt_hoje}";
                /**
                 * Verificar se variaveis do tramite nao estao vazias
                 */
                if (!$this->iisset($this->tramite)) {
                    throw new Exception('Informações importantes estão ausentes no ato do trâmite!');
                } else {
                    /**
                     * Setar todos os documento relacionados com os documentos primarios informados
                     */
                    $this->relacao = $vinculacao->setRelacaoDocumentosTramitar($digital);
                }
                /**
                 * Consolidar o tramite para o destino escolhido para todos os documentos relacionados
                 */
                $out = $this->consolidarTramiteExternoDocumento();
                break;
        }

        return $out;
    }

    /**
     * 
     */
    public function tramitarProcesso($numero_processo, $destino, $tipo = false, $local = false, $endereco = false, $cep = false, $prioridade = false, $telefone = false) {
        /**
         * Validar informacoes necessarios
         */
        if (!$numero_processo || !$destino || !$tipo) {
            throw new Exception('Informações importantes estão ausentes no ato do trâmite!');
        }

        /**
         * Objeto Vinculacao
         */
        $vinculacao = new Vinculacao();

        /**
         * Setar variaveis globais de tramite
         */
        $this->tramite->nm_unidade = DaoUnidade::getUnidade(Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE, 'nome');
        $this->tramite->nm_unidade_original = DaoUnidade::getUnidade(Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL, 'nome');
        $this->tramite->dt_hoje = date("d/m/Y - H:i:s");

        /**
         * Setar variaveis especificas de tramite de acordo com o tipo do tramite
         */
        switch ($tipo) {
            case 'I':
                $objDestino = DaoUnidade::getUnidade($destino);
                $this->tramite->id_destino = $objDestino['id'];
                $this->tramite->nm_destino = $objDestino['nome'] . ' - ' . $objDestino['sigla'];
                $this->tramite->caixa_entrada = $this->tramite->id_destino;
                $this->tramite->caixa_saida = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
                $this->tramite->ultimo_tramite = "Encaminhado por {$this->_usuario->nome} - " . $this->tramite->nm_unidade . " para {$objDestino['nome']} em {$this->tramite->dt_hoje}";

                /**
                 * Verificar se variaveis do tramite nao estao vazias
                 */
                if (!$this->iisset($this->tramite)) {
                    throw new Exception('Informações importantes estão ausentes no ato do trâmite!');
                } else {
                    /**
                     * Setar todos os documento relacionados com os documentos primarios informados
                     */
                    $this->relacao = $vinculacao->setRelacaoProcessosTramitar($numero_processo);
                }
                /**
                 * Consolidar o tramite para o destino escolhido para todos os documentos relacionados
                 */
                $out = $this->consolidarTramiteInternoProcesso();
                break;
            case 'E':
                $this->tramite->destinatario = $destino;
                $this->tramite->local = $local;
                $this->tramite->endereco = $endereco ? $endereco : 'Em Branco';
                $this->tramite->cep = $cep ? $cep : 'Em Branco';
                $this->tramite->prioridade = $prioridade;
                $this->tramite->telefone = $telefone ? $telefone : 'Em Branco';
                $this->tramite->nm_destino = "{$destino} - {$local}";
                $this->tramite->ultimo_tramite = "Encaminhado por {$this->_usuario->nome} - {$this->tramite->nm_unidade} para {$destino} - {$local} em {$this->tramite->dt_hoje}";
                /**
                 * Verificar se variaveis do tramite nao estao vazias
                 */
                if (!$this->iisset($this->tramite)) {
                    throw new Exception('Informações importantes estão ausentes no ato do trâmite!');
                } else {
                    /**
                     * Setar todos os documento relacionados com os documentos primarios informados
                     */
                    $this->relacao = $vinculacao->setRelacaoProcessosTramitar($numero_processo);
                }
                /**
                 * Consolidar o tramite para o destino escolhido para todos os documentos relacionados
                 */
                $out = $this->consolidarTramiteExternoProcesso();
                break;
        }

        return $out;
    }

    /**
     * 
     */
    public function receberDocumento($digital) {
        try {
            /**
             * Objeto Vinculacao
             */
            $vinculacao = new Vinculacao();

            /**
             * Setar variaveis do recebimento
             */
            $this->tramite->nm_unidade = DaoUnidade::getUnidade(Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE, 'nome');
            $this->tramite->dt_hoje = date("d/m/Y - H:i:s");
            $this->tramite->ultimo_tramite = "Recebido por {$this->_usuario->nome} - {$this->tramite->nm_unidade} em {$this->tramite->dt_hoje}";
            $this->tramite->acao = "Recebimento";
            $this->tramite->destino = "XXXXX";
            $this->tramite->origem = "XXXXX";

            $this->relacao = $vinculacao->setRelacaoDocumentosTramitar($digital);

            Controlador::getInstance()->getConnection()->connection->beginTransaction();
            /**
             * BugFix Notice
             */
            $id_usuario = $this->_usuario->id;
            $id_unidade = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
            $id_unidade_original = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
            $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
            $diretoria = DaoUnidade::getUnidade($id_unidade_original, 'nome');

            /**
             * Tramitar Documentos
             */
            foreach ($this->relacao as $key => $digital) {

                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_CADASTRO SET ID_UNID_AREA_TRABALHO = ? , ID_UNID_CAIXA_ENTRADA = NULL, ID_UNID_CAIXA_SAIDA = NULL, EXTERNO = NULL, ULTIMO_TRAMITE = ? WHERE DIGITAL = ?");
                $stmt->bindParam(1, $id_unidade, PDO::PARAM_INT);
                $stmt->bindParam(2, $this->tramite->ultimo_tramite, PDO::PARAM_STR);
                $stmt->bindParam(3, $digital, PDO::PARAM_STR);
                $stmt->execute();

                /**
                 * Inserir o historico de tramite do documento
                 */
                $historico = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS"
                        . " (DIGITAL,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                        . " VALUES (?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
                $historico->bindParam(1, $digital, PDO::PARAM_STR); //digital
                $historico->bindParam(2, $id_usuario, PDO::PARAM_INT); //id_usuario
                $historico->bindParam(3, $nome_usuario, PDO::PARAM_STR); //nm_usuario
                $historico->bindParam(4, $id_unidade_original, PDO::PARAM_INT); //id_unidade
                $historico->bindParam(5, $diretoria, PDO::PARAM_STR); //nm_unidade
                $historico->bindParam(6, $this->tramite->acao, PDO::PARAM_STR); //acao
                $historico->bindParam(7, $this->tramite->origem, PDO::PARAM_STR); //origem
                $historico->bindParam(8, $this->tramite->destino, PDO::PARAM_STR); //destino
                $historico->execute();
            }

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => "Documento(s) recebido(s) com sucesso!"));
        } catch (BasePDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }

        return $out;
    }

    /**
     * 
     */
    public function receberProcesso($numero_processo) {
        try {
            /**
             * Objeto Vinculacao
             */
            $vinculacao = new Vinculacao();

            /**
             * Setar variaveis do recebimento
             */
            $this->tramite->nm_unidade = DaoUnidade::getUnidade(Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE, 'nome');
            $this->tramite->dt_hoje = date("d/m/Y - H:i:s");
            $this->tramite->ultimo_tramite = "Recebido por {$this->_usuario->nome} - " . $this->tramite->nm_unidade . " em {$this->tramite->dt_hoje}";
            $this->tramite->acao = "Recebimento";
            $this->tramite->destino = "XXXXX";
            $this->tramite->origem = "XXXXX";

            $this->relacao = $vinculacao->setRelacaoProcessosTramitar($numero_processo);

            Controlador::getInstance()->getConnection()->connection->beginTransaction();
            /**
             * BugFix Notice
             */
            $id_usuario = $this->_usuario->id;
            $id_unidade = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
            $id_unidade_original = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
            $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
            $nome_unidade = DaoUnidade::getUnidade($id_unidade_original, 'nome');

            /**
             * Tramitar Processos
             */
            foreach ($this->relacao as $key => $numero_processo) {

                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_PROCESSOS_CADASTRO SET ID_UNID_AREA_TRABALHO = ?, ID_UNID_CAIXA_ENTRADA = NULL, ID_UNID_CAIXA_SAIDA = NULL, EXTERNO = NULL, ULTIMO_TRAMITE = ? WHERE NUMERO_PROCESSO = ?");
                $stmt->bindParam(1, $id_unidade, PDO::PARAM_INT);
                $stmt->bindParam(2, $this->tramite->ultimo_tramite, PDO::PARAM_STR);
                $stmt->bindParam(3, $numero_processo, PDO::PARAM_STR);
                $stmt->execute();

                /**
                 * Inserir o historico de tramite do processo
                 */
                $historico = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_PROCESSOS"
                        . " (NUMERO_PROCESSO,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                        . " VALUES (?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
                $historico->bindParam(1, $numero_processo, PDO::PARAM_STR);
                $historico->bindParam(2, $id_usuario, PDO::PARAM_INT);
                $historico->bindParam(3, $nome_usuario, PDO::PARAM_STR);
                $historico->bindParam(4, $id_unidade_original, PDO::PARAM_INT);
                $historico->bindParam(5, $nome_unidade, PDO::PARAM_STR);
                $historico->bindParam(6, $this->tramite->acao, PDO::PARAM_STR);
                $historico->bindParam(7, $this->tramite->origem, PDO::PARAM_STR);
                $historico->bindParam(8, $this->tramite->destino, PDO::PARAM_STR);
                $historico->execute();
            }

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => "Processos(s) recebido(s) com sucesso!"));
        } catch (BasePDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }

        return $out;
    }

    /**
     * 
     */
    public function cancelarTramiteDocumento($digital) {
        try {
            /**
             * Objeto Vinculacao
             */
            $vinculacao = new Vinculacao();

            /**
             * Setar variaveis do recebimento
             */
            $this->tramite->nm_unidade = DaoUnidade::getUnidade(Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE, 'nome');
            $this->tramite->dt_hoje = date("d/m/Y - H:i:s"); //dt_hoje
            $this->tramite->ultimo_tramite = "Trâmite cancelado por {$this->_usuario->nome} - {$this->tramite->nm_unidade} em {$this->tramite->dt_hoje}";
            $this->tramite->acao = "Trâmite cancelado";
            $this->tramite->destino = "XXXXX";
            $this->tramite->origem = "XXXXX";

            $this->relacao = $vinculacao->setRelacaoDocumentosTramitar($digital);

            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            /**
             * BugFix Notice 
             */
            $id_usuario = $this->_usuario->id;
            $id_unidade = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
            $id_unidade_original = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
            $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
            $diretoria = DaoUnidade::getUnidade($id_unidade_original, 'nome');

            /**
             * Tramitar Documentos
             */
            foreach ($this->relacao as $key => $digital) {

                /* Verificar a necessidade de validar a caixa do documento */
                /**
                 * TODO
                 * if (Documento::validarDocumentoAreaDeTrabalho($digital)) {  }
                 */
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_CADASTRO SET ID_UNID_AREA_TRABALHO = ? , ID_UNID_CAIXA_ENTRADA = NULL, ID_UNID_CAIXA_SAIDA = NULL, EXTERNO = NULL, ULTIMO_TRAMITE = ? WHERE DIGITAL = ?");
                $stmt->bindParam(1, $id_unidade, PDO::PARAM_INT); //area_trabalho
                $stmt->bindParam(2, $this->tramite->ultimo_tramite, PDO::PARAM_STR); //ultimo_tramite
                $stmt->bindParam(3, $digital, PDO::PARAM_STR); //digital
                $stmt->execute();

                /**
                 * Inserir o historico de tramite do documento
                 */
                $historico = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS"
                        . " (DIGITAL,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                        . " VALUES (?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
                $historico->bindParam(1, $digital, PDO::PARAM_STR); //digital
                $historico->bindParam(2, $id_usuario, PDO::PARAM_INT); //id_usuario
                $historico->bindParam(3, $nome_usuario, PDO::PARAM_STR); //nm_usuario
                $historico->bindParam(4, $id_unidade_original, PDO::PARAM_INT); //id_unidade
                $historico->bindParam(5, $diretoria, PDO::PARAM_STR); //nm_unidade
                $historico->bindParam(6, $this->tramite->acao, PDO::PARAM_STR); //acao
                $historico->bindParam(7, $this->tramite->origem, PDO::PARAM_STR); //origem
                $historico->bindParam(8, $this->tramite->destino, PDO::PARAM_STR); //destino
                $historico->execute();
            }

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => "Trâmite(s) cancelado(s) com sucesso!"));
        } catch (BasePDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }

        return $out;
    }

    /**
     * 
     */
    public function cancelarTramiteProcesso($numero_processo) {
        try {
            /**
             * Objeto Vinculacao
             */
            $vinculacao = new Vinculacao();

            /**
             * Setar variaveis do recebimento
             */
            $this->tramite->dt_hoje = date("d/m/Y - H:i:s");
            $this->tramite->ultimo_tramite = "Trâmite cancelado por {$this->_usuario->nome} - " . DaoUnidade::getUnidade(null, 'nome') . " em {$this->tramite->dt_hoje}";
            $this->tramite->acao = "Trâmite cancelado";
            $this->tramite->destino = "XXXXX";
            $this->tramite->origem = "XXXXX";

            $this->relacao = $vinculacao->setRelacaoProcessosTramitar($numero_processo);

            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            /**
             * BugFix Notice
             */
            $id_usuario = $this->_usuario->id;
            $id_unidade = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
            $id_unidade_original = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
            $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
            $diretoria = DaoUnidade::getUnidade($id_unidade_original, 'nome');
            $ultimo_tramite = $this->tramite->ultimo_tramite;

            /**
             * Tramitar Processos
             */
            foreach ($this->relacao as $key => $numero_processo) {

                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_PROCESSOS_CADASTRO SET ID_UNID_AREA_TRABALHO = ?, ID_UNID_CAIXA_ENTRADA = NULL, ID_UNID_CAIXA_SAIDA = NULL, EXTERNO = NULL, ULTIMO_TRAMITE = ? WHERE NUMERO_PROCESSO = ?");
                $stmt->bindParam(1, $id_unidade, PDO::PARAM_INT);
                $stmt->bindParam(2, $ultimo_tramite, PDO::PARAM_STR);
                $stmt->bindParam(3, $numero_processo, PDO::PARAM_STR);
                $stmt->execute();

                /**
                 * Inserir o historico de tramite do processo
                 */
                $historico = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_PROCESSOS"
                        . " (NUMERO_PROCESSO,ID_USUARIO,ACAO,ORIGEM,DESTINO,DT_TRAMITE,USUARIO,DIRETORIA,ID_UNIDADE)"
                        . " VALUES (?,?,?,?,?,CLOCK_TIMESTAMP(),?,?,?)");
                $historico->bindParam(1, $numero_processo, PDO::PARAM_STR);
                $historico->bindParam(2, $id_usuario, PDO::PARAM_INT);
                $historico->bindParam(3, $this->tramite->acao, PDO::PARAM_STR);
                $historico->bindParam(4, $this->tramite->origem, PDO::PARAM_STR);
                $historico->bindParam(5, $this->tramite->destino, PDO::PARAM_STR);
                $historico->bindParam(6, $nome_usuario, PDO::PARAM_STR);
                $historico->bindParam(7, $diretoria, PDO::PARAM_STR);
                $historico->bindParam(8, $id_unidade_original, PDO::PARAM_INT);
                $historico->execute();
            }

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => "Trâmite(s) cancelado(s) com sucesso!"));
        } catch (BasePDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }

        //return $out;
    }

    /**
     * 
     */
    public function resgatarDocumento($digital) {
        try {
            /**
             * Objeto Vinculacao
             */
            $vinculacao = new Vinculacao();

            /**
             * Setar variaveis do recebimento
             */
            $this->tramite->nm_unidade = DaoUnidade::getUnidade(Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE, 'nome');
            $this->tramite->dt_hoje = date("d/m/Y - H:i:s"); //dt_hoje
            $this->tramite->ultimo_tramite = "Documento resgatado da caixa de externos por {$this->_usuario->nome} - " . DaoUnidade::getUnidade(null, 'nome') . " em {$this->tramite->dt_hoje}";
            $this->tramite->acao = "Documento resgatado";
            $this->tramite->destino = "XXXXX";
            $this->tramite->origem = "XXXXX";

            $this->relacao = $vinculacao->setRelacaoDocumentosTramitar($digital);

            Controlador::getInstance()->getConnection()->connection->beginTransaction();
            /**
             * BugFix Notice
             */
            $id_usuario = $this->_usuario->id;
            $id_unidade = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
            $id_unidade_original = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
            $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
            $diretoria = DaoUnidade::getUnidade($id_unidade_original, 'nome');

            /**
             * Tramitar Documentos
             */
            foreach ($this->relacao as $key => $digital) {

                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_CADASTRO SET ID_UNID_AREA_TRABALHO = ?, ID_UNID_CAIXA_ENTRADA = NULL, ID_UNID_CAIXA_SAIDA = NULL, EXTERNO = NULL, ULTIMO_TRAMITE = ? WHERE DIGITAL = ?");
                $stmt->bindParam(1, $id_unidade, PDO::PARAM_INT); //area_trabalho
                $stmt->bindParam(2, $this->tramite->ultimo_tramite, PDO::PARAM_STR); //ultimo_tramite
                $stmt->bindParam(3, $digital, PDO::PARAM_STR); //digital
                $stmt->execute();

                /**
                 * Inserir o historico de tramite do documento
                 */
                $historico = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS"
                        . " (DIGITAL,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                        . " VALUES (?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
                $historico->bindParam(1, $digital, PDO::PARAM_STR); //digital
                $historico->bindParam(2, $id_usuario, PDO::PARAM_INT); //id_usuario
                $historico->bindParam(3, $nome_usuario, PDO::PARAM_STR); //nm_usuario
                $historico->bindParam(4, $id_unidade_original, PDO::PARAM_INT); //id_unidade
                $historico->bindParam(5, $diretoria, PDO::PARAM_STR); //nm_unidade
                $historico->bindParam(6, $this->tramite->acao, PDO::PARAM_STR); //acao
                $historico->bindParam(7, $this->tramite->origem, PDO::PARAM_STR); //origem
                $historico->bindParam(8, $this->tramite->destino, PDO::PARAM_STR); //destino
                $historico->execute();
            }

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => "Documento(s) regatado(s) com sucesso!"));
        } catch (BasePDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }

        return $out;
    }

    /**
     * 
     */
    public function resgatarProcesso($numero_processo) {
        try {
            /**
             * Objeto Vinculacao
             */
            $vinculacao = new Vinculacao();

            /**
             * Setar variaveis do recebimento
             */
            $this->tramite->nm_unidade = DaoUnidade::getUnidade(Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE, 'nome');
            $this->tramite->dt_hoje = date("d/m/Y - H:i:s");
            $this->tramite->ultimo_tramite = "Processo resgatado da caixa de externos por " . $this->_usuario->nome . " - " . DaoUnidade::getUnidade(null, 'nome') . " em " . $this->tramite->dt_hoje;
            $this->tramite->acao = "Processo resgatado";
            $this->tramite->destino = "XXXXX";
            $this->tramite->origem = "XXXXX";

            $this->relacao = $vinculacao->setRelacaoProcessosTramitar($numero_processo);

            Controlador::getInstance()->getConnection()->connection->beginTransaction();
            /**
             * BugFix Notice
             */
            $id_usuario = $this->_usuario->id;
            $id_unidade = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
            $id_unidade_original = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
            $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
            $nome_unidade = DaoUnidade::getUnidade($id_unidade_original, 'nome');


            /**
             * Tramitar Processos
             */
            foreach ($this->relacao as $key => $numero_processo) {


                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_PROCESSOS_CADASTRO SET ID_UNID_AREA_TRABALHO = ? , ID_UNID_CAIXA_ENTRADA = NULL, ID_UNID_CAIXA_SAIDA = NULL, EXTERNO = NULL, ULTIMO_TRAMITE = ? WHERE NUMERO_PROCESSO = ?");
                $stmt->bindParam(1, $id_unidade, PDO::PARAM_INT); //area_trabalho
                $stmt->bindParam(2, $this->tramite->ultimo_tramite, PDO::PARAM_STR); //ultimo_tramite
                $stmt->bindParam(3, $numero_processo, PDO::PARAM_STR); //numero_processo
                $stmt->execute();

                /**
                 * Inserir o historico de tramite do processo
                 */
                $historico = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_PROCESSOS"
                        . " (NUMERO_PROCESSO,USUARIO,ACAO,ORIGEM,DESTINO,DT_TRAMITE,ID_USUARIO,ID_UNIDADE,DIRETORIA)"
                        . " VALUES (?,?,?,?,?,CLOCK_TIMESTAMP(),?,?,?)");
                $historico->bindParam(1, $numero_processo, PDO::PARAM_STR); //numero_processo
                $historico->bindParam(2, $nome_usuario, PDO::PARAM_STR); //nm_usuario
                $historico->bindParam(3, $this->tramite->acao, PDO::PARAM_STR); //acao
                $historico->bindParam(4, $this->tramite->origem, PDO::PARAM_STR); //origem
                $historico->bindParam(5, $this->tramite->destino, PDO::PARAM_STR); //destino
                $historico->bindParam(6, $id_usuario, PDO::PARAM_INT); //data
                $historico->bindParam(7, $id_unidade_original, PDO::PARAM_INT); //data
                $historico->bindParam(8, $nome_unidade, PDO::PARAM_STR); //data
                $historico->execute();
            }

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => "Processo(s) regatado(s) com sucesso!"));
        } catch (BasePDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }

        return $out;
    }

    /**
     * Consolidar tramite interno
     */
    public function consolidarTramiteInternoDocumento() {
        try {

            Controlador::getInstance()->getConnection()->connection->beginTransaction();
            /**
             * Tramitar Documentos
             */
            foreach ($this->relacao as $key => $digital) {

                if (Documento::validarDocumentoAreaDeTrabalho($digital)) {

                    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_CADASTRO SET ID_UNID_AREA_TRABALHO = NULL, ID_UNID_CAIXA_ENTRADA = ?, ID_UNID_CAIXA_SAIDA = ?, EXTERNO = NULL, ULTIMO_TRAMITE = ? WHERE DIGITAL = ?");

                    $stmt->bindParam(1, $this->tramite->caixa_entrada, PDO::PARAM_INT); //caixa_entrada
                    $stmt->bindParam(2, $this->tramite->caixa_saida, PDO::PARAM_INT); //caixa_saida
                    $stmt->bindParam(3, $this->tramite->ultimo_tramite, PDO::PARAM_STR); //ultimo_tramite
                    $stmt->bindParam(4, $digital, PDO::PARAM_STR); //digital
                    $res = $stmt->execute();

                    /**
                     * Inserir o historico de tramite do documento
                     * Complemento do Historico do documento
                     */
                    $acao = "Encaminhado";

                    /**
                     * BugFix Notice
                     */
                    $id_usuario = $this->_usuario->id;
                    $id_unidade = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
                    $id_unidade_original = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
                    $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
                    $nome_unidade_original = DaoUnidade::getUnidade($id_unidade_original, 'nome');
                    $objOrigem = DaoUnidade::getUnidade($id_unidade);
                    $tx_origem = $objOrigem['nome'] . ' - ' . $objOrigem['sigla'];


                    $historico = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS"
                            . " (DIGITAL,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                            . " VALUES (?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
                    $historico->bindParam(1, $digital, PDO::PARAM_STR); //digital
                    $historico->bindParam(2, $id_usuario, PDO::PARAM_INT); //id_usuario
                    $historico->bindParam(3, $nome_usuario, PDO::PARAM_STR); //nm_usuario
                    $historico->bindParam(4, $id_unidade_original, PDO::PARAM_INT); //id_unidade
                    $historico->bindParam(5, $nome_unidade_original, PDO::PARAM_STR); //nm_unidade
                    $historico->bindParam(6, $acao, PDO::PARAM_STR); //acao
                    $historico->bindParam(7, $tx_origem, PDO::PARAM_STR); //origem
                    $historico->bindParam(8, $this->tramite->nm_destino, PDO::PARAM_STR); //destino
                    $historico->execute();
                }
            }

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => "Documento(s) tramitado(s) com sucesso!"));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            new BasePDOException($e);
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * 
     */
    public function removerTramite($digital) {
        try {

            Controlador::getInstance()->getConnection()->connection->beginTransaction();
            $historico = Controlador::getInstance()->getConnection()->connection->prepare("DELETE FROM TB_HISTORICO_TRAMITE_DOCUMENTOS WHERE DIGITAL = ?");
            $historico->bindParam(1, $digital, PDO::PARAM_STR); //digital
            $historico->execute();

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'error' => "Trâmite cancelado com sucesso"));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            new BasePDOException($e);
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * Tramitar documento para destino externo
     */
    public function consolidarTramiteExternoDocumento() {
        try {
            /**
             * Remover informacoes de tramites anteriores
             */
            Session::destroy('_digitais_recibo');
            Session::destroy('_digitais_recibo_destinatario');
            Session::destroy('_digitais_recibo_local');
            Session::destroy('_digitais_recibo_endereco');
            Session::destroy('_digitais_recibo_telefone');
            Session::destroy('_digitais_recibo_cep');
            Session::destroy('_digitais_recibo_prioridade');

            Controlador::getInstance()->getConnection()->connection->beginTransaction();
            /**
             * BugFix Notice
             */
            $id_usuario = $this->_usuario->id;
            $id_unidade = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
            $id_unidade_original = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
            $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
            $nome_unidade_original = DaoUnidade::getUnidade($id_unidade_original, 'nome');
            $oDiretoria = DaoUnidade::getUnidade($id_unidade);
            $tx_diretoria = $oDiretoria['nome'] . ' - ' . $oDiretoria['sigla'];

            /**
             * Tramitar Documentos
             */
            foreach ($this->relacao as $key => $digital) {

                if (Documento::validarDocumentoAreaDeTrabalho($digital)) {

                    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_CADASTRO SET ID_UNID_AREA_TRABALHO = NULL, ID_UNID_CAIXA_ENTRADA = NULL, ID_UNID_CAIXA_SAIDA = NULL, EXTERNO = ?, ULTIMO_TRAMITE = ? WHERE DIGITAL = ?");
                    $stmt->bindParam(1, $this->tramite->nm_destino, PDO::PARAM_STR); //destino
                    $stmt->bindParam(2, $this->tramite->ultimo_tramite, PDO::PARAM_STR); //ultimo_tramite
                    $stmt->bindParam(3, $digital, PDO::PARAM_STR); //digital
                    $stmt->execute();

                    /** Inserir o historico de tramite do documento
                     * Complemento do Historico do documento
                     */
                    $acao = "Encaminhado";

                    $historico = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS"
                            . " (DIGITAL,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                            . " VALUES (?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
                    $historico->bindParam(1, $digital, PDO::PARAM_STR); //digital
                    $historico->bindParam(2, $id_usuario, PDO::PARAM_INT); //id_usuario
                    $historico->bindParam(3, $nome_usuario, PDO::PARAM_STR); //nm_usuario
                    $historico->bindParam(4, $id_unidade_original, PDO::PARAM_INT); //id_unidade
                    $historico->bindParam(5, $nome_unidade_original, PDO::PARAM_STR); //nm_unidade
                    $historico->bindParam(6, $acao, PDO::PARAM_STR); //acao
                    $historico->bindParam(7, $tx_diretoria, PDO::PARAM_STR); //origem
                    $historico->bindParam(8, $this->tramite->nm_destino, PDO::PARAM_STR); //destino
                    $historico->execute();

                    Tramite::setDigitaisGuiaRecibo($digital); //Adicionar digital para confeccionar a guia de recibo!
                }
            }

            /**
             * Setar novas informacoes do tramite 
             */
            Session::set('_digitais_recibo_destinatario', $this->tramite->destinatario);
            Session::set('_digitais_recibo_local', $this->tramite->local);
            Session::set('_digitais_recibo_endereco', $this->tramite->endereco);
            Session::set('_digitais_recibo_telefone', $this->tramite->telefone);
            Session::set('_digitais_recibo_cep', $this->tramite->cep);
            Session::set('_digitais_recibo_prioridade', $this->tramite->prioridade);

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => "Documento(s) tramitados com sucesso!", 'ticket' => 'true'));
        } catch (PDOException $e) {
            new BasePDOException($e);
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * Consolidar tramite interno processo
     */
    public function consolidarTramiteInternoProcesso() {
        try {

            Controlador::getInstance()->getConnection()->connection->beginTransaction();
            /**
             * Tramitar Processos
             */
            foreach ($this->relacao as $key => $processo) {

                if (Processo::validarProcessoAreaDeTrabalho($processo)) {

                    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_PROCESSOS_CADASTRO SET ID_UNID_AREA_TRABALHO = NULL, ID_UNID_CAIXA_ENTRADA = ?, ID_UNID_CAIXA_SAIDA = ?, EXTERNO = NULL, ULTIMO_TRAMITE = ? WHERE NUMERO_PROCESSO = ?");
                    $stmt->bindParam(1, $this->tramite->caixa_entrada, PDO::PARAM_INT);
                    $stmt->bindParam(2, $this->tramite->caixa_saida, PDO::PARAM_INT);
                    $stmt->bindParam(3, $this->tramite->ultimo_tramite, PDO::PARAM_STR);
                    $stmt->bindParam(4, $processo, PDO::PARAM_STR);
                    $stmt->execute();

                    /**
                     * Inserir o historico de tramite do documento
                     * Complemento do Historico do documento
                     */
                    $acao = "Encaminhado";

                    /**
                     * BugFix Notice
                     */
                    $id_usuario = $this->_usuario->id;
                    $id_unidade = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
                    $id_unidade_original = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
                    $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
                    $nome_unidade_original = DaoUnidade::getUnidade($id_unidade_original, 'nome');
                    $objOrigem = DaoUnidade::getUnidade($id_unidade);
                    $tx_origem = $objOrigem['nome'] . ' - ' . $objOrigem['sigla'];

                    $historico = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_PROCESSOS"
                            . " (NUMERO_PROCESSO, ID_USUARIO, ACAO, ORIGEM, DESTINO, DT_TRAMITE, ID_UNIDADE, USUARIO, DIRETORIA)"
                            . " VALUES (?,?,?,?,?,CLOCK_TIMESTAMP(),?,?,?)");
                    $historico->bindParam(1, $processo, PDO::PARAM_STR);
                    $historico->bindParam(2, $id_usuario, PDO::PARAM_INT);
                    $historico->bindParam(3, $acao, PDO::PARAM_STR);
                    $historico->bindParam(4, $tx_origem, PDO::PARAM_STR);
                    $historico->bindParam(5, $this->tramite->nm_destino, PDO::PARAM_STR);
                    $historico->bindParam(6, $id_unidade_original, PDO::PARAM_INT);
                    $historico->bindParam(7, $nome_usuario, PDO::PARAM_STR);
                    $historico->bindParam(8, $nome_unidade_original, PDO::PARAM_STR);
                    $historico->execute();
                }
            }

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => "Processo(s) tramitado(s) com sucesso!"));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            new BasePDOException($e);
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * Tramitar processo para destino externo
     */
    public function consolidarTramiteExternoProcesso() {
        try {
            /**
             * Remover informacoes de tramites anteriores
             */
            Session::destroy('_processos_recibo');
            Session::destroy('_processos_recibo_destinatario');
            Session::destroy('_processos_recibo_local');
            Session::destroy('_processos_recibo_endereco');
            Session::destroy('_processos_recibo_telefone');
            Session::destroy('_processos_recibo_cep');
            Session::destroy('_processos_recibo_prioridade');

            Controlador::getInstance()->getConnection()->connection->beginTransaction();
            /**
             * BugFix Notice
             */
            $id_usuario = $this->_usuario->id;
            $id_unidade = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
            $id_unidade_original = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
            $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
            $nome_unidade_original = DaoUnidade::getUnidade($id_unidade_original, 'nome');
            $oDiretoria = DaoUnidade::getUnidade($id_unidade);
            $tx_diretoria = $oDiretoria['nome'] . ' - ' . $oDiretoria['sigla'];

            /**
             * Tramitar Documentos 
             */
            foreach ($this->relacao as $key => $processo) {

                if (Processo::validarProcessoAreaDeTrabalho($processo)) {

                    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_PROCESSOS_CADASTRO SET ID_UNID_AREA_TRABALHO = NULL, ID_UNID_CAIXA_ENTRADA = NULL, ID_UNID_CAIXA_SAIDA = NULL, EXTERNO = ?, ULTIMO_TRAMITE = ? WHERE NUMERO_PROCESSO = ?");
                    $stmt->bindParam(1, $this->tramite->nm_destino, PDO::PARAM_STR);
                    $stmt->bindParam(2, $this->tramite->ultimo_tramite, PDO::PARAM_STR);
                    $stmt->bindParam(3, $processo, PDO::PARAM_STR);
                    $stmt->execute();

                    /**
                     * Inserir o historico de tramite do documento
                     * Complemento do Historico do documento
                     */
                    $acao = "Encaminhado";

                    $historico = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_PROCESSOS"
                            . " (NUMERO_PROCESSO,USUARIO,ACAO,ORIGEM,DESTINO,DT_TRAMITE,DIRETORIA,ID_UNIDADE,ID_USUARIO)"
                            . " VALUES (?,?,?,?,?,CLOCK_TIMESTAMP(),?,?,?)");
                    $historico->bindParam(1, $processo, PDO::PARAM_STR); //numero processo
                    $historico->bindParam(2, $nome_usuario, PDO::PARAM_STR); //nm_usuario
                    $historico->bindParam(3, $acao, PDO::PARAM_STR); //acao
                    $historico->bindParam(4, $tx_diretoria, PDO::PARAM_STR); //origem
                    $historico->bindParam(5, $this->tramite->nm_destino, PDO::PARAM_STR); //destino
                    $historico->bindParam(6, $nome_unidade_original, PDO::PARAM_STR); //nome unidade
                    $historico->bindParam(7, $id_unidade_original, PDO::PARAM_INT); //id unidade
                    $historico->bindParam(8, $id_usuario, PDO::PARAM_INT); //id usuario
                    $historico->execute();

                    Tramite::setProcessosGuiaRecibo($processo); //Adicionar numero processo para confeccionar a guia de recibo!
                }
            }

            /**
             * Setar novas informacoes do tramite
             */
            Session::set('_processos_recibo_destinatario', $this->tramite->destinatario);
            Session::set('_processos_recibo_local', $this->tramite->local);
            Session::set('_processos_recibo_endereco', $this->tramite->endereco);
            Session::set('_processos_recibo_telefone', $this->tramite->telefone);
            Session::set('_processos_recibo_cep', $this->tramite->cep);
            Session::set('_processos_recibo_prioridade', $this->tramite->prioridade);

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => "Processo(s) tramitados com sucesso!", 'ticket' => 'true'));
        } catch (PDOException $e) {
            throw new BasePDOException($e);
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * Deve realizar a consulta das Unidades que contém os parâmetros listados.
     * A pesquisa é realizada desconsiderando Case (case insensitive) e Acentuação
     * Caso a consulta ($like) tenha somente um parâmetro, deve considerar SIGLA e NOME com OR
     * Caso possua mais de um parametro, o sistema deve considerar somente o NOME com AND
     * O resultado da pesquisa deve ser ORDENADO por NOME e LIMITADO a 100 registros
     */
    public function getListTramites($id = null, $like = '') {
        try {

            $id = $id ? $id : Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;

            $strSearchWS = preg_replace('/\s\s+/', ' ', $_GET['query']);

            $arrWords = explode(' ', $strSearchWS);

            $strConectivo = "";

            $arrClausule = array();
            if (count($arrWords) == 1) {//Se consulta possuir somente 1 parametro, pesquisa em SIGLA e NOME
                //Define conectivo
                $strConectivo = "OR";

                $arrClausule[] = "fn_remove_acentuacao(U.SIGLA) ILIKE fn_remove_acentuacao(?)";
                $arrClausule[] = "fn_remove_acentuacao(U.NOME) ILIKE fn_remove_acentuacao(?)";
                $arrWords[0] = "%{$arrWords[0]}%";
                $arrWords[1] = "%{$arrWords[0]}%";
            } else {//Pesquisa somente em NOME
                $strConectivo = "AND";

                $i = 0;
                foreach ($arrWords as $word) {
                    $arrClausule[] = "fn_remove_acentuacao(U.NOME) ILIKE fn_remove_acentuacao(?)";
                    $arrWords[$i] = "%{$word}%";
                    $i++;
                }
            }

            //Create AND Clausule with ILIKE
            $strAND = sprintf(" AND (%s) ", implode(" {$strConectivo} ", $arrClausule));

            $strSQL = "
                SELECT U.ID AS ID, U.SIGLA AS SIGLA, U.NOME AS NOME 
                FROM TB_UNIDADES U
                    INNER JOIN TB_TRAMITES T ON T.ID_REFERENCIA = U.ID
                WHERE 
                    (U.ID != U.UOP) 
                    AND U.ST_ATIVO = '1'
                    {$strAND}
                    AND T.ID_UNIDADE = ?
                    ORDER BY U.NOME
                    LIMIT 100
            ";

            //Get Statement Object
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($strSQL);

            //Bind Statement
            $i = 0;
            while ($i < count($arrWords)) {
                $stmt->bindParam($i + 1, $arrWords[$i], PDO::PARAM_STR);
                $i++;
            }

            //Bind ID_UNIDADE
            $stmt->bindParam($i + 1, $id, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            print($e->getMessage());
        }
    }

    /**
     * 
     */
    public static function alterarVisibilidadeTramite($id_unidade, $id_referencia, $status) {
        try {
            new Base();



            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $list = Controlador::getInstance()->getConnection()->connection->prepare("SELECT 1 FROM TB_TRAMITES WHERE ID_UNIDADE = ? AND ID_REFERENCIA = ? LIMIT 1");
            $list->bindParam(1, $id_unidade, PDO::PARAM_INT);
            $list->bindParam(2, $id_referencia, PDO::PARAM_INT);
            $list->execute();

            $out = $list->fetchAll(PDO::FETCH_ASSOC);

            if (empty($out)) {
                /**
                 * Insert
                 */
                $insert = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_TRAMITES (ST_PERMISSAO,ID_UNIDADE,ID_REFERENCIA) VALUES (?,?,?)");
                $insert->bindParam(1, $status, PDO::PARAM_INT);
                $insert->bindParam(2, $id_unidade, PDO::PARAM_INT);
                $insert->bindParam(3, $id_referencia, PDO::PARAM_INT);
                $insert->execute();
            } else {
                /**
                 * Update
                 */
                $update = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_TRAMITES SET ST_PERMISSAO = ? WHERE ID_UNIDADE = ? AND ID_REFERENCIA = ?");
                $update->bindParam(1, $status, PDO::PARAM_INT);
                $update->bindParam(2, $id_unidade, PDO::PARAM_INT);
                $update->bindParam(3, $id_referencia, PDO::PARAM_INT);
                $update->execute();
            }

            Controlador::getInstance()->getConnection()->connection->commit();

            return array('success' => 'true', 'message' => Util::fixErrorString('Permissão alterada com sucesso!'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollBack();
            throw new Exception($e);
        }
    }

    /**
     * 
     */
    public static function registrarHistoricoDeTramiteDocumentos($digital, $acao, $origem = 'XXXXX', $destino = 'XXXXX') {
        try {




            $id_usuario = $this->_usuario->id;
            $id_unidade = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;
            $id_unidade_original = Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
            $nome_usuario = Zend_Auth::getInstance()->getIdentity()->NOME;
            $diretoria = DaoUnidade::getUnidade($id_unidade_original, 'nome');

            $stmm = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_DOCUMENTOS"
                    . " (DIGITAL,ID_USUARIO,USUARIO,ID_UNIDADE,DIRETORIA,ACAO,ORIGEM,DESTINO,DT_TRAMITE)"
                    . " VALUES (?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");

            $stmm->bindParam(1, $digital, PDO::PARAM_STR);
            $stmm->bindParam(2, $id_usuario, PDO::PARAM_INT);
            $stmm->bindParam(3, $nome_usuario, PDO::PARAM_STR);
            $stmm->bindParam(4, $id_unidade, PDO::PARAM_INT);
            $stmm->bindParam(5, $diretoria, PDO::PARAM_STR);
            $stmm->bindParam(6, $acao, PDO::PARAM_STR);
            $stmm->bindParam(7, $origem, PDO::PARAM_STR);
            $stmm->bindParam(8, $destino, PDO::PARAM_STR);
            $stmm->execute();

            return Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_HISTORICO_TRAMITE_DOCUMENTOS_ID_SEQ');
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

}